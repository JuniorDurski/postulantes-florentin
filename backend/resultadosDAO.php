<?php
header("Content-Type: application/json; charset=utf-8");
require_once "../conexion.php";

$q = $_GET["q"] ?? "";


switch ($q) {


    // ==========================================================
    // GENERAR ESTRUCTURA DE RESULTADOS
    // ==========================================================
    case "estructura":

        $id_designacion = $_GET["id_designacion"];
        $id_postulante = $_GET["id_postulante"];

        // TRAER ESTUDIOS SEGÚN DESIGNACIÓN
        $sql = "
            SELECT e.*
            FROM requisitos r
            JOIN estudios e ON e.id_estudio = r.id_estudio
            WHERE r.id_designacion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_designacion);
        $stmt->execute();
        $rs = $stmt->get_result();

        $estudios = [];

        while ($est = $rs->fetch_assoc()) {

            // SECCIONES
            $sqlSec = "SELECT * FROM secciones WHERE id_estudio=?";
            $s = $conexion->prepare($sqlSec);
            $s->bind_param("i", $est["id_estudio"]);
            $s->execute();
            $rsSec = $s->get_result();

            $secciones = [];

            while ($sec = $rsSec->fetch_assoc()) {

                // EXÁMENES
                $sqlEx = "
                    SELECT ex.*,
                           (SELECT resultado
                            FROM resultados
                            WHERE id_examen = ex.id_examen
                              AND id_postulante = ?
                            LIMIT 1) AS resultado
                    FROM examen ex
                    WHERE ex.id_seccion = ?
                    ORDER BY ex.orden";

                $x = $conexion->prepare($sqlEx);
                $x->bind_param("ii", $id_postulante, $sec["id_seccion"]);
                $x->execute();
                $rsEx = $x->get_result();

                $examenes = [];
                while ($ex = $rsEx->fetch_assoc()) $examenes[] = $ex;

                $sec["examenes"] = $examenes;
                $secciones[] = $sec;
            }

            $est["secciones"] = $secciones;
            $estudios[] = $est;
        }

        echo json_encode($estudios);
        break;

    // ==========================================================
    // GUARDAR RESULTADOS
    // ==========================================================
    case "guardar":

        $datos = json_decode($_POST["datos"], true);

        foreach ($datos as $d) {

            // ¿EXISTE YA?
            $sql = "SELECT id_resultado FROM resultados WHERE id_examen=? AND id_postulante=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $d["id_examen"], $d["id_postulante"]);
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows) {
                // actualizar
                $sql = "UPDATE resultados SET resultado=? WHERE id_examen=? AND id_postulante=?";
                $u = $conexion->prepare($sql);
                $u->bind_param("sii", $d["resultado"], $d["id_examen"], $d["id_postulante"]);
                $u->execute();
            } else {
                // insertar
                $sql = "INSERT INTO resultados (id_examen, id_postulante, resultado)
                        VALUES (?, ?, ?)";
                $i = $conexion->prepare($sql);
                $i->bind_param("iis", $d["id_examen"], $d["id_postulante"], $d["resultado"]);
                $i->execute();
            }
        }

        echo json_encode(["ok" => true]);
        break;

    case "generarHematologia":

        $id_postulante = $_POST['id_postulante'];

        // Definir rangos por examen
        $rangos = [
            2 => [42, 43, 0.1],
            3 => [4000, 4100, 10],
            4 => [42, 47, 0.1],
            5 => [10, 20, 0.5],
            6 => [0.1, 1.0, 0.1],
            7 => [70, 110, 1],
        ];

        foreach ($rangos as $id_examen => [$ini, $fin, $salto]) {

            $resultado = valorAleatorio($ini, $fin, $salto);

            // Verificar si existe
            $sql = "SELECT id_resultado FROM resultados 
                    WHERE id_examen=? AND id_postulante=?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ii", $id_examen, $id_postulante);
            $stmt->execute();
            $rs = $stmt->get_result();

            if ($rs->num_rows) {
                $sql = "UPDATE resultados SET resultado=? 
                        WHERE id_examen=? AND id_postulante=?";
                $u = $conexion->prepare($sql);
                $u->bind_param("sii", $resultado, $id_examen, $id_postulante);
                $u->execute();
            } else {
                $sql = "INSERT INTO resultados (id_examen, id_postulante, resultado)
                        VALUES (?, ?, ?)";
                $i = $conexion->prepare($sql);
                $i->bind_param("iis", $id_examen, $id_postulante, $resultado);
                $i->execute();
            }
        }

        echo json_encode(["ok" => true]);
    break;


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

