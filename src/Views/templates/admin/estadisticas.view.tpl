<section class="dashboard">
    <div class="dashboard-header">
        <div>
            <p class="dashboard-label">
                MONITOREO EN TIEMPO REAL
            </p>

            <h1>
                Estadísticas Delivery Big Data
            </h1>

            <p>
                Información obtenida desde Kafka, MongoDB y Delivery.Api.
            </p>
        </div>

        <div class="api-status">
            <span id="apiPoint"></span>
            <span id="apiStatusText">
                Conectando...
            </span>
        </div>
    </div>

    <div class="dashboard-actions">
        <div>
            <h2>Resumen general</h2>
            <p>
                Datos actualizados automáticamente.
            </p>
        </div>

        <button id="btnActualizar" type="button">
            Actualizar
        </button>
    </div>

    <div class="stats-grid">
        <article class="stat-card">
            <span class="stat-icon">📦</span>
            <div>
                <p>Total pedidos</p>
                <h3 id="totalPedidos">0</h3>
            </div>
        </article>

        <article class="stat-card">
            <span class="stat-icon">L</span>
            <div>
                <p>Total ventas</p>
                <h3 id="totalVentas">L 0.00</h3>
            </div>
        </article>

        <article class="stat-card">
            <span class="stat-icon">⏳</span>
            <div>
                <p>Pendientes</p>
                <h3 id="pendientes">0</h3>
            </div>
        </article>

        <article class="stat-card">
            <span class="stat-icon">⚙️</span>
            <div>
                <p>En proceso</p>
                <h3 id="enProceso">0</h3>
            </div>
        </article>

        <article class="stat-card">
            <span class="stat-icon">✅</span>
            <div>
                <p>Entregados</p>
                <h3 id="entregados">0</h3>
            </div>
        </article>

        <article class="stat-card">
            <span class="stat-icon">🛵</span>
            <div>
                <p>Repartidores</p>
                <h3 id="repartidores">0</h3>
            </div>
        </article>
    </div>

    <div class="charts-grid">
        <article class="dashboard-panel">
            <h2>Pedidos por zona</h2>

            <div class="chart-box">
                <canvas id="chartZonas"></canvas>
            </div>
        </article>

        <article class="dashboard-panel">
            <h2>Estado de pedidos</h2>

            <div class="chart-box">
                <canvas id="chartEstados"></canvas>
            </div>
        </article>
    </div>

    <article class="dashboard-panel">
        <div class="panel-title">
            <div>
                <h2>Saturación por zona</h2>
                <p>
                    Comparación entre pedidos y repartidores disponibles.
                </p>
            </div>

            <span id="zonasCount">
                0 zonas
            </span>
        </div>

        <div id="zonasContainer" class="zones-grid">
            Cargando zonas...
        </div>
    </article>

    <article class="dashboard-panel">
        <div class="panel-title">
            <div>
                <h2>Pedidos registrados</h2>
                <p>
                    Información almacenada en MongoDB.
                </p>
            </div>

            <div class="filters">
                <select id="filterZona">
                    <option value="">
                        Todas las zonas
                    </option>
                </select>

                <select id="filterEstado">
                    <option value="">
                        Todos los estados
                    </option>

                    <option value="Pendiente">
                        Pendiente
                    </option>

                    <option value="En proceso">
                        En proceso
                    </option>

                    <option value="Entregado">
                        Entregado
                    </option>

                    <option value="Cancelado">
                        Cancelado
                    </option>
                </select>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Zona</th>
                        <th>Restaurante</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody id="pedidosTable">
                    <tr>
                        <td colspan="7">
                            Cargando pedidos...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>

    <div class="dashboard-footer">
        <span>Delivery Big Data</span>
        <span id="lastUpdate">Sin actualizar</span>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="public/js/estadisticas.js"></script>