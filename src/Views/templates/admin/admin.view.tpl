<section class="admin-page">
  <h1>Panel de administracion</h1>

  <div class="kpi-grid">
    <div class="kpi-card">
      <div class="kpi-label">Total pedidos</div>
      <div class="kpi-value">{{totalPedidos}}</div>
    </div>
    <div class="kpi-card warn">
      <div class="kpi-label">Pendientes</div>
      <div class="kpi-value">{{pendientes}}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">En proceso</div>
      <div class="kpi-value">{{enProceso}}</div>
    </div>
    <div class="kpi-card ok">
      <div class="kpi-label">Listos</div>
      <div class="kpi-value">{{listos}}</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Clientes registrados</div>
      <div class="kpi-value">{{totalClientes}}</div>
    </div>
    <div class="kpi-card warn">
      <div class="kpi-label">Platos stock bajo</div>
      <div class="kpi-value">{{stockBajo}}</div>
    </div>
  </div>

  <div class="admin-card-grid">
    <a href="index.php?page=Admin.GestionUsuarios" class="admin-card">
      <i class="fas fa-users"></i>
      <div class="admin-card-title">Gestion de usuarios</div>
      <div class="admin-card-desc">Cambia roles o elimina cuentas del sistema.</div>
    </a>
    <a href="index.php?page=Admin.GestionPlatos" class="admin-card">
      <i class="fas fa-utensils"></i>
      <div class="admin-card-title">Gestion de platos</div>
      <div class="admin-card-desc">Agrega platos, edita precios, stock y disponibilidad del menu.</div>
    </a>
    <a href="index.php?page=Cocina.ConfirmarPedido" class="admin-card">
      <i class="fas fa-clipboard-check"></i>
      <div class="admin-card-title">Confirmar pedidos</div>
      <div class="admin-card-desc">Acepta pedidos pendientes o rechazalos antes de cocina.</div>
    </a>
    <a href="index.php?page=Cocina.Cocina" class="admin-card">
      <i class="fas fa-forward"></i>
      <div class="admin-card-title">Avanzar pedidos</div>
      <div class="admin-card-desc">Mueve pedidos de en proceso a listo y entregado.</div>
    </a>
  </div>
</section>
