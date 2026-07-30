using Delivery.Producer.Configuration;
using Delivery.Producer.Services;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddControllers();

builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

var kafkaSettings = new KafkaSettings
{
    BootstrapServers =
        builder.Configuration["Kafka:BootstrapServers"]
        ?? "localhost:9092",

    Topic =
        builder.Configuration["Kafka:Topic"]
        ?? "pedidos-delivery"
};

builder.Services.AddSingleton(kafkaSettings);
builder.Services.AddSingleton<KafkaProducerService>();

builder.Services.AddCors(options =>
{
    options.AddPolicy("PermitirPHP", policy =>
    {
        policy
            .AllowAnyOrigin()
            .AllowAnyHeader()
            .AllowAnyMethod();
    });
});

var app = builder.Build();

app.UseSwagger();
app.UseSwaggerUI();

app.UseCors("PermitirPHP");

app.MapControllers();

app.Run();
