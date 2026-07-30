using Delivery.Shared.Interfaces;
using Delivery.Shared.Models;
using Microsoft.AspNetCore.Mvc;

namespace Delivery.Api.Controllers
{
    [ApiController]
    [Route("api/dashboard")]
    public class DashboardController : ControllerBase
    {
        private readonly IPedidoRepository _pedidoRepository;

        public DashboardController(
            IPedidoRepository pedidoRepository
        )
        {
            _pedidoRepository = pedidoRepository;
        }

        [HttpGet]
        public async Task<IActionResult> ObtenerDashboard()
        {
            var pedidos =
                await _pedidoRepository.ObtenerTodosAsync();

            var totalPedidos = pedidos.Count;

            var totalVentas =
                pedidos.Sum(pedido => pedido.Total);

            var pendientes =
                pedidos.Count(pedido =>
                    pedido.Estado.Equals(
                        "Pendiente",
                        StringComparison.OrdinalIgnoreCase
                    )
                );

            var enProceso =
                pedidos.Count(pedido =>
                    pedido.Estado.Equals(
                        "En proceso",
                        StringComparison.OrdinalIgnoreCase
                    )
                );

            var entregados =
                pedidos.Count(pedido =>
                    pedido.Estado.Equals(
                        "Entregado",
                        StringComparison.OrdinalIgnoreCase
                    )
                );

            var totalRepartidores =
                pedidos
                    .GroupBy(pedido => pedido.Zona)
                    .Sum(grupo =>
                        grupo
                            .OrderByDescending(
                                pedido => pedido.FechaHora
                            )
                            .First()
                            .RepartidoresDisponibles
                    );

            var resumenZonas =
                pedidos
                    .Where(pedido =>
                        !string.IsNullOrWhiteSpace(
                            pedido.Zona
                        )
                    )
                    .GroupBy(pedido => pedido.Zona)
                    .Select(grupo =>
                    {
                        var ultimoPedido =
                            grupo
                                .OrderByDescending(
                                    pedido =>
                                        pedido.FechaHora
                                )
                                .First();

                        var repartidores =
                            ultimoPedido
                                .RepartidoresDisponibles;

                        var pedidosActivos =
                            grupo.Count(pedido =>
                                !pedido.Estado.Equals(
                                    "Entregado",
                                    StringComparison
                                        .OrdinalIgnoreCase
                                )
                                &&
                                !pedido.Estado.Equals(
                                    "Cancelado",
                                    StringComparison
                                        .OrdinalIgnoreCase
                                )
                            );

                        decimal saturacion;

                        if (repartidores <= 0)
                        {
                            saturacion =
                                pedidosActivos > 0
                                    ? 100
                                    : 0;
                        }
                        else
                        {
                            saturacion =
                                Math.Round(
                                    (decimal)pedidosActivos
                                    / repartidores
                                    * 100,
                                    2
                                );
                        }

                        string estadoSaturacion;

                        if (saturacion < 50)
                        {
                            estadoSaturacion = "Baja";
                        }
                        else if (saturacion < 80)
                        {
                            estadoSaturacion = "Media";
                        }
                        else
                        {
                            estadoSaturacion = "Alta";
                        }

                        return new ResumenZona
                        {
                            Zona = grupo.Key,

                            TotalPedidos =
                                grupo.Count(),

                            PedidosPendientes =
                                grupo.Count(pedido =>
                                    pedido.Estado.Equals(
                                        "Pendiente",
                                        StringComparison
                                            .OrdinalIgnoreCase
                                    )
                                ),

                            PedidosEnProceso =
                                grupo.Count(pedido =>
                                    pedido.Estado.Equals(
                                        "En proceso",
                                        StringComparison
                                            .OrdinalIgnoreCase
                                    )
                                ),

                            PedidosEntregados =
                                grupo.Count(pedido =>
                                    pedido.Estado.Equals(
                                        "Entregado",
                                        StringComparison
                                            .OrdinalIgnoreCase
                                    )
                                ),

                            RepartidoresDisponibles =
                                repartidores,

                            TotalVentas =
                                grupo.Sum(
                                    pedido => pedido.Total
                                ),

                            NivelSaturacion =
                                saturacion,

                            EstadoSaturacion =
                                estadoSaturacion
                        };
                    })
                    .OrderByDescending(
                        zona => zona.NivelSaturacion
                    )
                    .ToList();

            return Ok(new
            {
                totalPedidos,
                totalVentas,
                pendientes,
                enProceso,
                entregados,
                totalRepartidores,
                resumenZonas
            });
        }

        [HttpGet("zonas")]
        public async Task<IActionResult> ObtenerPorZonas()
        {
            var pedidos =
                await _pedidoRepository.ObtenerTodosAsync();

            var resumen =
                pedidos
                    .GroupBy(pedido => pedido.Zona)
                    .Select(grupo => new
                    {
                        zona = grupo.Key,

                        totalPedidos =
                            grupo.Count(),

                        totalVentas =
                            grupo.Sum(
                                pedido => pedido.Total
                            ),

                        repartidoresDisponibles =
                            grupo
                                .OrderByDescending(
                                    pedido =>
                                        pedido.FechaHora
                                )
                                .First()
                                .RepartidoresDisponibles
                    })
                    .OrderByDescending(
                        zona => zona.totalPedidos
                    )
                    .ToList();

            return Ok(resumen);
        }
    }
}
