  
<?php
include  "Direcciones.php";
// include  "ClienteApi.php";


class PedidosApi{        

    // Retorna array json de todos los elementos.
    public function TraerTodos($request, $response, $args) {

        if($_SERVER['REQUEST_METHOD']=='GET'){
            //si contiene un id es a un solo registro
            if(isset($_GET['id'])){
                $query="select * from pedido where idAuth=".$_GET['idAuth'];
                $resultado=metodoGet($query);
                echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
            }else{ //sino a todos
                $query="select * from pedido";
                $resultado=metodoGet($query);
                $JsonRta = json_encode($resultado->fetchAll());
                $array = json_decode($JsonRta,true);
                $auxArray = array();

                for($i= 0; $i<count($array);$i++){
                    
                    if($array[$i]["estado"] == "POSTEADO"){
           
                        // echo count($array);
                            $DireccionOrigen = json_decode(Direcciones::TraerDireccionById($array[$i]["DireccionOrigen"]),true);
                            $DireccionDestino = json_decode(Direcciones::TraerDireccionById($array[$i]["DireccionLlegada"]),true);
                            $array[$i]["DireccionOrigen"] =   $DireccionOrigen ;
                            $array[$i]["DireccionLlegada"] =  $DireccionDestino;
                            $clienteInfo = json_decode(ClienteApi::TraerClientePorId($array[$i]["idCliente"]),true);
                            $array[$i]["clienteInfo"] = $clienteInfo;
                            array_push($auxArray,$array[$i]);
                            // echo "clienteInfo";
                            // var_dump($clienteInfo);
                    }
                   
                }
                echo json_encode($auxArray);
              
                //   echo json_encode($array);
            }
            header("HTTP/1.1 200 OK");
            exit();
        }
    }
    public function SubirUno($request, $response, $args){
            // if($_POST['METHOD']=='POST'){
            unset($_POST['METHOD']);
            // Direccion Origen;
                $calleO = $_POST['calleOrigen'];
                $CiudadO = $_POST['ciudadOrigen'];
                $DepartamentoO = $_POST['departamentoOrigen'];
                $ProvinciaO = $_POST['provinciaOrigen'];
                $CodigoPostO= $_POST['codigoPostalOrigen'];
                $NumeracionO= $_POST['numeracionOrigen'];
                $infoO = $_POST['infoOrigen'];
                
                $idDireccOrigen = Direcciones::SubirUnaDirecc($calleO, $CiudadO, $DepartamentoO,$ProvinciaO,$CodigoPostO,$NumeracionO,$infoO);
                //  echo "Direccion origen id:";
                //  echo (int)$idDireccOrigen;
            // direccion Llegada
            $calleD = $_POST['calleDestino'];
            $CiudadD = $_POST['ciudadDestino'];
            $DepartamentoD = $_POST['departamentoDestino'];
            $ProvinciaD = $_POST['provinciaDestino'];
            $CodigoPostD= $_POST['codigoPostalDestino'];
            $NumeracionD= $_POST['numeracionDestino'];
            $infoD = $_POST['infoDestino'];
            $idDireccLlegada = (int)Direcciones::SubirUnaDirecc($calleD, $CiudadD, $DepartamentoD,$ProvinciaD,$CodigoPostD,$NumeracionD,$infoD);
            // echo "Direccion llegada id:";
            // echo $idDireccLlegada;
            // //
            $distancia = $_POST['distancia'];
            $descripcion = $_POST['descripcion'];
            $idCliente = $_POST['idCliente'];
            
            //insertar en estado POSTEADO (?)
             $query="INSERT INTO `pedido` (`idCliente`, `DireccionLlegada`, `DireccionOrigen`,`PropuestasRecibidas`,`estado`, `Distancia`,`descripcion`)
              VALUES ('$idCliente', '$idDireccLlegada','$idDireccOrigen','','POSTEADO', '$distancia', '$descripcion')";
            //   echo $query;
            $queryAutoIncrement="select MAX(idPedido) as id from pedido";
            $resultado=metodoPost($query, $queryAutoIncrement);
            
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
            exit();
        }
//    public function ActualizarUno($request, $response, $args){
//     //    echo("entro a actualizar uno");
//          $data = $request->getParsedBody();
//          $id = isset($data["idAuth"])?$data["idAuth"]:null;
//          $tipouser= isset($data["tipoUsuario"])?$data["tipoUsuario"]:null;
//          $mail= isset($data["email"])?$data["email"]:null;
//          $contrasenia= isset($data["contrasenia"])?$data["contrasenia"]:null;

//         $query = "UPDATE `usuarios` SET `tipoUsuario`= '$tipouser',`email`='$mail',`contrasenia`='$contrasenia' WHERE `idAuth`= $id";
//         $resultado = metodoPut($query);
//         echo json_encode($resultado);
//         header("HTTP/1.1 200 OK");
//         exit();
//    }
   
//    public function BorrarById($request, $response, $args){
     
//     if($_POST['METHOD']=='DELETE'){
//         unset($_POST['METHOD']);
//         $id=$_POST['id'];
//         $query="DELETE FROM usuarios WHERE idAuth='$id'";
//         $resultado=metodoDelete($query);
//         echo json_encode($resultado);
//         header("HTTP/1.1 200 OK");
//         exit();
//     }
//    }
            public function existePedidoId($id) {
                $query="SELECT `idPedido` FROM `pedido` WHERE idPedido = $id";

                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
            
            }
}
?>
