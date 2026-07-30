# Delivery Big Data

## Sistema Distribuido de Simulacion y Monitoreo de Pedidos mediante Apache Kafka y MongoDB

---

# Descripcion del proyecto

Delivery Big Data es un sistema distribuido desarrollado para simular el funcionamiento de una plataforma de entrega de comida. El proyecto genera pedidos desde una aplicacion web, los envÃ­a a un Producer desarrollado en .NET, los procesa mediante Apache Kafka y un Consumer, los almacena en MongoDB y posteriormente los muestra en un Dashboard con estadi­sticas en tiempo real.

El objetivo principal del proyecto es demostrar el funcionamiento de una arquitectura basada en eventos utilizando tecnologÃ­as modernas de Big Data y microservicios.

# Objetivos

## Objetivo general

Desarrollar un sistema distribuido capaz de generar, procesar, almacenar y visualizar pedidos utilizando Apache Kafka como plataforma de mensajerÃ­a y MongoDB como base de datos NoSQL.

## Objetivos especificos

- Simular la generacion masiva de pedidos.
- Implementar un Producer utilizando .NET.
- Utilizar Apache Kafka para el envÃ­o de eventos.
- Implementar un Consumer encargado de procesar los mensajes.
- Almacenar la informaciÃ³n en MongoDB.
- Desarrollar una API para consultar la informacion almacenada.
- Mostrar estadÃ­sticas en tiempo real mediante un Dashboard web.
- Demostrar el funcionamiento completo del flujo de datos.

# Tecnologias utilizadas

| Tecnologia | Uso |
|------------|-----|
| .NET 10 | Producer, Consumer y API |
| Apache Kafka | MensajerÃ­a distribuida |
| MongoDB | Base de datos NoSQL |
| PHP | Aplicacion web |
| JavaScript | Comunicacion con la API |
| HTML5 | Interfaces |
| CSS3 | DiseÃ±o |
| Bootstrap | Componentes visuales |
| Visual Studio 2022 | Desarrollo Backend |
| Visual Studio Code | Desarrollo Frontend |
| XAMPP | Servidor local |

#Aplicación Web (PHP)

Producer (.NET)

Apache Kafka

Consumer (.NET)

MongoDB

Delivery API

Dashboard

# Funcionalidades implementadas

- Generador masivo de pedidos.
- EnvÃ­o de pedidos individuales.
- Simulacion de hora pico.
- Producer desarrollado en .NET.
- Comunicacion mediante Apache Kafka.
- Consumer para procesamiento de eventos.
- Almacenamiento automÃ¡tico en MongoDB.
- API REST.
- Dashboard con estadi­sticas.
- Tabla de pedidos registrados.

# Flujo de funcionamiento

1. El usuario genera pedidos desde la aplicacion web.
2. El Producer recibe los pedidos.
3. Los eventos son publicados en Apache Kafka.
4. El Consumer procesa los mensajes.
5. Los pedidos son almacenados en MongoDB.
6. La API consulta la informacion.
7. El Dashboard presenta estadÃ­sticas en tiempo real.

# Estructura del proyecto

DeliveryBigData
│
├── Delivery.Producer
├── Delivery.Consumer
├── Delivery.Api
├── Delivery.Infrastructure
├── Delivery.Shared
└── delivery_app

# Resultados obtenidos

- Envi­o correcto de pedidos.
- Procesamiento exitoso mediante Kafka.
- Almacenamiento correcto en MongoDB.
- Consulta mediante API REST.
- VisualizaciÃ³n de estadÃ­sticas y pedidos en tiempo real.

# Conclusiones

El proyecto demuestra el funcionamiento de una arquitectura distribuida basada en eventos utilizando Apache Kafka y MongoDB. La integracion entre PHP y .NET permitiÃ³ desarrollar una solucion funcional, escalable y capaz de procesar pedidos en tiempo real.
