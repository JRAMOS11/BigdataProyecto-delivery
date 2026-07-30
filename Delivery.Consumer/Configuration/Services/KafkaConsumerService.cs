using System.Text.Json;
using Confluent.Kafka;
using Delivery.Consumer.Configuration;
using Delivery.Shared.Interfaces;
using Delivery.Shared.Models;

namespace Delivery.Consumer.Services
{
    public class KafkaConsumerService : BackgroundService
    {
        private readonly KafkaSettings _settings;
        private readonly IPedidoRepository _pedidoRepository;
        private readonly ILogger<KafkaConsumerService> _logger;

        public KafkaConsumerService(
            KafkaSettings settings,
            IPedidoRepository pedidoRepository,
            ILogger<KafkaConsumerService> logger
        )
        {
            _settings = settings;
            _pedidoRepository = pedidoRepository;
            _logger = logger;
        }

        protected override Task ExecuteAsync(
            CancellationToken stoppingToken
        )
        {
            return Task.Run(
                async () =>
                {
                    var config = new ConsumerConfig
                    {
                        BootstrapServers = _settings.BootstrapServers,
                        GroupId = _settings.GroupId,
                        AutoOffsetReset = AutoOffsetReset.Earliest,
                        EnableAutoCommit = false
                    };

                    using var consumer =
                        new ConsumerBuilder<string, string>(config).Build();

                    consumer.Subscribe(_settings.Topic);

                    _logger.LogInformation(
                        "Consumer escuchando el topic {Topic}",
                        _settings.Topic
                    );

                    try
                    {
                        while (!stoppingToken.IsCancellationRequested)
                        {
                            try
                            {
                                var resultado =
                                    consumer.Consume(stoppingToken);

                                var pedido =
                                    JsonSerializer.Deserialize<PedidoEvento>(
                                        resultado.Message.Value,
                                        new JsonSerializerOptions
                                        {
                                            PropertyNameCaseInsensitive = true
                                        }
                                    );

                                if (pedido is null)
                                {
                                    _logger.LogWarning(
                                        "Se recibió un mensaje inválido."
                                    );

                                    consumer.Commit(resultado);
                                    continue;
                                }

                                _logger.LogInformation(
                                    "Pedido recibido: {PedidoId} - {Cliente} - {Zona} - Total: {Total}",
                                    pedido.PedidoId,
                                    pedido.Cliente,
                                    pedido.Zona,
                                    pedido.Total
                                );

                                var existe =
                                    await _pedidoRepository.ExisteAsync(
                                        pedido.PedidoId
                                    );

                                if (existe)
                                {
                                    _logger.LogWarning(
                                        "Pedido duplicado: {PedidoId}",
                                        pedido.PedidoId
                                    );

                                    consumer.Commit(resultado);
                                    continue;
                                }

                                await _pedidoRepository.GuardarAsync(pedido);

                                consumer.Commit(resultado);

                                _logger.LogInformation(
                                    "Pedido {PedidoId} guardado correctamente en MongoDB.",
                                    pedido.PedidoId
                                );
                            }
                            catch (JsonException error)
                            {
                                _logger.LogError(
                                    error,
                                    "El mensaje recibido no tiene un JSON válido."
                                );
                            }
                            catch (ConsumeException error)
                            {
                                _logger.LogError(
                                    error,
                                    "Error al consumir el mensaje de Kafka."
                                );
                            }
                            catch (Exception error)
                            {
                                _logger.LogError(
                                    error,
                                    "Error al procesar o guardar el pedido."
                                );
                            }
                        }
                    }
                    catch (OperationCanceledException)
                    {
                        _logger.LogInformation(
                            "El Consumer se está deteniendo."
                        );
                    }
                    finally
                    {
                        consumer.Close();
                    }
                },
                stoppingToken
            );
        }
    }
}
