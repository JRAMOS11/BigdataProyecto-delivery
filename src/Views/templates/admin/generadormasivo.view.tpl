<section class="generator-page">

    <div class="generator-header">
        <div>
            <p class="generator-label">
                SIMULADOR DE EVENTOS
            </p>

            <h1>Generador Masivo de Pedidos</h1>

            <p>
                Envía pedidos al Producer para procesarlos mediante Kafka,
                Consumer y MongoDB.
            </p>
        </div>

        <div class="producer-status">
            <span id="producerPoint"></span>
            <span id="producerStatusText">
                Verificando Producer...
            </span>
        </div>
    </div>

    <div class="generator-grid">

        <article class="generator-panel">

            <h2>Configuración de la simulación</h2>

            <div class="form-grid">

                <div class="form-group">
                    <label for="zona">Zona</label>

                    <select id="zona">
                        <option value="Centro">Centro</option>
                        <option value="Norte">Norte</option>
                        <option value="Sur">Sur</option>
                        <option value="Este">Este</option>
                        <option value="Oeste">Oeste</option>
                        <option value="Aleatoria">
                            Aleatoria
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cantidad">
                        Cantidad de pedidos
                    </label>

                    <input
                        id="cantidad"
                        type="number"
                        min="1"
                        max="5000"
                        value="10"
                    >
                </div>

                <div class="form-group">
                    <label for="intensidad">
                        Intensidad
                    </label>

                    <select id="intensidad">
                        <option value="normal">
                            Normal
                        </option>

                        <option value="media">
                            Media
                        </option>

                        <option value="alta">
                            Hora pico
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="intervalo">
                        Intervalo entre pedidos
                    </label>

                    <input
                        id="intervalo"
                        type="number"
                        min="0"
                        max="10000"
                        value="500"
                    >

                    <small>Tiempo en milisegundos.</small>
                </div>

            </div>

            <div class="generator-buttons">

                <button
                    type="button"
                    id="btnIndividual"
                    class="btn-secondary">
                    Enviar pedido individual
                </button>

                <button
                    type="button"
                    id="btnSimular"
                    class="btn-primary">
                    Iniciar simulación
                </button>

                <button
                    type="button"
                    id="btnHoraPico"
                    class="btn-warning">
                    Simular hora pico
                </button>

                <button
                    type="button"
                    id="btnDetener"
                    class="btn-danger"
                    disabled>
                    Detener
                </button>

            </div>

        </article>

        <article class="generator-panel">

            <h2>Información del envío</h2>

            <div class="status-list">

                <div class="status-item">
                    <span>Estado</span>
                    <strong id="estadoEnvio">
                        Listo para enviar
                    </strong>
                </div>

                <div class="status-item">
                    <span>Eventos enviados</span>
                    <strong id="eventosEnviados">
                        0
                    </strong>
                </div>

                <div class="status-item">
                    <span>Eventos fallidos</span>
                    <strong id="eventosFallidos">
                        0
                    </strong>
                </div>

                <div class="status-item">
                    <span>Tiempo transcurrido</span>
                    <strong id="tiempoTranscurrido">
                        00:00:00
                    </strong>
                </div>

                <div class="status-item">
                    <span>Rendimiento</span>
                    <strong id="rendimiento">
                        0 pedidos/segundo
                    </strong>
                </div>

            </div>

            <div class="progress-container">
                <div class="progress-info">
                    <span>Progreso</span>
                    <strong id="porcentajeProgreso">
                        0%
                    </strong>
                </div>

                <div class="progress-bar">
                    <div id="progressBar"></div>
                </div>
            </div>

        </article>

    </div>

    <article class="generator-panel log-panel">

        <div class="log-header">
            <div>
                <h2>Registro de eventos</h2>
                <p>
                    Resultado de cada pedido enviado.
                </p>
            </div>

            <button
                type="button"
                id="btnLimpiarLog"
                class="btn-secondary">
                Limpiar registro
            </button>
        </div>

        <div id="eventLog" class="event-log">
            <div class="log-line">
                El generador está listo.
            </div>
        </div>

    </article>

</section>

<script src="public/js/generador.js"></script>