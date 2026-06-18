<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
require_once __DIR__ . '/../mpdf/vendor/autoload.php';

/* ===========================
   LISTA DE POSTULANTES
=========================== */
$pacientes = [
    [
        "id"     => "03 - ACADEPOL - 2025",
        "nombre" => "ADUL ADEL GAYOZO RAMOS",
        "ci"     => "5242880",
        "edad"   => 21,
        "tipi"   => "'O' Rh Positivo",
        "sexo"   => "MASCULINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "04 - ACADEPOL - 2025",
        "tipi"   => "'O' Rh Positivo",
        "nombre" => "ARACELY NOEMI FLEITAS CUEVAS",
        "ci"     => "5624380",
        "edad"   => 22,
        "sexo"   => "FEMENINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "05 - ACADEPOL - 2025",
        "tipi"   => "'O' Rh Positivo",
        "nombre" => "JHON ALEXANDER REYES CORREA",
        "ci"     => "6255971",
        "edad"   => 22,
        "sexo"   => "MASCULINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "06 - ACADEPOL - 2025",
        "nombre" => "TAMARA LUJAN SALDIVAR NOGUERA",
        "ci"     => "5415856",
        "edad"   => 20,
        "sexo"   => "FEMENINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "07 - ACADEPOL - 2025",
        "tipi"   => "'O' Rh Positivo",
        "nombre" => "STHEFANIE NATACSHA GARCIA LOPEZ",
        "ci"     => "6662370",
        "edad"   => 19,
        "sexo"   => "FEMENINO",
        "fecha"  => "11/12/2025"
    ]
];


/* ===========================
   CONFIG MPDF
=========================== */
$mpdf = new \Mpdf\Mpdf([
    'mode'          => 'utf-8',
    'format'        => 'legal',
    'margin_top'    => 20,
    'margin_bottom' => 20,
    'margin_left'   => 20,
    'margin_right'  => 20
]);

/* ===========================
   CSS — MÁS CHICO
=========================== */
$css = '
<style>

    body { font-family: Arial; }

    .card {
        width: 45%;
        margin-left: 0;
        padding: 10px;
        border: 2px solid #000;
    }

    .header-table {
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
    }

    .header-logo {
        width: 55px;
    }

    .header-title {
        font-size: 12px;
        font-weight: bold;
        text-align: left;
        padding-top: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }

    td.label {
        width: 28%;
        font-weight: bold;
        padding: 3px 0;
        white-space: nowrap;
    }

    td.valor {
        border-bottom: 1px solid #000;
        padding: 3px 0;
        font-size: 11px;
    }

</style>
';


$mpdf->WriteHTML($css);

/* ===========================
   GENERAR TARJETA
=========================== */

foreach ($pacientes as $p) {

    $html = '
<div class="card">

    <table class="header-table">
        <tr>
            <td class="label">
                <img src="'.__DIR__.'/../images/img_logo.png" class="header-logo">
            </td>
            <td class="header-title">
                GRUPO SANGUÍNEO Y FACTOR Rh
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td class="label">Nombre:</td>
            <td class="valor">'.htmlspecialchars($p["nombre"]).'</td>
        </tr>

        <tr>
            <td class="label">Cédula:</td>
            <td class="valor">'.htmlspecialchars($p["ci"]).'</td>
        </tr>

        <tr>
            <td class="label">Pertenece a:</td>
            <td class="valor">'.htmlspecialchars($p["tipi"]).'</td>
        </tr>

        <tr>
            <td class="label">Fecha:</td>
            <td class="valor">'.$p["fecha"].'</td>
        </tr>
    </table>

</div>
';


    $mpdf->WriteHTML($html);
    $mpdf->AddPage();
}

/* ===========================
   SALIDA
=========================== */
$mpdf->Output("tarjetas_postulantes.pdf", "I");
