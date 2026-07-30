namespace Delivery.Shared.Models
{
    public class ResumenZona
    {
        public string Zona { get; set; } = string.Empty;

        public int TotalPedidos { get; set; }

        public int PedidosPendientes { get; set; }

        public int PedidosEnProceso { get; set; }

        public int PedidosEntregados { get; set; }

        public int RepartidoresDisponibles { get; set; }

        public decimal TotalVentas { get; set; }

        public decimal NivelSaturacion { get; set; }

        public string EstadoSaturacion { get; set; }
            = string.Empty;
    }
}
