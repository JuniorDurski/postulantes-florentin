<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
require_once __DIR__ . '/../mpdf/vendor/autoload.php';


/* ===========================
   CABECERA DEL PDF
=========================== */
function headerPaciente($p) {
return '
<table width="100%" style="border-bottom:2px solid #000; padding:0px 0px;">
    <tr>
        <td width="25%" style="text-align:left;">
            <img src="'.__DIR__.'/../images/img_logo.png" width="130">
        </td>

        <td width="50%" style="text-align:center; font-weight:bold; font-size:16px;">
            <br>
            CENTRO DE ANÁLISIS CLÍNICOS FLORENTIN<br>
            <span style="font-size:12px;">Dra. Ninfa Raquel Florentin Baez</span><br>
            <span style="font-size:11px;">Bioquímica R. P. 2.315</span>
        </td>

        <td width="25%" style="text-align:right;">
            <img src="'.__DIR__.'/../images/img_qr.png" width="120">
        </td>
    </tr>
</table>
<table class="datos" width="100%" style="border-bottom:2px solid #000;">
    <tr>
        <td colspan="3"><strong>Apellidos y Nombres:</strong> '.$p["nombre"].'</td>
        <td><strong>Codigo:</strong> '.$p["id"].'</td>
    </tr>
    <tr>
        <td><strong>C.I.:</strong> '.$p["ci"].'</td>
        <td><strong>Edad:</strong> '.$p["edad"].'</td>
        <td><strong>Sexo:</strong> '.$p["sexo"].'</td>
        <td><strong>Fecha:</strong> '.$p["fecha"].'</td>
    </tr>
</table>

<table class="tabla" >
    <tr style="border-bottom: 2px solid #000;">
        <th class="section-title col-examen">
            EXAMENES
        </th>
        <th class="section-title col-resultado">
            RESULTADOS
        </th>
        <th class="section-title col-unidad">
            UNIDAD
        </th>
        <th class="section-title col-referencia">
            VALORES REFERENCIALES
        </th>
    </tr>
</table>';
}

/* ===========================
   PIE DE PÁGINA DEL PDF
=========================== */
$footer = '

<table width="100%" style="border-top:2px solid #000; padding-top:8px; font-size:12px; line-height:1.3;">
    <tr>
        <td>
            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
        </td>
        <td width="70%" style="text-align:left; font-size:15px; vertical-align:top;">
            <div>
                <br>
                Villa Elisa - Centro Von Poleski, calle 7, calle Toledo.<br>
                Guayayvi - Centro Barrio Santa Rosa.<br>
                Celular: +595 985 29 60 89 &nbsp; | &nbsp; Correo: laboratorioflorentin@gmail.com<br>
                <span style="font-weight:bold;">HABILITADO POR MSPYBS y LCSP, DRHC 145/2018</span><br>
            </div>
        </td>

        <td width="30%" style="text-align:right; vertical-align:top;">
            <img src="'.__DIR__.'/../images/img_firma.jpg" style="width:300px;">
        </td>
    </tr>
</table>
<br><br><br>
';


/* ===========================
   LISTA DE PACIENTES
=========================== */
$pacientes = [
    [
        "id"     => "03 - ACADEPOL - 2025",
        "nombre" => "ADUL ADEL GAYOZO RAMOS",
        "ci"     => "5242880",
        "edad"   => 21,
        "sexo"   => "MASCULINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "04 - ACADEPOL - 2025",
        "nombre" => "ARACELY NOEMI FLEITAS CUEVAS",
        "ci"     => "5624380",
        "edad"   => 22,
        "sexo"   => "FEMENINO",
        "fecha"  => "11/12/2025"
    ],
    [
        "id"     => "05 - ACADEPOL - 2025",
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
        "nombre" => "STHEFANIE NATACSHA GARCIA LOPEZ",
        "ci"     => "6662370",
        "edad"   => 19,
        "sexo"   => "FEMENINO",
        "fecha"  => "11/12/2025"
    ]
];


/* ===========================
   CONFIGURAR MPDF
=========================== */
$mpdf = new \Mpdf\Mpdf([
    'mode'          => 'utf-8',
    'format'        => 'legal',
    'margin_top'    => 63,
    'margin_bottom' => 68,
    'margin_left'   => 10,
    'margin_right'  => 10
]);

$mpdf->SetHTMLFooter($footer);


/* ===========================
   GENERAR UNA PÁGINA POR PACIENTE
=========================== */

$css =' 
<style>
    body { font-family: Arial; font-size: 14px; }

    .tabla { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 0px; 
        table-layout: fixed; /* ← CLAVE */
    }

    .tabla th, .tabla td {
        padding: 3px 4px;
        word-wrap: break-word;     /* ← PERMITE DIVIDIR TEXTO */
        overflow-wrap: break-word; /* ← AÚN MÁS SEGURO */
        word-break: break-all;     /* ← OBLIGA A ROMPER PALABRAS LARGAS */
    }

    /* ====== TAMAÑOS FIJOS DE LAS 4 COLUMNAS ====== */
        .col-examen { 
            width: 30%; 
            vertical-align: top !important; 
        }
        .col-resultado { 
            width: 30%; 
            text-align: left; 
            vertical-align: top !important; 
        }
        .col-unidad { 
            width: 10%; 
            text-align: left; 
            vertical-align: top !important; 
        }
        .col-referencia { 
            width: 35%; 
            text-align: left; 
            vertical-align: top !important; 
        }

    .section-title {
        text-align: left;
        font-weight: bold;
        border-bottom: 2px solid #000;
    }

    .examen-seccion { 
        font-weight: bold; 
        font-size: 18px;
    }

    .titulo-seccion { 
        font-weight: bold; 
        margin-top: 12px; 
        text-align: left;
    }
</style>';


foreach ($pacientes as $index => $p) {
    /* ===========================
       CALCULO DE RESULTADOS
    =========================== */
    /* HEMATOLOGIA */
        $hematocrito    = valorHematocrito();
        $hemoglobina    = round( $hematocrito / 3.3, 2 );
        $rojos          = round( $hemoglobina * 0.3, 2 );
        $vcm            = valorVCM();
        $hcm            = valorHCM();
        $chcm           = valorCHCM();

        $blanco         = valorBlancos();

        $vec    = obtenerValoresAleatorios();
        $seg    = $vec[1];
        $linfo  = $vec[0];
        $mono   = $vec[2];

        $plaqueta = $plaqueta_formateada = number_format(valorPlaqueta(), 0, ',', '.');

        $vec    = obtenerHorasAleatorias();
        $hora1  = $vec[0];
        $hora2  = $vec[1];

    /* QUIMICA SANGUINEA */
        $glu    = valorGlu();  
        $urea   = valorUrea();  
        $crea   = valorCre();  
        $got    = valorGOT();  
        $gpt    = valorGPT();  
        $fa     = valorFA();  
        
        $col    = valorCol();
        $tri    = valorTri();  
        $hdl    = valorHDL();  
        $vldl   = $tri/5;
        $ldl    = $col - $hdl - $vldl;

        $vec        = obtenerBillisAleatoria();
        $bilTot     = $vec[0];
        $bilDir     = $vec[1];
        $bilInd     = $bilTot - $bilDir;

    /* CITOQUÍMICO URINARIO */
        $perfil = perfilOrinaAleatorio();

        $color      = $perfil["color"];
        $aspecto    = $perfil["aspecto"];
        $densidad   = $perfil["densidad"];
        $ph         = $perfil["ph"];
        $reac       = $perfil["reac"];

        $epi        = $perfil["epi"];
        $leu        = $perfil["leu"];
        $hema       = $perfil["hema"];

    /* ===========================
       EXÁMENES APLICADOS A CADA PACIENTE
    =========================== */
    $examenes = [
        "HEMATOLOGIA" => [
            "SERIE ROJA" => [
                ["Recuento de Glóbulos Rojos", 
                                $rojos, "mill/mm3", "Hombre:(4.5 - 6.0 ) Mujer:( 4.2 - 5.4 )"],
                ["Hemoglobina", $hemoglobina, "g/dL", "Hombre: ( 14 - 18 ) Mujer: ( 13 - 15 )"],
                ["Hematocrito", $hematocrito, "%", "Hombre: ( 42 - 54 ) Mujer: ( 38 - 46 )"],
                ["VCM",         $vcm, "fL", "(79 - 93.3)"],
                ["HCM",         $hcm, "pg", "(26.7 - 31.9)"],
                ["CHCM",        $chcm, "g/dL", "(30,0 - 35,0)"]
            ],
            "SERIE BLANCA" => [
                ["Recuento de Glóbulos Blancos", $blanco, "/uL", "( 4.000 - 10.000 )"],
                ["Linfoblasto",     "0", "", ""],
                ["Mieloblasto",     "0", "", ""],
                ["Promielocito",    "0", "", ""],
                ["Mielocitos",      "0", "", "K/uL"],
                ["Metamielocitos",  "0", "", "K/uL"],
                ["Cayados",         "0", "", "K/uL"],
                ["Segmentados",     $seg, "%", "( 50 - 70)"],
                ["Linfocitos",      $linfo, "%", "( 20 - 40 )"],
                ["Monocitos",       $mono, "%", "( 0 - 10 )"],
                ["Eosinófilos",     "0", "%", "( 0 - 4 )"],
                ["Basófilos",       "0", "%", "( 0 - 2 )"]
            ],
            "SERIE PLAQUETARIA" => [
                ["Recuento de Plaquetas", $plaqueta, "uL", "150.000 - 450.000"],
            ],
            "VELOCIDAD DE SEDIMENTACIÓN GLOBULAR (V.S.G)" => [
                [ "1era Hora",    $hora1, "mm3/hora", "HASTA 12"],
                [ "2da Hora",     $hora2, "mm3/hora", " HASTA 20"]
            ]
        ],
        "QUIMICA SANGUINEA" => [
            "" => [
                ["GLUCOSA",             $glu, "mg/dl", "70 - 110 <br>Prediabetes: 100 - 125 <br>ADA 2020"],
                ["UREA",                $urea, "mg/dl", "10 - 50"],
                ["CREATININA",          $crea, "mg/dl", "0,8 - 1,4"],
                ["GOT (AST) CINETICO",  $got, "U/L", "H: hasta 42 <br>M: hasta 32<br>"],
                ["GPT (ALT) CINETICO",  $gpt, "U/L", "H: hasta 42 <br>M: hasta 32<br>"],
                ["FOSFATASA ALCALINA",  $fa, "U/L", "Adultos: 65 - 300 Niños: hasta 645"]
            ],
            "PERFIL LIPIDICO" => [
                ["COLESTEROL",          $col, "mg/dl", "Deseable: menor a 190, <br>riesgo medio: 190 -239, <br>riesgo alto: > 239"],
                ["TRIGLICERIDOS",       $tri, "mg/dl", "Sospechoso: sobre 150. <br>Elevado: sobre 200"],
                ["COLESTEROL HDL",      $hdl, "mg/dl", "35 - 65"],
                ["COLESTEROL LDL",      $ldl, "mg/dl", "Favorable menor de 150"],
                ["COLESTEROL VLDL",     $vldl, "mg/dl", "5 - 40"]
            ],
            "BILIRRUBINA" => [
                ["BILIRRUBINA TOTAL",       $bilTot, "mg/dl", "Adultos: hasta 1.1 <br>Recién nacido, hasta 5 <br>5 días, hasta 12 <br>1 mes, hasta 1.5"],
                ["BILIRRUBINA DIRECTA",     $bilDir, "mg/dl", "Hasta 0,25"],
                ["BILIRRUBINA INDIRECTA",   $bilInd, "mg/dl", "Hasta 0,85"]
            ],
            "SEROLOGIA" => [
                ["B-HCG (gonadotropina corionica humana)",   "NEGATIVO", "", "SANGRE - CUALITATIVA <br>METODO: INMUNOCROTOGRAFIA"]
            ]
        ],
        "CITOQUÍMICO URINARIO" => [
            "" => [
                ["COLOR",       $color, "", ""],
                ["ASPECTO",     $aspecto, "", ""],
                ["DENSIDAD",    $densidad, "", ""],
                ["PH",          $ph, "", ""],
                ["REACCION",    $reac, "", ""],
                ["SEDIMENTO MICROSCÓPICO",  "REGULAR", "", ""],
                ["ESTERASA LEUCOCITARIA",   "Negativo", "", ""],
                ["HEMATIES",                "-", "", ""],
                ["SANGRE (HEMOGLOBINA)",    "-", "", ""],
                ["NITRITOS",                "Negativo", "", ""],
                ["PROTEINAS",               "No Detectable", "", ""],
                ["GLUCOSA",                 "No Detectable", "", ""],
                ["CUERPOS CETONICOS",       "No Detectable", "", ""],
                ["BILIRRUBINA",             "No Detectable", "", ""],
                ["UROBILINOGENO",           "Normal", "", ""],
                ["HEMOGLOBINA",             "No Detectable", "", ""]
            ],
            "EXAMEN MICROSCÓPICO DEL SEDIMENTO" => [
                ["CELULAS EPITELIALES PLANAS",      $epi, "", ""],
                ["LEUCOCITOS",                      $leu, "", ""],
                ["HEMATIES",                        $hema, "", ""],
                ["CILINDROS HIALINOS",              "-", "", ""],
                ["BACTERIAS",                       "( - )", "", ""],
                ["CILINDRO HIALINO GRANULOSO",      "-", "", ""],
                ["CELULAS HEPITELIALES REDONDAS",   "-", "", ""],
                ["CILINDROS GRANULOSOS",            "Negativo", "", ""],
                ["MUCUS",                           "( - )", "", ""],
                ["TRICHOMONAS",                     "( - )", "", ""],
                ["PIOCITOS",                        "Negativo", "", ""]
            ],
            "HECES SIMPLES" => [
                ["VERMES",          "NO SE OBSERVARON FORMAS PARASITARIAS EN LA MUESTRA ANALIZADA", 
                    "", ""],
                ["PROTOZOARIOS",    "NO SE OBSERVARON FORMAS PARASITARIAS EN LA MUESTRA ANALIZADA", 
                    "", "POR CAMPO"],
            ],
        ],
        "ESPECIALES" => [
            "" => [
                ["CHAGAS (TRYPANOSOMA CRUZI), ANTICUERPOS IgG, SANGRE", "NEGATIVO", "", "NEGATIVO"],
                ["CHAGAS (TRYPANOSOMA CRUZI), ANTICUERPOS IgM, SANGRE", "NEGATIVO", "", "NEGATIVO"],
                ["HEPATITIS B ANTIGENO DE SUPERFICIE (Hbs Ag)", "No Reactivo: Inferior a 1.00", "", "
                    No Reactivo: Inferior a 1.00
                    <br>Reactivo: Superior a 1.00
                    <br>METODO: Quimioluminiscencia"],
                ["VDRL (cuantitativo)", "NO REACTIVO", "", "NO REACTIVO 
                <br>-MÉTODO: Floculación
                <br>-Este resultado indica el estado serológico del paciente y debe ser asociado a la historia clínica y epidemiológica. 
                <br>-FALSO NEGATIVO: Puede ocurrir en la Sífilis Tardía.
                <br>-FALSO POSITIVO: Puede ocurrir en ancianos, portadores de enfermedades autoinmunes, malaria, mononucleosis, brucelosis, hepatitis, lepra, portadores de HIV, embarazadas, drogadictos, e infecciones bacterianas."],
                ["TEST DE LEISHMANIASIS IFI IgG, sangre", "NO REACTIVA", "", "NO REACTIVA"],
                ["TEST DE LEISHMANIASIS IFI IgM, sangre", "NO REACTIVA", "", "NO REACTIVA"]
            ]
        ]
    ];

    $mpdf->SetHTMLHeader(headerPaciente($p));
    // Nueva página por cada paciente excepto el primero
    if ($index > 0) {
        $mpdf->AddPage();
    }

    $html = $css;
    $html .= '<div>'; // contenedor general

    foreach ($examenes as $nombreExamen => $secciones) {
        $html .= '<table class="tabla" >';
        $html .= '<tr><th colspan="4" class="examen-seccion">'.$nombreExamen.'</th></tr>';

        foreach ($secciones as $nombreSeccion => $items) {
            if ($nombreSeccion == "") {
                
            }else if ($nombreSeccion == "SEROLOGIA"){
                $html .= '<tr><th colspan="4" class="examen-seccion">'.$nombreSeccion.'</th></tr>';
            }else if ($nombreSeccion == "HECES SIMPLES"){
                $html .= '<tr><th colspan="4" class="examen-seccion">'.$nombreSeccion.'</th></tr>';
                $html .= '
                <tr>
                    <td colspan="4" class="titulo-seccion">
                        &emsp;&emsp;
                        <span style="border-bottom: 2px solid #000;">
                            EXAMEN PARASITOLOGICO
                        </span>
                    </td>
                </tr>';
            }else{
                $html .= '
                <tr>
                    <td colspan="4" class="titulo-seccion">
                        &emsp;&emsp;
                        <span style="border-bottom: 2px solid #000;">
                            '.$nombreSeccion.'
                        </span>
                    </td>
                </tr>';
            }

            foreach ($items as $fila) {
                $html .= "
                <tr>
                    <td class='col-examen'>{$fila[0]}</td>
                    <td class='col-resultado'>{$fila[1]}</td>
                    <td class='col-unidad'>{$fila[2]}</td>
                    <td class='col-referencia'>{$fila[3]}</td>
                </tr>";
            }
        }

        $html .= '</table>';
        $mpdf->WriteHTML($html);

        // ====== AGREGAR SALTO SOLO SI NO ES EL ÚLTIMO EXAMEN ======
        if ($nombreExamen !== array_key_last($examenes)) {
            $mpdf->WriteHTML('<pagebreak>');
        }

        // Reiniciar html para el siguiente examen
        $html = "";
    }

    $html .= '</div>';

    $mpdf->WriteHTML($html);
}

$mpdf->Output("Pacientes_Examenes.pdf", "I");

function valorGlu(){
    return valorAleatorioEscalado(70, 81, 1);   // Glucosa
}

function valorUrea(){
    return valorAleatorioEscalado(10, 35, 1);    // Urea
}

function valorCre(){
    return valorAleatorioEscalado(0.60, 0.80, 0.01); // Creatinina
}

function valorGOT(){
    return valorAleatorioEscalado(10, 32, 1);     // TGO/AST
}

function valorGPT(){
    return valorAleatorioEscalado(10, 32, 1);     // TGP/ALT
}

function valorFA(){
    return valorAleatorioEscalado(80, 145, 1);   // Fosfatasa Alcalina
}


function valorCol(){
    return valorAleatorioEscalado(110, 165, 1);  // Colesterol Total
}

function valorTri(){
    return valorAleatorioEscalado(88, 116, 1);   // Triglicéridos
}

function valorHDL(){
    return valorAleatorioEscalado(46, 56, 1);    // HDL
}



function valorPlaqueta(){
    return valorAleatorioEscalado(200000, 400000, 1000);
}

function valorHematocrito(){
    return valorAleatorioEscalado(42, 47, 0.1);
}

function valorBlancos(){
    return valorAleatorioEscalado(4000, 7900, 10);
}

function valorHCM(){
    return valorAleatorioEscalado(30, 32, 0.1);
}

function valorVCM(){
    return valorAleatorioEscalado(90, 91, 0.01);
}

function valorCHCM(){
    return valorAleatorioEscalado(30, 32, 0.1);
}

function valorAleatorioEscalado($inicio, $fin, $salto) {
    $valores = [];

    // Evita bucles infinitos por floats
    $inicio = floatval($inicio);
    $fin = floatval($fin);
    $salto = floatval($salto);

    for ($v = $inicio; $v <= $fin + 0.0000001; $v += $salto) {
        // Redondeo automático para evitar cositas como 42.300000004
        $valores[] = round($v, 6);
    }

    // Elegir aleatorio
    return $valores[array_rand($valores)];
}

function obtenerValoresAleatorios() {

    // Matriz con tus datos
    $matriz = [
        // lin (fila 0)
        [40, 43, 48, 30, 32, 48, 42, 44, 40, 38],
        // seg (fila 1)
        [58, 55, 50, 67, 66, 50, 56, 55, 57, 60],
        // mo  (fila 2)
        [2,  2,  2,  3,  2,  2,  2,  1,  3,  2]
    ];

    // Elegir columna aleatoria de 0 a 9
    $columna = rand(0, 9);

    // Vector resultado
    return [
        $matriz[0][$columna],   // lin
        $matriz[1][$columna],   // seg
        $matriz[2][$columna]    // mo
    ];
}

function obtenerHorasAleatorias() {
    $matriz = [
        // hora1 (fila 0)
        [7,5,6,7,8,3,5,6,7,8,10,12,5,6,7,8,9,9,7,6],
        // hora2 (fila 1)
        [10,12,14,12,19,10,15,13,12,13,18,18,14,16,16,17,17,15,15,14]
    ];

    $columna = rand(0, count($matriz[0]) - 1);

    return [
        $matriz[0][$columna],   // hora1
        $matriz[1][$columna]    // hora2
    ];
}

function obtenerBillisAleatoria() {
    $matriz = [
        [0.4, 0.41, 0.5, 0.55, 0.56, 0.77, 0.58, 0.59, 0.6, 0.56, 0.62, 0.65, 0.68, 0.7, 0.68, 0.66, 0.55, 0.78, 0.76, 0.56],
        [0.17, 0.12, 0.13, 0.14, 0.15, 0.16, 0.17, 0.18, 0.19, 0.20, 0.21, 0.22, 0.23, 0.24, 0.18, 0.22, 0.12, 0.22, 0.21, 0.10]
    ];

    $columna = rand(0, count($matriz[0]) - 1);

    return [
        $matriz[0][$columna],   
        $matriz[1][$columna]  
    ];
}

function perfilOrinaAleatorio() {

    $perfiles = [

        ["color" => "Amarilla","aspecto" => "Claro","densidad" => "1015","ph" => "6.0","reac" => "Ácida","epi" => "1 a 2 por Campo","leu" => "0 a 1 por Campo","hema" => "0 a 1 por Campo"],

        ["color" => "Amarillo pálido","aspecto" => "Claro","densidad" => "1016","ph" => "6.5","reac" => "Neutra","epi" => "2 a 3 por Campo","leu" => "1 a 2 por Campo","hema" => "1 a 2 por Campo"],

        ["color" => "Amarillo","aspecto" => "Ligeramente turbia","densidad" => "1014","ph" => "6","reac" => "Ácida","epi" => "0 a 2 por Campo","leu" => "0 a 1 por Campo","hema" => "0 a 1 por Campo"],

        ["color" => "Amarillo intenso","aspecto" => "Claro","densidad" => "1020","ph" => "7.0","reac" => "Neutra","epi" => "1 a 3 por Campo","leu" => "1 a 2 por Campo","hema" => "0 a 1 por Campo"],

        ["color" => "Amarilla","aspecto" => "Ligeramente turbia","densidad" => "1018","ph" => "6.0","reac" => "Ácida","epi" => "2 a 4 por Campo","leu" => "1 a 2 por Campo","hema" => "1 a 2 por Campo"],

        ["color" => "Amarillo pálido","aspecto" => "Claro","densidad" => "1013","ph" => "6.0","reac" => "Ácida","epi" => "0 a 1 por Campo","leu" => "0 a 1 por Campo","hema" => "0 a 1 por Campo"],

        ["color" => "Amarilla","aspecto" => "Claro","densidad" => "1017","ph" => "7.0","reac" => "Neutra","epi" => "1 a 2 por Campo","leu" => "1 a 2 por Campo","hema" => "1 a 2 por Campo"],

        ["color" => "Amarilla","aspecto" => "Ligeramente turbia","densidad" => "1019","ph" => "6.0","reac" => "Ácida","epi" => "2 a 3 por Campo","leu" => "0 a 1 por Campo","hema" => "1 a 2 por Campo"],

        ["color" => "Amarilla","aspecto" => "Claro","densidad" => "1012","ph" => "6","reac" => "Ácida","epi" => "1 a 2 por Campo","leu" => "0 a 1 por Campo","hema" => "0 a 1 por Campo"],

        ["color" => "Amarillo","aspecto" => "Ligeramente turbia","densidad" => "1021","ph" => "8","reac" => "Alcalina","epi" => "2 a 3 por Campo","leu" => "1 a 2 por Campo","hema" => "1 a 2 por Campo"]
    ];

    return $perfiles[array_rand($perfiles)];
}


