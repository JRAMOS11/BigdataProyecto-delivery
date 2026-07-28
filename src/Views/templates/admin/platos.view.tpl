<section class="admin-page">
  <h1>Menu, stock y disponibilidad</h1>

  {{if mensaje}}
  <div class="admin-alert">{{mensaje}}</div>
  {{endif mensaje}}

  <form method="POST" action="index.php?page=Admin.GestionPlatos" class="admin-editor-form">
    <input type="hidden" name="accion" value="crear">
    <div>
      <label>Nombre</label>
      <input type="text" name="nombre" required>
    </div>
    <div>
      <label>Descripcion</label>
      <input type="text" name="descripcion">
    </div>
    <div>
      <label>Precio</label>
      <input type="number" name="precio" min="0" step="0.01" required>
    </div>
    <div>
      <label>Stock</label>
      <input type="number" name="stock" min="0" required>
    </div>
    <div>
      <label>Disponible</label>
      <select name="disponible">
        <option value="1">Si</option>
        <option value="0">No</option>
      </select>
    </div>
    <button type="submit">Agregar plato</button>
  </form>

  <div class="admin-table-wrap">
    <table class="admin-table admin-menu-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Descripcion</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>Disponible</th>
          <th>Accion</th>
        </tr>
      </thead>
      <tbody>
        {{foreach platos}}
        <tr>
          <td>{{id}}</td>
          <td colspan="6">
            <form method="POST" action="index.php?page=Admin.GestionPlatos" class="admin-row-form">
              <input type="hidden" name="accion" value="editar">
              <input type="hidden" name="id" value="{{id}}">
              <input type="text" name="nombre" value="{{nombre}}" required>
              <input type="text" name="descripcion" value="{{descripcion}}">
              <input type="number" name="precio" value="{{precio}}" min="0" step="0.01" required>
              <input type="number" name="stock" value="{{stock}}" min="0" required>
              <select name="disponible">
                <option value="{{disponible}}">{{disponible_texto}}</option>
                <option value="1">si</option>
                <option value="0">no</option>
              </select>
              <button type="submit" class="btn-sm">Guardar</button>
            </form>
          </td>
        </tr>
        {{endfor platos}}
      </tbody>
    </table>
  </div>

  <div class="admin-actions">
    <a href="index.php?page=Admin.Admin" class="admin-back">Volver al panel</a>
    <a href="index.php?page=Cocina.ConfirmarPedido" class="admin-back">Confirmar pedidos</a>
    <a href="index.php?page=Cocina.Cocina" class="admin-back">Avanzar pedidos</a>
  </div>
</section>
