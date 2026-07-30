using System;
using System.Collections.Generic;
using System.Text;
using Delivery.Shared.Models;

namespace Delivery.Shared.Interfaces
{
    public interface IPedidoRepository
    {
        Task GuardarAsync(PedidoEvento pedido);

        Task<List<PedidoEvento>> ObtenerTodosAsync();

        Task<PedidoEvento?> ObtenerPorIdAsync(int pedidoId);

        Task<bool> ExisteAsync(int pedidoId);
    }
}
