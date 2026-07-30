using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Delivery.Shared.Models
{
    public class PedidoEvento
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        public int PedidoId { get; set; }

        public int UsuarioId { get; set; }

        public string Cliente { get; set; } = string.Empty;

        public string Zona { get; set; } = string.Empty;

        public string Restaurante { get; set; } = string.Empty;

        public decimal Total { get; set; }

        public int CantidadItems { get; set; }

        public string Estado { get; set; } = string.Empty;

        public DateTime FechaHora { get; set; }

        public int RepartidoresDisponibles { get; set; }

        public List<DetallePedidoEvento> Platos { get; set; } = new();
    }
}
