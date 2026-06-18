<?php
header('Content-Type: application/json; charset=utf-8');
require_once "../conexion.php";

$q = isset($_GET['q']) ? $_GET['q'] : '';

switch ($q) {

    // ============================================================
    // LISTAR - Devuelve todas las designaciones
    // ============================================================
    case 'listar':

        $sql = "SELECT id_designacion, designacion, fecha FROM designaciones ORDER BY id_designacion DESC";
        $rs  = $conexion->query($sql);

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    // ============================================================
    // GUARDAR (INSERTAR o EDITAR)
    // ============================================================
    case 'guardar':

        $id    = $_POST['id_designacion'];
        $des   = strtoupper($_POST['designacion']);
        $fecha = $_POST['fecha'];

        // Si existe ID → EDITAR
        if ($id != "") {

            $sql = "UPDATE designaciones 
                    SET designacion=?, fecha=? 
                    WHERE id_designacion=?";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $des, $fecha, $id);

            if ($stmt->execute()) {
                echo json_encode(["status" => "ok"]);
            } else {
                echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
            }

        } else {
        // Si NO hay ID → INSERTAR

            $sql = "INSERT INTO designaciones (designacion, fecha)
                    VALUES (?, ?)";

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ss", $des, $fecha);

            if ($stmt->execute()) {
                echo json_encode(["status" => "ok"]);
            } else {
                echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
            }
        }

        break;

    // ============================================================
    // ELIMINAR
    // ============================================================
    case 'eliminar':

        if (!isset($_POST['id'])) {
            echo json_encode(["status" => "error", "mensaje" => "ID no recibido"]);
            exit;
        }

        $id = $_POST['id'];

        $sql = "DELETE FROM designaciones WHERE id_designacion=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
        }

        break;

    case 'listarEstudios':
        $sql = "SELECT id_estudio, estudio FROM estudios ORDER BY estudio";
        $rs = $conexion->query($sql);

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    case 'listarReq':

        $id = $_GET['id'];

        $sql = "SELECT r.id_requisito, e.estudio AS estudio
                FROM requisitos r
                JOIN estudios e ON e.id_estudio = r.id_estudio
                WHERE r.id_designacion=?";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $rs = $stmt->get_result();

        $data = [];
        while ($row = $rs->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
        break;

    case 'agregarReq':

        $idDesign = $_POST['id_designacion'];
        $idEstudio = $_POST['id_estudio'];

        $sql = "INSERT INTO requisitos (id_designacion, id_estudio) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $idDesign, $idEstudio);

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
        }
        break;

    case 'eliminarReq':

        $idReq = $_POST['id_requisito'];

        $sql = "DELETE FROM requisitos WHERE id_requisito=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $idReq);

        if ($stmt->execute()) {
            echo json_encode(["status" => "ok"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => $stmt->error]);
        }
        break;

    // ============================================================
    // OPCIÓN NO VÁLIDA
    // ============================================================
    default:
        echo json_encode(["status" => "error", "mensaje" => "Operación no válida"]);
        break;
}
?>
