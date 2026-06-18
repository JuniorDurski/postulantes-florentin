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

<!-- SCRIPTS ===================================================== -->

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.22.2/dist/bootstrap-table.min.js"></script>

<script>

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
                        <button class="btn btn-primary btn-sm generarHematologia">G. Hematologia</button>
                        <button class="btn btn-warning btn-sm imprimir">Imprimir</button>
                    `;
                },
                events: {

                    "click .generarHematologia": (e, v, row) => {
                        $.post("backend/resultadosDAO.php?q=generarHematologia", {
                            id_postulante: row.id_postulante
                        }, function (resp) {
                            cargarTablaPostulantes();
                        });

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

</script>


</body>
</html>
