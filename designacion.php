<?php include "menu.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Designaciones</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.css">

</head>

<body>

<div class="container mt-4">

    <div id="mensaje"></div>

    <table
        id="tabla_design"
        class="table table-bordered"
        data-toggle="table"
        data-search="true"
        data-pagination="true"
        data-toolbar="#toolbarDesign">
    </table>

    <div id="toolbarDesign">
        <button class="btn btn-primary" id="btnNuevo">
            <i class="bi bi-plus-circle"></i> Nueva Designación
        </button>
    </div>

</div>

<!-- ============================
       MODAL CREAR / EDITAR
============================== -->
<div class="modal fade" id="modalDesign" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formDesign">

        <div class="modal-header">
            <h5 class="modal-title">Designación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <input type="hidden" id="id_designacion" name="id_designacion">

            <div class="mb-3">
                <label>Designación</label>
                <input type="text" class="form-control" id="designacion" name="designacion" required>
            </div>

            <div class="mb-3">
                <label>Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>

      </form>

    </div>
  </div>
</div>

<!-- ==========================================
       MODAL REQUISITOS DE ESTUDIOS
=========================================== -->
<div class="modal fade" id="modalEstudios" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Requisitos de Estudios</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="est_id_designacion">

        <div class="row">
          <div class="col-md-8">
            <select id="selectEstudios" class="form-select"></select>
          </div>

          <div class="col-md-4">
            <button class="btn btn-success w-100" id="btnAgregarEstudio">
              Agregar
            </button>
          </div>
        </div>

        <hr>

        <table class="table table-bordered" id="tablaReq">
          <thead>
            <tr>
              <th>ID</th>
              <th>Estudio</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>



<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>

<script>

// Convierte a mayúsculas
$('#designacion').on('input', function() {
    this.value = this.value.toUpperCase();
});

// ================================
//      CARGAR TABLA
// ================================
function cargarTabla() {
    $('#tabla_design').bootstrapTable('destroy').bootstrapTable({
        url: 'backend/designacionesDAO.php?q=listar',
        method: 'GET',
        columns: [
            { field: 'id_designacion', title: 'ID', align: 'center' },
            { field: 'designacion', title: 'Designación', align: 'left' },
            { field: 'fecha', title: 'Fecha', align: 'center' },
            {
                field: 'acciones',
                title: 'Acciones',
                align: 'center',
                formatter: function () {
                    return `
                        <button class="btn btn-warning btn-sm editar">Editar</button>
                        <button class="btn btn-info btn-sm estudios">Estudios</button>
                        <button class="btn btn-danger btn-sm eliminar">Eliminar</button>
                    `;
                },
                events: {
                    'click .editar': function(e, value, row) {
                        $('#id_designacion').val(row.id_designacion);
                        $('#designacion').val(row.designacion);
                        $('#fecha').val(row.fecha);
                        $('#modalDesign').modal('show');
                    },
                    'click .eliminar': function(e, value, row) {
                        if (!confirm("¿Eliminar esta designación?")) return;

                        $.post('backend/designacionesDAO.php?q=eliminar',
                            { id: row.id_designacion },
                            function(resp) {
                                if (resp.status === 'ok') {
                                    cargarTabla();
                                } else {
                                    alert("Error: " + resp.mensaje);
                                }
                            },
                        'json');

                    },
                    'click .estudios': function(e, value, row) {
                        abrirModalEstudios(row.id_designacion);
                    }
                }
            }
        ]
    });
}

cargarTabla();

// ================================
//     NUEVA DESIGNACIÓN
// ================================
$("#btnNuevo").click(() => {
    $("#formDesign")[0].reset();
    $("#id_designacion").val('');
    $("#modalDesign").modal("show");
});

// ================================
//        GUARDAR (AJAX POST)
// ================================
$("#formDesign").submit(function(e) {
    e.preventDefault();

    let datos = $(this).serialize();

    $.post('backend/designacionesDAO.php?q=guardar', datos, function(resp) {
        if (resp.status === 'ok') {
            $("#modalDesign").modal("hide");
            cargarTabla();
        } else {
            alert("Error: " + resp.mensaje);
        }
    }, 'json');

});

// ======================================================
//   ABRIR MODAL DE ESTUDIOS
// ======================================================
function abrirModalEstudios(idDesign) {
    $("#est_id_designacion").val(idDesign);

    cargarSelectEstudios();
    cargarRequisitos(idDesign);

    $("#modalEstudios").modal("show");
}

// ======================================================
//  CARGAR ESTUDIOS EN SELECT
// ======================================================
function cargarSelectEstudios() {
    $.get("backend/designacionesDAO.php?q=listarEstudios", function(data) {
        let sel = $("#selectEstudios");
        sel.empty();

        data.forEach(e => {
            sel.append(`<option value="${e.id_estudio}">${e.estudio}</option>`);
        });
    }, "json");
}

// ======================================================
//  CARGAR REQUISITOS EXISTENTES
// ======================================================
function cargarRequisitos(idDesign) {
    $.get("backend/designacionesDAO.php?q=listarReq&id="+idDesign, function(data) {
        let tbody = $("#tablaReq tbody");
        tbody.empty();

        data.forEach(r => {
            tbody.append(`
                <tr>
                    <td>${r.id_requisito}</td>
                    <td>${r.estudio}</td>
                    <td>
                        <button class="btn btn-danger btn-sm" onclick="eliminarReq(${r.id_requisito}, ${idDesign})">
                            Eliminar
                        </button>
                    </td>
                </tr>
            `);
        });
    }, "json");
}

// ======================================================
//     AGREGAR REQUISITO
// ======================================================
$("#btnAgregarEstudio").click(function() {
    let idDesign = $("#est_id_designacion").val();
    let idEstudio = $("#selectEstudios").val();

    $.post("backend/designacionesDAO.php?q=agregarReq",
        { id_designacion: idDesign, id_estudio: idEstudio },
        function(resp) {
            if (resp.status === "ok") {
                cargarRequisitos(idDesign);
            } else {
                alert(resp.mensaje);
            }
        }, "json");
});

// ======================================================
//   ELIMINAR REQUISITO
// ======================================================
function eliminarReq(idReq, idDesign) {
    if (!confirm("¿Quitar este estudio?")) return;

    $.post("backend/designacionesDAO.php?q=eliminarReq",
        { id_requisito: idReq },
        function(resp) {
            if (resp.status === "ok") {
                cargarRequisitos(idDesign);
            } else {
                alert(resp.mensaje);
            }
        }, "json");
}


</script>

</body>
</html>
