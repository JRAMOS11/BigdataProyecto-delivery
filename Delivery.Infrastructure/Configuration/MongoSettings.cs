namespace Delivery.Infrastructure.Configuration
{
    public class MongoSettings
    {
        public string ConnectionString { get; set; }
            = "mongodb://localhost:27017";

        public string DatabaseName { get; set; }
            = "delivery_bigdata";

        public string PedidosCollectionName { get; set; }
            = "pedidos";
    }
}