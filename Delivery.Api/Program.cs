using Delivery.Infrastructure.Configuration;
using Delivery.Infrastructure.Repositories;
using Delivery.Shared.Interfaces;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddControllers();

builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

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

builder.Services.AddSingleton(mongoSettings);

builder.Services.AddSingleton<
    IPedidoRepository,
    PedidoRepository
>();

builder.Services.AddCors(options =>
{
    options.AddPolicy("PermitirDashboard", policy =>
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

app.UseCors("PermitirDashboard");

app.MapControllers();

app.Run();
