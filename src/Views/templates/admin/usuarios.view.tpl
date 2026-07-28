<section class="admin-page">
  <h1>Usuarios registrados</h1>

  {{with mensaje}}
  <div class="admin-alert">{{mensaje}}</div>
  {{endwith mensaje}}

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre</th>
          <th>Email</th>
          <th>Rol actual</th>
          <th>Cambiar rol</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        {{foreach usuarios}}
        <tr>
          <td>{{id}}</td>
          <td>{{nombre}}</td>
          <td>{{email}}</td>
          <td><span class="badge badge-{{rol}}">{{rol}}</span></td>
          <td>
            <form method="POST" action="index.php?page=Admin.GestionUsuarios">
              <input type="hidden" name="accion" value="cambiar_rol">
              <input type="hidden" name="id" value="{{id}}">
              <select name="rol">
                <option value="cliente">cliente</option>
                <option value="cocina">cocina</option>
                <option value="admin">admin</option>
              </select>
              <button type="submit" class="btn-sm">Guardar</button>
            </form>
          </td>
          <td>
            <form method="POST" action="index.php?page=Admin.GestionUsuarios">
              <input type="hidden" name="accion" value="eliminar">
              <input type="hidden" name="id" value="{{id}}">
              <button type="submit" class="btn-sm">Eliminar</button>
            </form>
          </td>
        </tr>
        {{endfor usuarios}}
      </tbody>
    </table>
  </div>

  <a href="index.php?page=Admin.Admin" class="admin-back">← Volver al panel</a>
</section>