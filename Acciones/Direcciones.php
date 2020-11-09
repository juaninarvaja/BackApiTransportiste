<?php


class Direcciones{   
// public function SubirUno($request, $response, $args){
//             // if($_POST['METHOD']=='POST'){
//             unset($_POST['METHOD']);
//             $direccionLLegada=$_POST['DireccionLlegada'];
//             $direccionOrigen=$_POST['DireccionOrigen'];
//             $distancia = $_POST['distancia'];
//             $descripcion = $_POST['descripcion'];
//             $idCliente = $_POST['idCliente'];
            
//             //insertar en estado VER NAQUINA DE ESTADOS (?)
//             $query="INSERT INTO `usuarios` (`tipoUsuario`, `email`, `contrasenia`) VALUES ('$tipouser', '$mail', '$contrasenia')";
//             $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
//             $resultado=metodoPost($query, $queryAutoIncrement);
//             echo json_encode($resultado);
//             header("HTTP/1.1 200 OK");
//             exit();
//         // }
//     }
    public  function SubirUnaDirecc($calle, $Ciudad, $Departamento,$Provincia,$CodigoPost,$Numeracion,$info){
        // echo  $calle, $Ciudad, $Departamento,$Provincia,$CodigoPost,$Numeracion,$info;
        $query="INSERT INTO `direcciones` (`Calle`, `Ciudad`, `Departamento`,`Provincia`,`CP`,`Numeracion`,`InfoExtra`)
         VALUES ('$calle' , '$Ciudad' , '$Departamento','$Provincia','$CodigoPost','$Numeracion','$info')";
               $queryAutoIncrement="select MAX(idDireccion) as id from direcciones";
           
               $resultado=metodoPost($query, $queryAutoIncrement);
        
               header("HTTP/1.1 200 OK");
               return $resultado["id"];
              
        //retorno el id
    }
    public  function TraerDireccionbyId($id){
        // echo  $calle, $Ciudad, $Departamento,$Provincia,$CodigoPost,$Numeracion,$info;
        $query="SELECT * FROM `direcciones` WHERE idDireccion = $id";

               $resultado=metodoGet($query);
        
               header("HTTP/1.1 200 OK");
               return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    }
              
    
    
}
?>
