<?php


    class ClienteApi{        

        // Retorna array json de todos los elementos.
        public function TraerClientePorMail($mail) {
            $query="SELECT * FROM `clientes` WHERE email = '$mail'";

            $resultado=metodoGet($query);
     
            header("HTTP/1.1 200 OK");
            return json_encode($resultado->fetch(PDO::FETCH_ASSOC));

        }
        public function TraerClientePorId($id) {
            $query="SELECT * FROM `clientes` WHERE id = $id";

            $resultado=metodoGet($query);
            header("HTTP/1.1 200 OK");
            
            return json_encode($resultado->fetch(PDO::FETCH_ASSOC));

        }
        public function TraerPorMailPost($request, $response, $args) {
            if(isset($_POST['mail'])){
                $mail=$_POST['mail'];
                $rta =ClienteApi::TraerClientePorMail($mail);
                echo $rta;
            }   
            else {
                echo "falta el mail";
            }
        }

        public function  TraerPorIdPost($request, $response, $args) {
            if(isset($_POST['id'])){
                $id=$_POST['id'];
                $rta =ClienteApi::TraerClientePorId($id);
                echo $rta;
            }
            else {
                echo "falta el id";
            }
        }
        public function AgregarPedidoACliente($idCliente, $idPedido) {

            $respuesta = ClienteApi::getArrayPedidosRealizados($idCliente);
            array_push($respuesta,$idPedido);
            $auxStringData = implode(",",$respuesta);

            //echo $auxStringData;
            $query2 = "UPDATE `clientes` SET `pedidos`= '$auxStringData'  WHERE id = $idCliente"; 
            $resultado2 =  metodoPut($query2);
            // exit(true);
        }
        public function getArrayPedidosRealizados($idCliente){
            // $idint = intval($idPedido);
            // echo $idint;
            $query = "SELECT `pedidos` FROM `clientes` WHERE `id` = $idCliente";
            $resultado = metodoGet($query);
            header("HTTP/1.1 200 OK");

            $unformatArray = $resultado->fetch(PDO::FETCH_ASSOC);
  
            $array = ClienteApi::FormatArray($unformatArray);
            return $array;

        } 
        public function FormatArray($stringInfo){
            // var_dump($stringInfo);
            $stringInfo["pedidos"];
                $arrayFormat = explode(",", $stringInfo["pedidos"]);
                return $arrayFormat;
        }     


        public function getClienteConPedidosbyEmail($request, $response, $args){
            if(isset($_POST['mail'])){
                $mail=$_POST['mail'];
                $rta =ClienteApi::TraerClientePorMail($mail);
                
                if(strcmp ($rta , "false" ) != 0){
                    $rtaJson = json_decode($rta);
                     $idCLI = (int)$rtaJson->id;
                    
                     $pedidos = ClienteApi::getArrayPedidosRealizados(($idCLI));
                    //  var_dump($pedidos);
                    //  var_dump(count($pedidos));
                    $pedidosCliente = [];
                     for($i=1;$i<count($pedidos);$i++){
                         
                         $rta2 = PedidosApi::TraerUnobyIdPedido($pedidos[$i]);
                         $aux = json_decode($rta2, true);
                        //  var_dump($aux["DireccionLlegada"]);
                        //  var_dump((int)$aux->DireccionOrigen);
                         $dataDireccLLegada = json_decode(Direcciones::TraerDireccionbyId((int)$aux["DireccionLlegada"]),true);
                         $dataDireccOrigen = json_decode(Direcciones::TraerDireccionbyId((int)$aux["DireccionOrigen"]),true);
                         // var_dump($aux);
                        //  var_dump($dataDireccLLegada);
                         $aux["DireccionLlegadaInfo"] = $dataDireccLLegada;
                         $aux["DireccionOrigenInfo"] = $dataDireccOrigen;
                           array_push($pedidosCliente,$aux); 

                     }
                     $rtaJson->pedidosCliente = $pedidosCliente;

                    //  var_dump($pedidosCliente);
                    //  var_dump($rtaJson);}
                     echo json_encode($rtaJson);
                }   
                else {
                    echo "No existe ese email";
                 }
                
            }
            else{
                echo "no tiene mail para valdiar";
            }

        }

        public function setearCalificacionCliente($idCliente, $calif){
             
            $query = "UPDATE `clientes` SET `calificacion`= $calif WHERE `id`= $idCliente";
            $resultado = metodoPut($query);
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
            exit();
 
         }

    }
    ?>
    