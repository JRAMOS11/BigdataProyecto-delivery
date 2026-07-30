using Delivery.Consumer.Configuration;
using Delivery.Consumer.Services;
using Delivery.Infrastructure.Configuration;
using Delivery.Infrastructure.Repositories;
using Delivery.Shared.Interfaces;

var builder = Host.CreateApplicationBuilder(args);

var kafkaSettings = new KafkaSettings
{
    BootstrapServers =
        builder.Configuration["Kafka:BootstrapServers"]
        ?? "localhost:9092",

    Topic =
        builder.Configuration["Kafka:Topic"]
        ?? "pedidos-delivery",

    GroupId =
        builder.Configuration["Kafka:GroupId"]
        ?? "delivery-consumer"
};

var mongoSettings = new MongoSettings
{
    ConnectionString =
        builder.Configuration["MongoDB:ConnectionString"]
        ?? "mongodb://localhost:27017",

    DatabaseName =
        builder.Configuration["MongoDB:DatabaseName"]
        ?? "delivery_bigdata",

    PedidosCollectionName =
        builder.Configuration["MongoDB:PedidosCollectionName"]
        ?? "pedidos"
};

builder.Services.AddSingleton(kafkaSettings);
builder.Services.AddSingleton(mongoSettings);

builder.Services.AddSingleton<IPedidoRepository, PedidoRepository>();

builder.Services.AddHostedService<KafkaConsumerService>();

var host = builder.Build();

host.Run();
