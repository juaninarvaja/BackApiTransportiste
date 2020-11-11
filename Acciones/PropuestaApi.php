<?php


    class PropuestaApi{        
        //

        public function CotizarUno($request, $response, $args){
            // if($_POST['METHOD']=='POST'){
            // unset($_POST['METHOD']);
            if(isset($_POST['idPedido']) && isset($_POST['Precio']) && isset($_POST['idTransportista']) && isset($_POST['informacion'])){
                $idPedido=$_POST['idPedido'];
                $idTransportista=$_POST['idTransportista'];
                $precio=$_POST['Precio'];
                $informacion = $_POST['informacion'];

                // $rta = PropuestaApi::existePropuestaId($idPedido);
                $rta = PedidosApi::existePedidoId($idPedido);
                if($rta!="false"){
                    //aca tengo q validar q el idTransportista esta bien
                    $rta = TransportistaApi::ExisteTransportistaId($idTransportista);
                    if($rta!="false"){
         
                        //primero genero una propuesta, la guardo en la bd
                        $query="INSERT INTO `propuesta`(`idPedido`, `Precio`, `idTransportista`, `informacion`) VALUES ($idPedido,$precio,$idTransportista,'$informacion')";
                        $queryAutoIncrement="select MAX(idPropuesta) as id from propuesta";
                        $resultado=metodoPost($query, $queryAutoIncrement);
                        //  echo json_encode($resultado);
                        header("HTTP/1.1 200 OK");
                        //luego guardo el id de esa propeuesta en el array de propuestas del pedido
                        // echo $resultado["id"]; //idPorpuesta para desp guardar
                        $respuesta = PropuestaAPI::getArrayPropuestasRecibidas($idPedido);
                        array_push($respuesta,$resultado["id"]);
                        $auxStringData = implode(",",$respuesta);

                        //echo $auxStringData;
                       $query2 = "UPDATE `pedido` SET `PropuestasRecibidas`= '$auxStringData'  WHERE idPedido = $idPedido"; 
                        $resultado2 =  metodoPut($query2);
                        // var_dump($resultado2);
                        exit(true);
                      

                        
                    }
                    else{
                        echo "No existe un transportista con ese id";
                    }
                }
                else{
                    echo "No existe un pedido con ese id";
                }
            }
            else{
                echo "no recibio los datos necesarios";
            }
              
             
         }
            public function existePropuestaId($id) {
                $query="SELECT `idPropuesta`, `idPedido`, `Precio`, `idTransportista`, `informacion` FROM `propuesta` WHERE idPedido = $id";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
              
            }

            public function getArrayPropuestasRecibidas($idPedido){
                // $idint = intval($idPedido);
                // echo $idint;
                $query = "SELECT `PropuestasRecibidas` FROM `pedido` WHERE `idPedido` = $idPedido";
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                // return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
                $unformatArray = $resultado->fetch(PDO::FETCH_ASSOC);
                // var_dump($strArray);
                $array = PropuestaApi::FormatArray($unformatArray);
                return $array;

            } 
            public function FormatArray($stringInfo){
                $stringInfo["PropuestasRecibidas"];
                    $arrayFormat = explode(",", $stringInfo["PropuestasRecibidas"]);
                    return $arrayFormat;
  
        
            }     


        // }
    }
    
    ?>
    