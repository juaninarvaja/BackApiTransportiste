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
            public function existePropuestaPorIdPedido($id) {
                $query="SELECT `idPropuesta`, `idPedido`, `Precio`, `idTransportista`, `informacion` FROM `propuesta` WHERE idPedido = $id";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                return json_encode($resultado->fetch(PDO::FETCH_ASSOC));
              
            }

            public function traerTodasPropuestasPorIdPedido($id) {
                $query="SELECT `idPropuesta`, `idPedido`, `Precio`, `idTransportista`, `informacion` FROM `propuesta` WHERE idPedido = $id";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
                return  json_encode($resultado->fetchAll());;
              
            }

            public function existePropuestaPorId($id) {
                $query="SELECT `idPropuesta`, `idPedido`, `Precio`, `idTransportista`, `informacion` FROM `propuesta` WHERE idPropuesta = $id";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
               
                 $array = json_encode($resultado->fetch(PDO::FETCH_ASSOC),true);

                 if($array != "false"){
                    $array = json_decode($array,true);
                    $idTranpo = (int) $array["idTransportista"];
                    $infoTransp = json_decode(TransportistaApi::ExisteTransportistaId($idTranpo),true);
                    $array["infoTransp"] = $infoTransp;
                    
                    
                    $respuesta =json_encode($array,true);
                    return $respuesta;
                 }
                 else { return false; }
            }

            public function existePropuestaPorIdTranspo($idT) {
            
                $query="SELECT `idPropuesta`, `idPedido`, `Precio`, `idTransportista`, `informacion` FROM `propuesta` WHERE idTransportista = $idT";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
               
                //  $array = json_encode($resultado->fetch(PDO::FETCH_ASSOC),true);
                 $resp= json_encode($resultado->fetchAll());
                  
                  $array = json_decode($resp,true);
                 if($array != "false"){
                     //var_dump($array);
                    for($i = 0;$i<count($array);$i++){
                        


                    $idPedido= $array[$i]["idPedido"];
                    $infoPedido = json_decode(PedidosApi::TraerUnobyIdPedido($idPedido),true);
                    $array[$i]["infoPedido"] = $infoPedido;
                    

                    $idCliente= $infoPedido["idCliente"];
                    $idCliente = json_decode(ClienteApi::TraerClientePorId($idCliente),true);
                    $array[$i]["infoCliente"] = $idCliente;
                   // var_dump($array[$i]);
                    }
                    
                    $respuesta =json_encode($array,true);
                    //var_dump($respuesta);
                    return $respuesta;
                 }
                 else { return false; }
            }




            public function TraerPorId($request, $response, $args){
                if(isset($_POST['idPropuesta'])) {
                
                    $idPropuesta=$_POST['idPropuesta'];
                    
                   
                    $ppta = Propuestaapi::existePropuestaPorId($idPropuesta);
                    var_dump($ppta);
                    if($ppta!="false"){
                        
                        echo $ppta;
                    }
                    else{
                        echo "no existe ese idPropuesta";
                    }
                    
                }
                else{ echo "falta idPropuesta";}
            }

            public function TraerPorIdTransp($request, $response, $args){
                if(isset($_POST['idTransportista'])) {
                
                    $idTransportista=$_POST['idTransportista'];
                    
                   
                    $ppta = Propuestaapi::existePropuestaPorIdTranspo($idTransportista);
                    //var_dump($ppta);
                    if($ppta!="false"){
                        
                        echo $ppta;
                    }
                    else{
                        echo "no existe ese idTranspo";
                    }
                    
                }
                else{ echo "falta idTransportista";}
            }

            public function TraerPorEmailTransp($request, $response, $args){
                if(isset($_POST['email'])) {
                
                    $email=$_POST['email'];
                    $infoTta = TransportistaApi::TraerTransportPorMail($email);
                    if($infoTta == "false" || $infoTta == false){
                        echo "ese mail no existe";
                    }
                    else{
                        $array = json_decode($infoTta,true);
                          $ppta = Propuestaapi::existePropuestaPorIdTranspo($array["idTransportista"]);

                        if($ppta!="false"){
                            
                            echo $ppta;
                        }
                        else{
                            echo "no existe ese idTranspo";
                        }
                    }
                }
                else{ echo "falta el mail";}
            }
            
            public function cancelarPropuestaByIdProp($request, $response, $args){
                if(isset($_POST['idPropuesta'])) {
                
                    $idPropuesta=$_POST['idPropuesta'];
                    $infoProp = PropuestaApi::existePropuestaPorId($idPropuesta);


                    //$infoTta = TransportistaApi::TraerTransportPorMail($email);
                    if($infoProp == "false" || $infoProp == false){
                        echo "ese id Propuesta  no existe";
                    }
                    else{
                        $borro = PropuestaApi::borrarPropuestaPorId($idPropuesta);
                        return true;
                    }
                }
                else{ echo "falta el idPropuesta";}
            }
            


            public function traerPropuestasIdPedido($id) {
              
                $respuesta = " ";
                $query="SELECT * FROM `propuesta` WHERE idPedido = $id";
                
                $resultado = metodoGet($query);
                
                $JsonRta = json_encode($resultado->fetchAll());
                $array = json_decode($JsonRta,true);
              
                //faltaria traer la info del transportista tambiern 
                //echo count($array);
               
                for($i = 0;$i<count($array);$i++){
                    // var_dump((int)$array[$i]["idTransportista"]);
                    if(isset($array[$i]["idTransportista"])){
                        //echo "todo ok";
                        $idTranpo = $array[$i]["idTransportista"];
                        //echo $idTranpo;
                         $infoTransp = json_decode(TransportistaApi::ExisteTransportistaId($idTranpo),true);
                         $array[$i]["infoTransp"] = $infoTransp;
                         // echo $infoTransp;
                        // var_dump ($array);
                         //$respuesta =json_encode($array,true));
                         
                    }
                    // echo "llego aca";
                    $respuesta =json_encode($array,true);
                   

                }
                header("HTTP/1.1 200 OK");
                return $respuesta;
              
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
            public function borrarPropuestaPorId($idProp){
                $idPropuesta = (int) $idProp;
                $query="DELETE FROM `propuesta` WHERE idPropuesta =$idPropuesta";
                $resultado=metodoDelete($query);
                return $resultado;
            }

            

        // }
    }
    
    ?>
    