<?php

require_once('../config/config.php');

class Factura
{
    public function todos() 
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoParaConectar();
        $cadena = "SELECT factura.idFactura, clientes.Nombres, (factura.Sub_total + factura.Sub_total_iva) as total FROM `factura` INNER JOIN clientes on factura.Clientes_idClientes = clientes.idClientes";
        $datos = mysqli_query($con, $cadena);
        $con->close();
        return $datos;
    }

    public function uno($idFactura)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoParaConectar();
        $cadena = "SELECT * FROM `factura` INNER JOIN clientes on factura.Clientes_idClientes = clientes.idClientes WHERE `idFactura` = $idFactura";
        $datos = mysqli_query($con, $cadena);
        $con->close();
        return $datos;
    }

    public function insertar($Fecha, $Sub_total, $Sub_total_iva, $Valor_IVA, $Clientes_idClientes) 
    {
        try {
            $con = new ClaseConectar();
            $con = $con->ProcedimientoParaConectar();
            $cadena = "INSERT INTO `factura`(`Fecha`, `Sub_total`, `Sub_total_iva`, `Valor_IVA`, `Clientes_idClientes`) 
                       VALUES ('$Fecha', '$Sub_total', '$Sub_total_iva', '$Valor_IVA', '$Clientes_idClientes')";
            //echo $cadena;
            if (mysqli_query($con, $cadena)) {
                return $con->insert_id; 
            } else {
                return $con->error;
            }
        } catch (Exception $th) {
            return $th->getMessage();
        } finally {
            $con->close();
        }
    }

    public function actualizar($idFactura, $Fecha, $Sub_total, $Sub_total_iva, $Valor_IVA, $Clientes_idClientes)
    {
        try {
            $con = new ClaseConectar();
            $con = $con->ProcedimientoParaConectar();
            $cadena = "UPDATE `factura` SET 
                       `Fecha`='$Fecha',
                       `Sub_total`='$Sub_total',
                       `Sub_total_iva`='$Sub_total_iva',
                       `Valor_IVA`='$Valor_IVA',
                       `Clientes_idClientes`='$Clientes_idClientes'
                       WHERE `idFactura` = $idFactura";
            if (mysqli_query($con, $cadena)) {
                return $idFactura; 
            } else {
                return $con->error;
            }
        } catch (Exception $th) {
            return $th->getMessage();
        } finally {
            $con->close();
        }
    }

    public function eliminar($idFactura) 
    {
        try {
            $con = new ClaseConectar();
            $con = $con->ProcedimientoParaConectar();
            $cadena = "DELETE FROM `factura` WHERE `idFactura`= $idFactura";
            if (mysqli_query($con, $cadena)) {
                return 1; 
            } else {
                return $con->error;
            }
        } catch (Exception $th) {
            return $th->getMessage();
        } finally {
            $con->close();
        }
    }
}
