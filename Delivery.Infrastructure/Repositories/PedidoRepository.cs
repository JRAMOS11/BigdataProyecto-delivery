using Delivery.Infrastructure.Configuration;
using Delivery.Shared.Interfaces;
using Delivery.Shared.Models;
using MongoDB.Driver;

namespace Delivery.Infrastructure.Repositories
{
    public class PedidoRepository : IPedidoRepository
    {
        private readonly IMongoCollection<PedidoEvento> _pedidos;

        public PedidoRepository(MongoSettings settings)
        {
            var cliente = new MongoClient(
                settings.ConnectionString
            );

            var baseDatos = cliente.GetDatabase(
                settings.DatabaseName
            );

            _pedidos = baseDatos.GetCollection<PedidoEvento>(
                settings.PedidosCollectionName
            );
        }

        public async Task GuardarAsync(PedidoEvento pedido)
        {
            await _pedidos.InsertOneAsync(pedido);
        }

        public async Task<List<PedidoEvento>> ObtenerTodosAsync()
        {
            return await _pedidos
                .Find(_ => true)
                .SortByDescending(pedido => pedido.FechaHora)
                .ToListAsync();
        }

        public async Task<PedidoEvento?> ObtenerPorIdAsync(
            int pedidoId
        )
        {
            return await _pedidos
                .Find(pedido => pedido.PedidoId == pedidoId)
                .FirstOrDefaultAsync();
        }

        public async Task<bool> ExisteAsync(int pedidoId)
        {
            return await _pedidos
                .Find(pedido => pedido.PedidoId == pedidoId)
                .AnyAsync();
        }
    }
}
