<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER["REQUEST_METHOD"];
if ($method == "OPTIONS") {
    die();
}


require_once('../models/proveedores.model.php');
error_reporting(0);
$proveedores = new Provedores;

switch ($_GET["op"]) {
        

    case 'todos': 
        $datos = array(); 
        $datos = $proveedores->todos();
        while ($row = mysqli_fetch_assoc($datos)) 
        {
            $todos[] = $row;
        }
        echo json_encode($todos);
        break;
     
    case 'uno':
        $idProveedores = $_POST["idProveedores"];
        $datos = array();
        $datos = $proveedores->uno($idProveedores);
        $res = mysqli_fetch_assoc($datos);
        echo json_encode($res);
        break;
       
    case 'insertar':
        $Nombre_Empresa = $_POST["Nombre_Empresa"];
        $Direccion = $_POST["Direccion"];
        $Telefono = $_POST["Telefono"];
        $Contacto_Empresa = $_POST["Contacto_Empresa"];
        $Teleofno_Contacto = $_POST["Teleofno_Contacto"];

        $datos = array();
        $datos = $proveedores->insertar($Nombre_Empresa, $Direccion, $Telefono, $Contacto_Empresa, $Teleofno_Contacto);
        echo json_encode($datos);
        break;
        
    case 'actualizar':
        $idProveedores = $_POST["idProveedores"];
        $Nombre_Empresa = $_POST["Nombre_Empresa"];
        $Direccion = $_POST["Direccion"];
        $Telefono = $_POST["Telefono"];
        $Contacto_Empresa = $_POST["Contacto_Empresa"];
        $Teleofno_Contacto = $_POST["Teleofno_Contacto"];
        $datos = array();
        $datos = $proveedores->actualizar($idProveedores, $Nombre_Empresa, $Direccion, $Telefono, $Contacto_Empresa, $Teleofno_Contacto);
        echo json_encode($datos);
        break;
        
    case 'eliminar':
        $idProveedores = $_POST["idProveedores"];
        $datos = array();
        $datos = $proveedores->eliminar($idProveedores);
        echo json_encode($datos);
        break;
}
