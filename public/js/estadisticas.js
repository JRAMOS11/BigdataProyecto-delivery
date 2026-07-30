const API_BASE = "http://127.0.0.1:5181";

let pedidos = [];
let chartZonas = null;
let chartEstados = null;

async function cargarDatos() {
    const btn = document.getElementById("btnActualizar");

    try {
        btn.disabled = true;
        btn.textContent = "Actualizando...";

        const [resDashboard, resZonas, resPedidos] =
            await Promise.all([
                fetch(`${API_BASE}/api/dashboard`),
                fetch(`${API_BASE}/api/dashboard/zonas`),
                fetch(`${API_BASE}/api/pedidos`)
            ]);

        if (
            !resDashboard.ok ||
            !resZonas.ok ||
            !resPedidos.ok
        ) {
            throw new Error("Error al consultar la API");
        }

        const dashboard = await resDashboard.json();
        const zonas = await resZonas.json();
        pedidos = await resPedidos.json();

        mostrarResumen(dashboard);
        mostrarZonas(dashboard.resumenZonas || []);
        mostrarPedidos(pedidos);
        cargarFiltroZonas(zonas);
        crearGraficaZonas(zonas);
        crearGraficaEstados(dashboard);
        conexionCorrecta();

        document.getElementById("lastUpdate").textContent =
            "Última actualización: " +
            new Date().toLocaleTimeString("es-HN");
    } catch (error) {
        console.error(error);
        conexionFallida();
    } finally {
        btn.disabled = false;
        btn.textContent = "Actualizar";
    }
}

function mostrarResumen(data) {
    document.getElementById("totalPedidos").textContent =
        data.totalPedidos ?? 0;

    document.getElementById("totalVentas").textContent =
        formatearDinero(data.totalVentas ?? 0);

    document.getElementById("pendientes").textContent =
        data.pendientes ?? 0;

    document.getElementById("enProceso").textContent =
        data.enProceso ?? 0;

    document.getElementById("entregados").textContent =
        data.entregados ?? 0;

    document.getElementById("repartidores").textContent =
        data.totalRepartidores ?? 0;
}

function mostrarZonas(zonas) {
    const container =
        document.getElementById("zonasContainer");

    container.innerHTML = "";

    document.getElementById("zonasCount").textContent =
        `${zonas.length} zonas`;

    if (zonas.length === 0) {
        container.innerHTML =
            "<p>No hay zonas registradas.</p>";

        return;
    }

    zonas.forEach(zona => {
        const estado =
            (zona.estadoSaturacion || "Baja")
                .toLowerCase();

        const porcentaje =
            Math.min(
                Number(zona.nivelSaturacion) || 0,
                100
            );

        const card =
            document.createElement("article");

        card.className = "zone-card";

        card.innerHTML = `
            <div class="zone-head">
                <h3>${escapar(zona.zona)}</h3>

                <span class="zone-status status-${estado}">
                    ${escapar(zona.estadoSaturacion)}
                </span>
            </div>

            <strong>
                ${zona.nivelSaturacion}% de saturación
            </strong>

            <div class="zone-progress">
                <div
                    class="progress-${estado}"
                    style="width:${porcentaje}%">
                </div>
            </div>

            <div class="zone-data">
                <div>
                    <span>Pedidos</span>
                    <strong>${zona.totalPedidos}</strong>
                </div>

                <div>
                    <span>Repartidores</span>
                    <strong>
                        ${zona.repartidoresDisponibles}
                    </strong>
                </div>

                <div>
                    <span>Pendientes</span>
                    <strong>
                        ${zona.pedidosPendientes}
                    </strong>
                </div>

                <div>
                    <span>Ventas</span>
                    <strong>
                        ${formatearDinero(zona.totalVentas)}
                    </strong>
                </div>
            </div>
        `;

        container.appendChild(card);
    });
}

function mostrarPedidos(lista) {
    const tbody =
        document.getElementById("pedidosTable");

    tbody.innerHTML = "";

    if (lista.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7">
                    No hay pedidos registrados.
                </td>
            </tr>
        `;

        return;
    }

    const ordenados = [...lista].sort(
        (a, b) =>
            new Date(b.fechaHora) -
            new Date(a.fechaHora)
    );

    ordenados.forEach(pedido => {
        const tr =
            document.createElement("tr");

        tr.innerHTML = `
            <td>${pedido.pedidoId}</td>
            <td>${escapar(pedido.cliente)}</td>
            <td>${escapar(pedido.zona)}</td>
            <td>${escapar(pedido.restaurante)}</td>
            <td>${formatearDinero(pedido.total)}</td>
            <td>
                <span class="
                    order-status
                    ${claseEstado(pedido.estado)}
                ">
                    ${escapar(pedido.estado)}
                </span>
            </td>
            <td>${formatearFecha(pedido.fechaHora)}</td>
        `;

        tbody.appendChild(tr);
    });
}

function cargarFiltroZonas(zonas) {
    const select =
        document.getElementById("filterZona");

    const actual = select.value;

    select.innerHTML = `
        <option value="">
            Todas las zonas
        </option>
    `;

    zonas.forEach(zona => {
        const option =
            document.createElement("option");

        option.value = zona.zona;
        option.textContent = zona.zona;

        select.appendChild(option);
    });

    select.value = actual;
}

function aplicarFiltros() {
    const zona =
        document
            .getElementById("filterZona")
            .value
            .toLowerCase();

    const estado =
        document
            .getElementById("filterEstado")
            .value
            .toLowerCase();

    const resultado =
        pedidos.filter(pedido => {
            const coincideZona =
                !zona ||
                pedido.zona.toLowerCase() === zona;

            const coincideEstado =
                !estado ||
                pedido.estado.toLowerCase() === estado;

            return coincideZona && coincideEstado;
        });

    mostrarPedidos(resultado);
}

function crearGraficaZonas(zonas) {
    const ctx =
        document
            .getElementById("chartZonas")
            .getContext("2d");

    if (chartZonas) {
        chartZonas.destroy();
    }

    chartZonas = new Chart(ctx, {
        type: "bar",

        data: {
            labels: zonas.map(zona => zona.zona),

            datasets: [{
                label: "Pedidos",
                data: zonas.map(
                    zona => zona.totalPedidos
                ),
                backgroundColor: "#d97706",
                borderRadius: 7
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
}

function crearGraficaEstados(data) {
    const ctx =
        document
            .getElementById("chartEstados")
            .getContext("2d");

    if (chartEstados) {
        chartEstados.destroy();
    }

    const cancelados =
        pedidos.filter(
            pedido =>
                pedido.estado.toLowerCase() ===
                "cancelado"
        ).length;

    chartEstados = new Chart(ctx, {
        type: "doughnut",

        data: {
            labels: [
                "Pendientes",
                "En proceso",
                "Entregados",
                "Cancelados"
            ],

            datasets: [{
                data: [
                    data.pendientes ?? 0,
                    data.enProceso ?? 0,
                    data.entregados ?? 0,
                    cancelados
                ],

                backgroundColor: [
                    "#d89b00",
                    "#2563a8",
                    "#2f9e44",
                    "#c92a2a"
                ],

                borderWidth: 0
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: "bottom"
                }
            },

            cutout: "65%"
        }
    });
}

function claseEstado(estado) {
    const valor =
        (estado || "").toLowerCase();

    if (valor === "en proceso") {
        return "order-proceso";
    }

    if (valor === "entregado") {
        return "order-entregado";
    }

    if (valor === "cancelado") {
        return "order-cancelado";
    }

    return "order-pendiente";
}

function conexionCorrecta() {
    document.getElementById("apiPoint").style.background =
        "#62d57b";

    document.getElementById("apiStatusText").textContent =
        "Conectado con Delivery.Api";
}

function conexionFallida() {
    document.getElementById("apiPoint").style.background =
        "#ff6b6b";

    document.getElementById("apiStatusText").textContent =
        "No se pudo conectar con Delivery.Api";
}

function formatearDinero(valor) {
    return "L " +
        Number(valor || 0).toLocaleString(
            "es-HN",
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );
}

function formatearFecha(valor) {
    if (!valor) {
        return "Sin fecha";
    }

    return new Date(valor).toLocaleString("es-HN");
}

function escapar(valor) {
    const div = document.createElement("div");
    div.textContent = valor ?? "";
    return div.innerHTML;
}

document
    .getElementById("btnActualizar")
    .addEventListener("click", cargarDatos);

document
    .getElementById("filterZona")
    .addEventListener("change", aplicarFiltros);

document
    .getElementById("filterEstado")
    .addEventListener("change", aplicarFiltros);

cargarDatos();

setInterval(cargarDatos, 10000);