  
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
    public function TraerTodosTodos($request, $response, $args) {

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
                $idCliente = $_POST['idCliente'];
                $existe = ClienteApi::TraerClientePorId($idCliente);
                if(strcmp ($existe , "false" ) != 0){
                   
               
                   
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
               
                
                //insertar en estado POSTEADO (?)
                 $query="INSERT INTO `pedido` (`idCliente`, `DireccionLlegada`, `DireccionOrigen`,`PropuestasRecibidas`,`estado`, `Distancia`,`descripcion`)
                  VALUES ('$idCliente', '$idDireccLlegada','$idDireccOrigen','','POSTEADO', '$distancia', '$descripcion')";
                //   echo $query;
                $queryAutoIncrement="select MAX(idPedido) as id from pedido";
                $resultado=metodoPost($query, $queryAutoIncrement);
                
                echo json_encode($resultado);
                //actualizer el array de pedidos de ese idCliente
                ClienteApi::AgregarPedidoACliente($idCliente,$resultado["id"]);
    
                header("HTTP/1.1 200 OK");
                exit();
                }
                else {
                    echo "ese cliente no existe";
                }

        }


            public function TraerPedidosConSusPropuestas($request, $response, $args){
                if(isset($_POST["idPedido"])){
                    $idPedido = $_POST['idPedido'];
                    // echo $idPedido;
                    $rta =PedidosAPi::borrarPedidoYPropuestasAsociadas($idPedido);
                    
                    // if(strcmp ($rta , "false" ) != 0){
                    //     $propuestas = PropuestaApi::traerPropuestasIdPedido($idPedido);
                    //     echo ($propuestas);
                      
                    // }
                    // else{
                    //     echo "no existe ese idPedido";
                    // }
                   
                }else{
                    echo "no mandaste el idPedido";
                }

            }
            public function  cancelarPedidoPorIdPedido($request, $response, $args){
                if(isset($_POST["idPedido"])){
                    $idPedido = $_POST['idPedido'];
                    // echo $idPedido;
                    $rta =PedidosAPi::TraerUnobyIdPedido($idPedido);
                    
                    if(strcmp ($rta , "false" ) != 0){
                        $propuestas = PedidosApi::borrarPedidoYPropuestasAsociadas($idPedido);
                        echo $propuestas;
                      
                    }
                    else{
                        echo "no existe ese idPedido";
                    }
                   
                }else{
                    echo "no mandaste el idPedido";
                }

            }
            public function borrarPedidoYPropuestasAsociadas($idPedido){
                $propuestasAsoc = PropuestaApi::traerTodasPropuestasPorIdPedido($idPedido);
                $arrayProp = json_decode($propuestasAsoc,true);
                //var_dump(count($arrayProp));
                if(count($arrayProp)>0 && $arrayProp != null){
                    for($i=0;$i<count($arrayProp);$i++){
                        $rta = PropuestaApi::borrarPropuestaPorId($arrayProp[$i]["idPropuesta"]);
                       //var_dump($arrayProp[$i]["idPropuesta"]);
                    }
                    
           
                }
                $query="DELETE FROM `pedido` WHERE idPedido =$idPedido";
                $resultado=metodoDelete($query);
                return $resultado;
                }


            public function TraerUnobyIdPedido($id) {
                $query="SELECT `idCliente`, `idPedido`, `DireccionLlegada`, `DireccionOrigen`, `PropuestasRecibidas`, `estado`, `Distancia`, `descripcion` FROM `pedido` WHERE idPedido = $id";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
    
            }

            public function updateEstadoPedidoById($idPedido,$estado){
                $query = "UPDATE `pedido` SET `estado`='$estado' WHERE idPedido = $idPedido";
                $resultado = metodoPut($query);
                header("HTTP/1.1 200 OK");
                return json_encode($resultado);
            }
            
    
                public function existePedidoId($id) {
                    $query="SELECT `idPedido` FROM `pedido` WHERE idPedido = $id";
    
                    $resultado = metodoGet($query);
                    header("HTTP/1.1 200 OK");
                    return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
                
                }

            public function traerArrayIdPedidosDeUnIdCliente($idCliente){
                $query = "SELECT `idPedido` FROM `pedido` WHERE idCliente = $idCliente";
                $resultado = metodoGet($query);
                //header("HTTP/1.1 200 OK");

                $resp= json_encode($resultado->fetchAll());  
                $array = json_decode($resp,true);
                return $array;
                //var_dump(count($array));
            }


    
}
?>
