<?php include "menu.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Postulantes</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.css">
</head>

<body>

<div class="container mt-4">

    <div id="mensaje"></div>

    <!-- =====================================================
                        TOOLBAR
    ====================================================== -->
    <div id="toolbarPostulantes" class="row g-2">

        <div class="col-auto">
            <button class="btn btn-primary" id="btnNuevoPostulante">Nuevo</button>
        </div>

        <div class="col-auto">
            <select id="filtroDesignacion" class="form-select">
                <option value="">Todas las Designaciones</option>
            </select>
        </div>

        <div class="col-auto">
            <input type="date" id="filtroFecha" class="form-control">
        </div>

        <div class="col-auto">
            <button class="btn btn-success" id="btnBuscar">Buscar</button>
        </div>

    </div>

    <!-- =====================================================
                        TABLA
    ====================================================== -->
    <table
        id="tablaPostulantes"
        class="table"
        data-toggle="table"
        data-pagination="true"
        data-toolbar="#toolbarPostulantes">
    </table>

</div>


<!-- =====================================================
                MODAL CREAR / EDITAR
====================================================== -->
<div class="modal fade" id="modalPostulante">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formPostulante">

                <div class="modal-header">
                    <h5 class="modal-title">Postulante</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id_postulante" name="id_postulante">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>CI</label>
                            <input type="text" class="form-control" name="ci" id="ci" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Edad</label>
                            <input type="number" class="form-control" name="edad" id="edad" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Sexo</label>
                            <select class="form-select" name="sexo" id="sexo" required>
                                <option value="MASCULINO">Masculino</option>
                                <option value="FEMENINO">Femenino</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Designación</label>
                            <select class="form-select" name="id_designacion" id="id_designacion" required></select>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>

            </form>

        </div>
    </div>
</div>


<!-- =====================================================
                MODAL RESULTADOS
====================================================== -->
<div class="modal fade" id="modalResultados">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Resultados</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="contenedorResultados">
                <!-- Aquí va la estructura dinámica -->
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="btnGuardarResultados">Guardar Resultados</button>
            </div>

        </div>
    </div>
</div>



<!-- SCRIPTS ===================================================== -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>

<script>


// ===============================================================
// CARGAR SELECT DESIGNACIONES
// ===============================================================
function cargarDesignacionesSelect() {
    $.get("backend/designacionesDAO.php?q=listar", function (r) {

        $("#filtroDesignacion").html(`<option value="">Todas las Designaciones</option>`);
        $("#id_designacion").html("");

        r.forEach(d => {
            $("#filtroDesignacion").append(`<option value="${d.id_designacion}">${d.designacion}</option>`);
            $("#id_designacion").append(`<option value="${d.id_designacion}">${d.designacion}</option>`);
        });

    }, "json");
}
cargarDesignacionesSelect();


// ===============================================================
// TABLA
// ===============================================================
function cargarTablaPostulantes() {

    const des = $("#filtroDesignacion").val();
    const fec = $("#filtroFecha").val();

    let url = "backend/postulantesDAO.php?q=listar";

    if (des) {
        url += `&id_designacion=${des}`;
    }
    if (fec) {
        url += `&fecha=${fec}`;
    }

    $("#tablaPostulantes").bootstrapTable("destroy").bootstrapTable({
        url: url,
        columns: [
            { field: 'id_postulante', title: 'ID', align: 'center' },
            { field: 'nombre', title: 'Nombre' },
            { field: 'ci', title: 'CI' },
            { field: 'edad', title: 'Edad' },
            { field: 'sexo', title: 'Sexo' },
            { field: 'designacion', title: 'Designación' },
            { field: 'fecha_alta', title: 'Fecha Alta/Edicion' },

            {
                field: 'acciones',
                title: 'Acciones',
                align: 'center',
                formatter() {
                    return `
                        <button class="btn btn-warning btn-sm editar">Editar</button>
                        <button class="btn btn-danger btn-sm eliminar">Eliminar</button>
                        <button class="btn btn-info btn-sm resultados">Resultados</button>
                        <button class="btn btn-secondary btn-sm imprimir">Imprimir</button>
                    `;
                },
                events: {

                    // EDITAR
                    "click .editar": (e, v, row) => {
                        $("#formPostulante")[0].reset();
                        $("#id_postulante").val(row.id_postulante);
                        $("#nombre").val(row.nombre);
                        $("#ci").val(row.ci);
                        $("#edad").val(row.edad);
                        $("#sexo").val(row.sexo);
                        $("#id_designacion").val(row.id_designacion);
                        $("#modalPostulante").modal("show");
                    },

                    // ELIMINAR
                    "click .eliminar": (e, v, row) => {
                        if (!confirm("¿Eliminar postulante?")) return;
                        $.post("backend/postulantesDAO.php?q=eliminar", { id: row.id_postulante }, function () {
                            cargarTablaPostulantes();
                        });
                    },

                    // RESULTADOS
                    "click .resultados": (e, v, row) => {
                        cargarResultados(row.id_postulante, row.id_designacion);
                    },

                    // IMPRIMIR
                    "click .imprimir": (e, v, row) => {
                        window.open(`reportes/informes.pdf?id_postulante=${row.id_postulante}`, "_blank");
                    }
                }
            }
        ]
    });
}
cargarTablaPostulantes();


// ===============================================================
// BUSCAR
// ===============================================================
$("#btnBuscar").click(() => cargarTablaPostulantes());


// ===============================================================
// NUEVO
// ===============================================================
$("#btnNuevoPostulante").click(() => {
    $("#formPostulante")[0].reset();
    $("#id_postulante").val("");
    $("#modalPostulante").modal("show");
});


// ===============================================================
// GUARDAR POSTULANTE
// ===============================================================
$("#formPostulante").submit(function (e) {
    e.preventDefault();
    $.post("backend/postulantesDAO.php?q=guardar", $(this).serialize(), function () {
        $("#modalPostulante").modal("hide");
        cargarTablaPostulantes();
    });
});



// ===============================================================
// CARGAR RESULTADOS (ESTUDIOS → SECCIONES → EXAMENES)
// ===============================================================
let ID_POSTULANTE_ACTUAL = null;

function cargarResultados(id_postulante, id_designacion) {

    ID_POSTULANTE_ACTUAL = id_postulante;

    $.get(`backend/resultadosDAO.php?q=estructura&id_designacion=${id_designacion}&id_postulante=${id_postulante}`, function (r) {

        let html = "";

        r.forEach(est => {

            html += `<h4 class='mt-3 text-primary'>${est.estudio}</h4><hr>`;

            est.secciones.forEach(sec => {

                html += `<h5 class='text-secondary mt-2'>${sec.seccion}</h5>`;

                html += `<table class='table table-sm'>
                            <thead>
                                <tr>
                                    <th>Examen</th>
                                    <th style='width:150px'>Resultado</th>
                                    <th>Unidad</th>
                                    <th>Valor Referencial</th>
                                </tr>
                            </thead>`;

                sec.examenes.forEach(ex => {

                    html += `
                        <tr>
                            <td>${ex.examen}</td>
                            <td>
                                <input class="form-control result-input"
                                       data-id-examen="${ex.id_examen}"
                                       value="${ex.resultado ?? ""}">
                            </td>
                            <td>${ex.unidad}</td>
                            <td>${ex.referencial}</td>
                        </tr>
                    `;
                });

                html += `</table>`;
            });
        });

        $("#contenedorResultados").html(html);
        $("#modalResultados").modal("show");

    }, "json");
}



// ===============================================================
// GUARDAR RESULTADOS
// ===============================================================
$("#btnGuardarResultados").click(function(){

    let datos = [];

    $(".result-input").each(function(){

        datos.push({
            id_examen: $(this).data("id-examen"),
            resultado: $(this).val(),
            id_postulante: ID_POSTULANTE_ACTUAL
        });

    });

    $.post("backend/resultadosDAO.php?q=guardar", { datos: JSON.stringify(datos) }, function(){
        alert("Resultados guardados correctamente");
        $("#modalResultados").modal("hide");
    });

});

</script>


</body>
</html>
