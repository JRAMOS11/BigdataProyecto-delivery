const PRODUCER_URL =
    "http://127.0.0.1:5018/api/pedidos";

const zonasDisponibles = [
    "Centro",
    "Norte",
    "Sur",
    "Este",
    "Oeste"
];

const clientes = [
    "Carlos Martínez",
    "María López",
    "José Flores",
    "Ana Rodríguez",
    "Luis Hernández",
    "Sofía Mejía",
    "Daniela Cruz",
    "Miguel Rivera",
    "Laura Gómez",
    "Pedro Castillo"
];

const restaurantes = [
    "Pizza House",
    "Burger Station",
    "Pollo Express",
    "Tacos El Centro",
    "Baleadas Doña Rosa",
    "Café Central",
    "Alitas Factory",
    "Sabor Catracho"
];

const platosDisponibles = [
    {
        platoId: 1,
        nombre: "Pizza margarita",
        precio: 150
    },
    {
        platoId: 2,
        nombre: "Hamburguesa clásica",
        precio: 120
    },
    {
        platoId: 3,
        nombre: "Pollo frito",
        precio: 145
    },
    {
        platoId: 4,
        nombre: "Tacos mexicanos",
        precio: 110
    },
    {
        platoId: 5,
        nombre: "Baleada especial",
        precio: 70
    },
    {
        platoId: 6,
        nombre: "Alitas BBQ",
        precio: 160
    },
    {
        platoId: 7,
        nombre: "Refresco natural",
        precio: 40
    },
    {
        platoId: 8,
        nombre: "Pastel de chocolate",
        precio: 85
    }
];

let simulacionActiva = false;
let detenerSolicitado = false;
let enviados = 0;
let fallidos = 0;
let totalSimulacion = 0;
let tiempoInicio = null;
let temporizador = null;

const zonaInput =
    document.getElementById("zona");

const cantidadInput =
    document.getElementById("cantidad");

const intensidadInput =
    document.getElementById("intensidad");

const intervaloInput =
    document.getElementById("intervalo");

const btnIndividual =
    document.getElementById("btnIndividual");

const btnSimular =
    document.getElementById("btnSimular");

const btnHoraPico =
    document.getElementById("btnHoraPico");

const btnDetener =
    document.getElementById("btnDetener");

const btnLimpiarLog =
    document.getElementById("btnLimpiarLog");

const estadoEnvio =
    document.getElementById("estadoEnvio");

const eventosEnviados =
    document.getElementById("eventosEnviados");

const eventosFallidos =
    document.getElementById("eventosFallidos");

const tiempoTranscurrido =
    document.getElementById("tiempoTranscurrido");

const rendimiento =
    document.getElementById("rendimiento");

const porcentajeProgreso =
    document.getElementById("porcentajeProgreso");

const progressBar =
    document.getElementById("progressBar");

const eventLog =
    document.getElementById("eventLog");

const producerPoint =
    document.getElementById("producerPoint");

const producerStatusText =
    document.getElementById("producerStatusText");

function numeroAleatorio(minimo, maximo) {
    return Math.floor(
        Math.random() * (maximo - minimo + 1)
    ) + minimo;
}

function elementoAleatorio(lista) {
    return lista[
        Math.floor(Math.random() * lista.length)
    ];
}

function obtenerZonaSeleccionada() {
    if (zonaInput.value === "Aleatoria") {
        return elementoAleatorio(zonasDisponibles);
    }

    return zonaInput.value;
}

function obtenerEstadoAleatorio() {
    const estados = [
        "Pendiente",
        "Pendiente",
        "Pendiente",
        "En proceso",
        "Entregado"
    ];

    return elementoAleatorio(estados);
}

function generarPlatos() {
    const cantidadPlatos =
        numeroAleatorio(1, 3);

    const platos = [];
    let total = 0;
    let cantidadItems = 0;

    for (let i = 0; i < cantidadPlatos; i++) {
        const plato =
            elementoAleatorio(platosDisponibles);

        const cantidad =
            numeroAleatorio(1, 3);

        platos.push({
            platoId: plato.platoId,
            nombre: plato.nombre,
            cantidad: cantidad,
            precio: plato.precio
        });

        total += plato.precio * cantidad;
        cantidadItems += cantidad;
    }

    return {
        platos,
        total,
        cantidadItems
    };
}

function generarPedido() {
    const detalle = generarPlatos();

    const pedidoId =
    numeroAleatorio(1000, 2000000000);

    return {
        pedidoId: pedidoId,
        usuarioId: numeroAleatorio(1, 1000),
        cliente: elementoAleatorio(clientes),
        zona: obtenerZonaSeleccionada(),
        restaurante:
            elementoAleatorio(restaurantes),
        total: detalle.total,
        cantidadItems:
            detalle.cantidadItems,
        estado: obtenerEstadoAleatorio(),
        fechaHora:
            new Date().toISOString(),
        repartidoresDisponibles:
            numeroAleatorio(1, 6),
        platos: detalle.platos
    };
}

async function enviarPedido(pedido) {
    const respuesta = await fetch(
        PRODUCER_URL,
        {
            method: "POST",

            headers: {
                "Content-Type":
                    "application/json"
            },

            body: JSON.stringify(pedido)
        }
    );

    if (!respuesta.ok) {
        let detalleError = "";

        try {
            detalleError =
                await respuesta.text();
        } catch {
            detalleError = "";
        }

        throw new Error(
            `HTTP ${respuesta.status} ${detalleError}`
        );
    }

    return respuesta;
}

function agregarLog(mensaje, tipo = "info") {
    const linea =
        document.createElement("div");

    linea.className =
        `log-line log-${tipo}`;

    const hora =
        new Date().toLocaleTimeString("es-HN");

    linea.textContent =
        `[${hora}] ${mensaje}`;

    eventLog.appendChild(linea);

    eventLog.scrollTop =
        eventLog.scrollHeight;
}

function actualizarPanel() {
    eventosEnviados.textContent = enviados;
    eventosFallidos.textContent = fallidos;

    const procesados =
        enviados + fallidos;

    const porcentaje =
        totalSimulacion > 0
            ? Math.round(
                (procesados / totalSimulacion) * 100
            )
            : 0;

    porcentajeProgreso.textContent =
        `${porcentaje}%`;

    progressBar.style.width =
        `${porcentaje}%`;

    if (tiempoInicio) {
        const segundos =
            Math.max(
                1,
                (Date.now() - tiempoInicio) / 1000
            );

        const pedidosPorSegundo =
            (enviados / segundos).toFixed(2);

        rendimiento.textContent =
            `${pedidosPorSegundo} pedidos/segundo`;
    }
}

function actualizarTiempo() {
    if (!tiempoInicio) {
        tiempoTranscurrido.textContent =
            "00:00:00";

        return;
    }

    const diferencia =
        Date.now() - tiempoInicio;

    const segundosTotales =
        Math.floor(diferencia / 1000);

    const horas =
        Math.floor(segundosTotales / 3600);

    const minutos =
        Math.floor(
            (segundosTotales % 3600) / 60
        );

    const segundos =
        segundosTotales % 60;

    tiempoTranscurrido.textContent =
        `${String(horas).padStart(2, "0")}:` +
        `${String(minutos).padStart(2, "0")}:` +
        `${String(segundos).padStart(2, "0")};`
}

function esperar(milisegundos) {
    return new Promise(resolve => {
        setTimeout(resolve, milisegundos);
    });
}

function cambiarEstadoSimulacion(activa) {
    simulacionActiva = activa;

    btnIndividual.disabled = activa;
    btnSimular.disabled = activa;
    btnHoraPico.disabled = activa;
    btnDetener.disabled = !activa;

    zonaInput.disabled = activa;
    cantidadInput.disabled = activa;
    intensidadInput.disabled = activa;
    intervaloInput.disabled = activa;
}

function obtenerIntervalo() {
    const intensidad =
        intensidadInput.value;

    if (intensidad === "alta") {
        return 50;
    }

    if (intensidad === "media") {
        return 250;
    }

    return Math.max(
        0,
        Number(intervaloInput.value) || 500
    );
}

async function iniciarSimulacion(
    cantidad,
    intervalo
) {
    if (simulacionActiva) {
        return;
    }

    if (
        !Number.isInteger(cantidad) ||
        cantidad < 1 ||
        cantidad > 5000
    ) {
        alert(
            "La cantidad debe estar entre 1 y 5000."
        );

        return;
    }

    enviados = 0;
    fallidos = 0;
    totalSimulacion = cantidad;
    detenerSolicitado = false;
    tiempoInicio = Date.now();

    actualizarPanel();
    actualizarTiempo();

    estadoEnvio.textContent =
        "Simulación en ejecución";

    cambiarEstadoSimulacion(true);

    temporizador =
        setInterval(actualizarTiempo, 1000);

    agregarLog(
        `Simulación iniciada con ${cantidad} pedidos.`,
        "info"
    );

    for (let i = 0; i < cantidad; i++) {
        if (detenerSolicitado) {
            agregarLog(
                "La simulación fue detenida por el usuario.",
                "info"
            );

            break;
        }

        const pedido = generarPedido();

        try {
            await enviarPedido(pedido);

            enviados++;

            agregarLog(
                `Pedido ${pedido.pedidoId} enviado. ` +
                `Zona: ${pedido.zona}. ` +
                `Total: L ${pedido.total}.`,
                "success"
            );
        } catch (error) {
            fallidos++;

            agregarLog(
                `Error enviando pedido ` +
                `${pedido.pedidoId}: ${error.message}`,
                "error"
            );
        }

        actualizarPanel();

        if (intervalo > 0) {
            await esperar(intervalo);
        }
    }

    clearInterval(temporizador);
    actualizarTiempo();
    actualizarPanel();

    cambiarEstadoSimulacion(false);

    if (detenerSolicitado) {
        estadoEnvio.textContent =
            "Simulación detenida";
    } else if (fallidos > 0) {
        estadoEnvio.textContent =
            "Finalizada con errores";
    } else {
        estadoEnvio.textContent =
            "Simulación completada";
    }

    agregarLog(
        `Proceso terminado. Enviados: ${enviados}. ` +
        `Fallidos: ${fallidos}.`,
        fallidos > 0 ? "error" : "success"
    );
}

async function enviarIndividual() {
    btnIndividual.disabled = true;

    estadoEnvio.textContent =
        "Enviando pedido individual";

    const pedido = generarPedido();

    try {
        await enviarPedido(pedido);

        enviados++;
        totalSimulacion =
            Math.max(totalSimulacion, enviados);

        agregarLog(
            `Pedido individual ${pedido.pedidoId} ` +
            `enviado correctamente.`,
            "success"
        );

        estadoEnvio.textContent =
            "Pedido individual enviado";
    } catch (error) {
        fallidos++;

        agregarLog(
            `Error enviando pedido individual: ` +
            error.message,
            "error"
        );

        estadoEnvio.textContent =
            "Error en pedido individual";
    } finally {
        actualizarPanel();
        btnIndividual.disabled = false;
    }
}

async function verificarProducer() {
    try {
        const respuesta =
            await fetch(PRODUCER_URL, {
                method: "OPTIONS"
            });

        producerPoint.style.background =
            "#62d57b";

        producerStatusText.textContent =
            "Producer disponible";
    } catch (error) {
        producerPoint.style.background =
            "#ff6b6b";

        producerStatusText.textContent =
            "Producer no disponible";

        agregarLog(
            "No se pudo verificar Delivery.Producer.",
            "error"
        );
    }
}

btnIndividual.addEventListener(
    "click",
    enviarIndividual
);

btnSimular.addEventListener(
    "click",
    () => {
        const cantidad =
            Number(cantidadInput.value);

        const intervalo =
            obtenerIntervalo();

        iniciarSimulacion(
            cantidad,
            intervalo
        );
    }
);

btnHoraPico.addEventListener(
    "click",
    () => {
        zonaInput.value = "Aleatoria";
        cantidadInput.value = "100";
        intensidadInput.value = "alta";
        intervaloInput.value = "50";

        iniciarSimulacion(
            100,
            50
        );
    }
);

btnDetener.addEventListener(
    "click",
    () => {
        detenerSolicitado = true;
        estadoEnvio.textContent =
            "Deteniendo simulación...";
        btnDetener.disabled = true;
    }
);

btnLimpiarLog.addEventListener(
    "click",
    () => {
        eventLog.innerHTML = `
            <div class="log-line log-info">
                Registro limpiado.
            </div>
        `;
    }
);

verificarProducer();