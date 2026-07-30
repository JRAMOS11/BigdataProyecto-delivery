using System.Text.Json;
using Confluent.Kafka;
using Delivery.Producer.Configuration;
using Delivery.Shared.Models;

namespace Delivery.Producer.Services
{
    public class KafkaProducerService
    {
        private readonly KafkaSettings _settings;

        public KafkaProducerService(KafkaSettings settings)
        {
            _settings = settings;
        }

        public async Task EnviarPedidoAsync(PedidoEvento pedido)
        {
            var config = new ProducerConfig
            {
                BootstrapServers = _settings.BootstrapServers
            };

            using var producer =
                new ProducerBuilder<string, string>(config).Build();

            var json = JsonSerializer.Serialize(pedido);

            var mensaje = new Message<string, string>
            {
                Key = pedido.Zona,
                Value = json
            };

            await producer.ProduceAsync(
                _settings.Topic,
                mensaje
            );
        }
    }
}
