using System;
using System.Collections.Generic;
using System.Text;

namespace Delivery.Shared.Models
{
    public class DetallePedidoEvento
    {
        public int PlatoId { get; set; }

        public string Nombre { get; set; } = string.Empty;

        public int Cantidad { get; set; }

        public decimal Precio { get; set; }

        public decimal Subtotal
        {
            get
            {
                return Cantidad * Precio;
            }
        }
    }
}
