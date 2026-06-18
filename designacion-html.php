<?php include "menu.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Designaciones</title>
</head>

<body>

<h2>Designaciones</h2>

<div id="mensaje"></div>

<!-- ======================
        TABLA LISTADO
========================= -->
<table border="1" id="tabla_design">
    <thead>
        <tr>
            <th>ID</th>
            <th>Designación</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<br>

<button id="btnNuevo">Nueva Designación</button>

<!-- ======================
   MODAL CREAR / EDITAR
========================= -->
<div id="modalDesign" style="display:none; border:1px solid black; padding:10px;">
    <h3>Designación</h3>

    <form id="formDesign">
        <input type="hidden" id="id_designacion" name="id_designacion">

        <label>Designación</label><br>
        <input type="text" id="designacion" name="designacion" required><br><br>

        <label>Fecha</label><br>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <button type="submit">Guardar</button>
        <button type="button" onclick="cerrarModal('modalDesign')">Cerrar</button>
    </form>
</div>

<!-- ==============================
   MODAL REQUISITOS DE ESTUDIOS
=============================== -->
<div id="modalEstudios" style="display:none; border:1px solid black; padding:10px; margin-top:20px;">
    <h3>Requisitos de Estudios</h3>

    <input type="hidden" id="est_id_designacion">

    <select id="selectEstudios"></select>
    <button id="btnAgregarEstudio">Agregar</button>

    <br><br>

    <table border="1" id="tablaReq">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estudio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <br>
    <button onclick="cerrarModal('modalEstudios')">Cerrar</button>
</div>

<script>

// ==============================
//    MODALES SIN CSS
// ==============================
function abrirModal(id) {
    document.getElementById(id).style.display = "block";
}

function cerrarModal(id) {
    document.getElementById(id).style.display = "none";
}

// ==============================
//  CARGAR TABLA
// ==============================
function cargarTabla() {
    fetch("backend/designacionesDAO.php?q=listar")
        .then(r => r.json())
        .then(data => {
            let tbody = document.querySelector("#tabla_design tbody");
            tbody.innerHTML = "";

            data.forEach(row => {
                let tr = document.createElement("tr");

                tr.innerHTML = `
                    <td>${row.id_designacion}</td>
                    <td>${row.designacion}</td>
                    <td>${row.fecha}</td>
                    <td>
                        <button onclick='editar(${JSON.stringify(row)})'>Editar</button>
                        <button onclick='abrirEstudios(${row.id_designacion})'>Estudios</button>
                        <button onclick='eliminar(${row.id_designacion})'>Eliminar</button>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        });
}

cargarTabla();

// ==============================
//    BOTÓN NUEVO
// ==============================
document.getElementById("btnNuevo").onclick = function () {
    document.getElementById("formDesign").reset();
    document.getElementById("id_designacion").value = "";
    abrirModal("modalDesign");
};

// ==============================
//      EDITAR
// ==============================
function editar(row) {
    document.getElementById("id_designacion").value = row.id_designacion;
    document.getElementById("designacion").value = row.designacion;
    document.getElementById("fecha").value = row.fecha;

    abrirModal("modalDesign");
}

// ==============================
//      GUARDAR
// ==============================
document.getElementById("formDesign").onsubmit = function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("backend/designacionesDAO.php?q=guardar", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.status === "ok") {
            cerrarModal("modalDesign");
            cargarTabla();
        } else {
            alert(resp.mensaje);
        }
    });
};

// ==============================
//      ELIMINAR
// ==============================
function eliminar(id) {
    if (!confirm("¿Eliminar esta designación?")) return;

    let fd = new FormData();
    fd.append("id", id);

    fetch("backend/designacionesDAO.php?q=eliminar", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.status === "ok") {
            cargarTabla();
        } else {
            alert(resp.mensaje);
        }
    });
}

// ==============================
//  MODAL ESTUDIOS
// ==============================
function abrirEstudios(idDesign) {
    document.getElementById("est_id_designacion").value = idDesign;

    cargarSelectEstudios();
    cargarRequisitos(idDesign);

    abrirModal("modalEstudios");
}

// ==============================
// CARGAR SELECT DE ESTUDIOS
// ==============================
function cargarSelectEstudios() {
    fetch("backend/designacionesDAO.php?q=listarEstudios")
    .then(r => r.json())
    .then(data => {
        let sel = document.getElementById("selectEstudios");
        sel.innerHTML = "";

        data.forEach(e => {
            let opt = document.createElement("option");
            opt.value = e.id_estudio;
            opt.textContent = e.estudio;
            sel.appendChild(opt);
        });
    });
}

// ==============================
// CARGAR REQUISITOS EXISTENTES
// ==============================
function cargarRequisitos(idDesign) {
    fetch("backend/designacionesDAO.php?q=listarReq&id=" + idDesign)
    .then(r => r.json())
    .then(data => {
        let tbody = document.querySelector("#tablaReq tbody");
        tbody.innerHTML = "";

        data.forEach(r => {
            let tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${r.id_requisito}</td>
                <td>${r.estudio}</td>
                <td>
                    <button onclick="eliminarReq(${r.id_requisito}, ${idDesign})">Eliminar</button>
                </td>
            `;

            tbody.appendChild(tr);
        });
    });
}

// ==============================
//      AGREGAR REQUISITO
// ==============================
document.getElementById("btnAgregarEstudio").onclick = function () {
    let idDesign = document.getElementById("est_id_designacion").value;
    let idEstudio = document.getElementById("selectEstudios").value;

    let fd = new FormData();
    fd.append("id_designacion", idDesign);
    fd.append("id_estudio", idEstudio);

    fetch("backend/designacionesDAO.php?q=agregarReq", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.status === "ok") {
            cargarRequisitos(idDesign);
        } else {
            alert(resp.mensaje);
        }
    });
};

// ==============================
//      ELIMINAR REQUISITO
// ==============================
function eliminarReq(idReq, idDesign) {
    if (!confirm("¿Quitar este estudio?")) return;

    let fd = new FormData();
    fd.append("id_requisito", idReq);

    fetch("backend/designacionesDAO.php?q=eliminarReq", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.status === "ok") {
            cargarRequisitos(idDesign);
        } else {
            alert(resp.mensaje);
        }
    });
}

</script>

</body>
</html>
