using Delivery.Producer.Services;
using Delivery.Shared.Models;
using Microsoft.AspNetCore.Mvc;

namespace Delivery.Producer.Controllers
{
    [ApiController]
    [Route("api/pedidos")]
    public class PedidosController : ControllerBase
    {
        private readonly KafkaProducerService _producerService;

        public PedidosController(
            KafkaProducerService producerService
        )
        {
            _producerService = producerService;
        }

        [HttpPost]
        public async Task<IActionResult> EnviarPedido(
            PedidoEvento pedido
        )
        {
            if (pedido.PedidoId <= 0)
            {
                return BadRequest(
                    "El identificador del pedido es obligatorio."
                );
            }

            if (string.IsNullOrWhiteSpace(pedido.Zona))
            {
                return BadRequest(
                    "La zona del pedido es obligatoria."
                );
            }

            await _producerService.EnviarPedidoAsync(pedido);

            return Ok(new
            {
                mensaje = "Pedido enviado a Kafka.",
                pedidoId = pedido.PedidoId
            });
        }
    }
}
