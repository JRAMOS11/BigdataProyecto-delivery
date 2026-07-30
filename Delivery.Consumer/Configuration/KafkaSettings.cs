using System;
using System.Collections.Generic;
using System.Text;

namespace Delivery.Consumer.Configuration
{
    public class KafkaSettings
    {
        public string BootstrapServers { get; set; } = "localhost:9092";

        public string Topic { get; set; } = "pedidos-delivery";

        public string GroupId { get; set; } = "delivery-consumer";
    }
}
