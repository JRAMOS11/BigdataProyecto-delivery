using Delivery.Shared.Interfaces;
using Microsoft.AspNetCore.Mvc;

namespace Delivery.Api.Controllers
{
    [ApiController]
    [Route("api/pedidos")]
    public class PedidosController : ControllerBase
    {
        private readonly IPedidoRepository _pedidoRepository;

        public PedidosController(
            IPedidoRepository pedidoRepository
        )
        {
            _pedidoRepository = pedidoRepository;
        }

        [HttpGet]
        public async Task<IActionResult> ObtenerTodos()
        {
            var pedidos =
                await _pedidoRepository.ObtenerTodosAsync();

            return Ok(pedidos);
        }

        [HttpGet("{pedidoId:int}")]
        public async Task<IActionResult> ObtenerPorId(
            int pedidoId
        )
        {
            var pedido =
                await _pedidoRepository.ObtenerPorIdAsync(
                    pedidoId
                );

            if (pedido is null)
            {
                return NotFound(new
                {
                    mensaje =
                        "No se encontró el pedido solicitado.",
                    pedidoId
                });
            }

            return Ok(pedido);
        }
    }
}
