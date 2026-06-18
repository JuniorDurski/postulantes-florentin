<?php include "menu.php"; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Estudios</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.css">
</head>

<body>

<div class="container mt-4">

    <div id="mensaje"></div>

    <div id="toolbarEstudios">
        <button class="btn btn-primary" id="btnNuevoEstudio">Nuevo Estudio</button>
    </div>

    <table
        id="tablaEstudios"
        class="table"
        data-toggle="table"
        data-pagination="true"
        data-search="true"
        data-toolbar="#toolbarEstudios">
    </table>

</div>

<!-- =====================================================
                    MODAL ESTUDIO
======================================================-->
<div class="modal fade" id="modalEstudio">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formEstudio">
                <div class="modal-header">
                    <h5 class="modal-title">Estudio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id_estudio" name="id_estudio">

                    <div class="mb-3">
                        <label>Estudio</label>
                        <input type="text" class="form-control" id="estudio" name="estudio" required>
                    </div>

                    <div class="mb-3">
                        <label>Fecha</label>
                        <input type="date" class="form-control" id="fecha_est" name="fecha" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- =====================================================
                    MODAL SECCIONES
======================================================-->
<div class="modal fade" id="modalSecciones">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="secc_id_estudio">

            <div class="modal-header">
                <h5 class="modal-title">Secciones del Estudio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="toolbarSecciones">
                    <button class="btn btn-primary" id="btnNuevaSeccion">Nueva Sección</button>
                </div>

                <table
                    id="tablaSecciones"
                    class="table"
                    data-toggle="table"
                    data-pagination="true"
                    data-toolbar="#toolbarSecciones">
                </table>

            </div>

        </div>
    </div>
</div>

<!-- =====================================================
            MODAL CREAR/EDITAR SECCIÓN
======================================================-->
<div class="modal fade" id="modalSeccionForm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formSeccion">
                <div class="modal-header">
                    <h5 class="modal-title">Sección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id_seccion" name="id_seccion">
                    <input type="hidden" id="id_estudio_fk" name="id_estudio">

                    <div class="mb-3">
                        <label>Sección</label>
                        <input type="text" class="form-control" id="seccion" name="seccion" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- =====================================================
                        MODAL EXÁMENES
======================================================-->
<div class="modal fade" id="modalExamenes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="id_seccion_examen">

            <div class="modal-header">
                <h5 class="modal-title">Exámenes</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="toolbarExamenes">
                    <button class="btn btn-primary" id="btnNuevoExamen">Nuevo Examen</button>
                </div>

                <table
                    id="tablaExamenes"
                    class="table"
                    data-toggle="table"
                    data-pagination="true"
                    data-toolbar="#toolbarExamenes">
                </table>

            </div>

        </div>
    </div>
</div>

<!-- =====================================================
                FORMULARIO CREAR/EDITAR EXAMEN
======================================================-->
<div class="modal fade" id="modalExamenForm">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formExamen">
                <div class="modal-header">
                    <h5 class="modal-title">Examen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id_examen" name="id_examen">
                    <input type="hidden" id="id_seccion_fk" name="id_seccion">

                    <div class="mb-3">
                        <label>Examen</label>
                        <input type="text" class="form-control" id="examen" name="examen" required>
                    </div>

                    <div class="mb-3">
                        <label>Unidad</label>
                        <input type="text" class="form-control" id="unidad" name="unidad">
                    </div>

                    <div class="mb-3">
                        <label>Valor Referencial</label>
                        <textarea class="form-control" id="referencial" name="referencial"> 
                        </textarea>
                    </div>
                    <div class="mb-3">
                        <label>Orden</label>
                        <input type="text" class="form-control" id="orden" name="orden" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- SCRIPTS ========================================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>

<script>


/* =====================================================
                    TABLA ESTUDIOS
======================================================*/
function cargarEstudios() {
    $('#tablaEstudios').bootstrapTable('destroy').bootstrapTable({
        url: 'backend/estudiosDAO.php?q=listar',
        columns: [
            { field: 'id_estudio', title: 'ID', align: 'center' },
            { field: 'estudio', title: 'Estudio' },
            { field: 'fecha', title: 'Fecha', align: 'center' },
            {
                field: 'acciones',
                title: 'Acciones',
                align: 'center',
                formatter() {
                    return `
                        <button class="btn btn-warning btn-sm editar">Editar</button>
                        <button class="btn btn-danger btn-sm eliminar">Eliminar</button>
                        <button class="btn btn-info btn-sm secciones">Secciones</button>
                    `;
                },
                events: {
                    "click .editar": (e, v, row) => {
                        $("#id_estudio").val(row.id_estudio);
                        $("#estudio").val(row.estudio);
                        $("#fecha_est").val(row.fecha);
                        $("#modalEstudio").modal("show");
                    },
                    "click .eliminar": (e, v, row) => {
                        if (!confirm("¿Eliminar estudio?")) return;
                        $.post("backend/estudiosDAO.php?q=eliminar", { id: row.id_estudio }, (r) => cargarEstudios(), "json");
                    },
                    "click .secciones": (e, v, row) => {
                        $("#secc_id_estudio").val(row.id_estudio);
                        cargarSecciones(row.id_estudio);
                        $("#modalSecciones").modal("show");
                    }
                }
            }
        ]
    });
}

cargarEstudios();

/* =====================================================
                    NUEVO ESTUDIO
======================================================*/
$("#btnNuevoEstudio").click(() => {
    $("#formEstudio")[0].reset();
    $("#id_estudio").val("");
    $("#modalEstudio").modal("show");
});

/* =====================================================
                    GUARDAR ESTUDIO
======================================================*/
$("#formEstudio").submit(function(e){
    e.preventDefault();
    $.post("backend/estudiosDAO.php?q=guardar", $(this).serialize(), function(r){
        $("#modalEstudio").modal("hide");
        cargarEstudios();
    }, "json");
});


/* =====================================================
                    TABLA SECCIONES
======================================================*/
function cargarSecciones(id_estudio) {
    $("#tablaSecciones").bootstrapTable('destroy').bootstrapTable({
        url: "backend/seccionesDAO.php?q=listar&id_estudio=" + id_estudio,
        columns: [
            { field: 'id_seccion', title: 'ID', align: 'center' },
            { field: 'seccion', title: 'Sección' },
            {
                field: 'acciones',
                title: 'Acciones',
                formatter() {
                    return `
                        <button class="btn btn-warning btn-sm editar">Editar</button>
                        <button class="btn btn-danger btn-sm eliminar">Eliminar</button>
                        <button class="btn btn-info btn-sm examenes">Exámenes</button>
                    `;
                },
                events: {
                    "click .editar": (e, v, row) => {
                        $("#id_seccion").val(row.id_seccion);
                        $("#id_estudio_fk").val($("#secc_id_estudio").val());
                        $("#seccion").val(row.seccion);
                        $("#modalSeccionForm").modal("show");
                    },
                    "click .eliminar": (e, v, row) => {
                        if (!confirm("¿Eliminar sección?")) return;
                        $.post("backend/seccionesDAO.php?q=eliminar", { id: row.id_seccion }, (r) => {
                            cargarSecciones($("#secc_id_estudio").val());
                        }, "json");
                    },
                    "click .examenes": (e, v, row) => {
                        $("#id_seccion_examen").val(row.id_seccion);
                        cargarExamenes(row.id_seccion);
                        $("#modalExamenes").modal("show");
                    }
                }
            }
        ]
    });
}

$("#btnNuevaSeccion").click(() => {
    $("#formSeccion")[0].reset();
    $("#id_seccion").val("");
    $("#id_estudio_fk").val($("#secc_id_estudio").val());
    $("#modalSeccionForm").modal("show");
});

$("#formSeccion").submit(function(e){
    e.preventDefault();
    $.post("backend/seccionesDAO.php?q=guardar", $(this).serialize(), function(r){
        $("#modalSeccionForm").modal("hide");
        cargarSecciones($("#secc_id_estudio").val());
    }, "json");
});


/* =====================================================
                    TABLA EXAMENES
======================================================*/
function cargarExamenes(id_seccion) {
    $("#tablaExamenes").bootstrapTable('destroy').bootstrapTable({
        url: "backend/examenesDAO.php?q=listar&id_seccion=" + id_seccion,
        columns: [
            { field: 'id_examen', title: 'ID', align: 'center' },
            { field: 'examen', title: 'Examen' },
            { field: 'unidad', title: 'Unidad' },
            { field: 'referencial', title: 'Referencial' },
            { field: 'orden', title: 'Orden' },
            {
                field: 'acciones',
                title: 'Acciones',
                formatter() {
                    return `
                        <button class="btn btn-warning btn-sm editar">Editar</button>
                        <button class="btn btn-danger btn-sm eliminar">Eliminar</button>
                    `;
                },
                events: {
                    "click .editar": (e, v, row) => {
                        $("#id_examen").val(row.id_examen);
                        $("#id_seccion_fk").val($("#id_seccion_examen").val());
                        $("#examen").val(row.examen);
                        $("#unidad").val(row.unidad);
                        $("#referencial").val(row.referencial);
                        $("#orden").val(row.orden);
                        $("#modalExamenForm").modal("show");
                    },
                    "click .eliminar": (e, v, row) => {
                        if (!confirm("¿Eliminar examen?")) return;
                        $.post("backend/examenesDAO.php?q=eliminar", { id: row.id_examen }, (r) => {
                            cargarExamenes($("#id_seccion_examen").val());
                        });
                    }
                }
            }
        ]
    });
}

$("#btnNuevoExamen").click(() => {
    $("#formExamen")[0].reset();
    $("#id_examen").val("");
    $("#id_seccion_fk").val($("#id_seccion_examen").val());
    $("#modalExamenForm").modal("show");
});

$("#formExamen").submit(function(e){
    e.preventDefault();
    $.post("backend/examenesDAO.php?q=guardar", $(this).serialize(), function(){
        $("#modalExamenForm").modal("hide");
        cargarExamenes($("#id_seccion_examen").val());
    });
});

</script>

</body>
</html>
