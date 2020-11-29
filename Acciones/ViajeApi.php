<?php


class ViajeApi{
    //para crear un viaje tiene q llegar un idpedido, una idpropuesta, un idtransportista, y se setea en estado "Viaje pactado" ;
    //tengo q validar q haya uno solo por idPedido
    //el idViaje es autoincremental
    public function traerViajesPorIdPedido($request, $response, $args){  
        if(isset($_POST['idPedido'])){
            $idPedido=$_POST['idPedido'];
            $respuesta = " ";
            $query="SELECT * FROM `viajes` WHERE idPedido = $idPedido";
            
            $resultado = metodoGet($query);
            
            $JsonRta = json_encode($resultado->fetchAll());
            // $array = json_decode($JsonRta,true);
          
            //return $JsonRta;
           
            $array = json_decode($JsonRta,true);
          
            //faltaria traer la info del transportista tambiern 
            //echo count($array);
           
            for($i = 0;$i<count($array);$i++){
                // var_dump((int)$array[$i]["idTransportista"]);
                if(isset($array[$i]["idTransportista"]) && isset($array[$i]["idPropuesta"]) &&  isset($array[$i]["idPedido"])){

                    // $idTranpo = $array[$i]["idTransportista"];
                    //  $infoTransp = json_decode(TransportistaApi::ExisteTransportistaId($idTranpo),true);
                    //  $array[$i]["infoTransp"] = $infoTransp;

                     $idPropuesta = $array[$i]["idPropuesta"];
                     $infoPropuesta = json_decode(PropuestaApi::existePropuestaPorId($idPropuesta),true);
                     $array[$i]["infoPropuesta"] = $infoPropuesta;

                     $idPedido= $array[$i]["idPedido"];
                     $infoPedido = json_decode(PedidosApi::TraerUnobyIdPedido($idPedido),true);
                     $array[$i]["infoPedido"] = $infoPedido;

                     $DireccionOrigen = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionOrigen"]),true);
                     $DireccionDestino = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionLlegada"]),true);
                     $array[$i]["DireccionOrigen"] =   $DireccionOrigen ;
                     $array[$i]["DireccionLlegada"] =  $DireccionDestino;

                     $idCliente= $infoPedido["idCliente"];
                     $idCliente = json_decode(ClienteApi::TraerClientePorId($idCliente),true);
                     $array[$i]["infoCliente"] = $idCliente;
                    // TraerUnobyIdPedido
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
        else {
            echo "falta el idPedido";
        }


    }
    public function traerViajesPorIdTransportista($request, $response, $args){  
        if(isset($_POST['idTransportista'])){
            $idTransportista=$_POST['idTransportista'];
            $respuesta = " ";
            $query="SELECT * FROM `viajes` WHERE idTransportista = $idTransportista";
            
            $resultado = metodoGet($query);
            
            $JsonRta = json_encode($resultado->fetchAll());

            $array = json_decode($JsonRta,true);
    
           
            for($i = 0;$i<count($array);$i++){
                // var_dump((int)$array[$i]["idTransportista"]);
                if(isset($array[$i]["idPedido"])){


                     $idPedido= $array[$i]["idPedido"];
                     $infoPedido = json_decode(PedidosApi::TraerUnobyIdPedido($idPedido),true);
                     $array[$i]["infoPedido"] = $infoPedido;
                     $idCliente= $infoPedido["idCliente"];
                     $idCliente = json_decode(ClienteApi::TraerClientePorId($idCliente),true);
                     $array[$i]["infoCliente"] = $idCliente;

                     
                }
                // echo "llego aca";
                $respuesta =json_encode($array,true);
               

            }
            header("HTTP/1.1 200 OK");
            return $respuesta;
        }
        else {
            echo "falta el idTransportista";
        }
    }
    public function  traerViajesPorMailTransportista($request, $response, $args){  
        if(isset($_POST['email'])){
            $email=$_POST['email'];
            $infoTta = TransportistaApi::TraerTransportPorMail($email);
                    
            if($infoTta == "false" || $infoTta == false){
                echo "ese mail no existe";
            }
            else{
                $array = json_decode($infoTta,true);
            //var_dump($array["idTransportista"]);
                //echo "ese mail si existe";
                  $idTransportista = $array["idTransportista"];
                  $respuesta = " ";
                  $query="SELECT * FROM `viajes` WHERE idTransportista = $idTransportista";
                  
                  $resultado = metodoGet($query);
                  
                  $JsonRta = json_encode($resultado->fetchAll());
      
                  $array = json_decode($JsonRta,true);
          
                 
                  for($i = 0;$i<count($array);$i++){
                      // var_dump((int)$array[$i]["idTransportista"]);
                      if(isset($array[$i]["idPedido"])){
      
      
                           $idPedido= $array[$i]["idPedido"];
                           $infoPedido = json_decode(PedidosApi::TraerUnobyIdPedido($idPedido),true);
                           $array[$i]["infoPedido"] = $infoPedido;
                           //
                           $idCliente= $infoPedido["idCliente"];
                           $idCliente = json_decode(ClienteApi::TraerClientePorId($idCliente),true);
                           $array[$i]["infoCliente"] = $idCliente;
                           //
                           $idPropuesta= $array[$i]["idPropuesta"];
                           $infoPropuesta = json_decode(PropuestaApi::existePropuestaPorId($idPropuesta),true);
                           $array[$i]["infoPropuesta"] = $infoPropuesta;
                           //
                            $DireccionOrigen = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionOrigen"]),true);
                            $DireccionDestino = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionLlegada"]),true);
                            $array[$i]["DireccionOrigen"] =   $DireccionOrigen ;
                            $array[$i]["DireccionLlegada"] =  $DireccionDestino;
      
                           
                      }
                      // echo "llego aca";
                      $respuesta =json_encode($array,true);
                     
      
                  }
                  header("HTTP/1.1 200 OK");
                  return $respuesta;
            }


 
        }
        else {
            echo "falta el mail del tranp";
        }
    }
   
    public function   traerViajesPorMailCliente($request, $response, $args){  
        if(isset($_POST['email'])){
            $email=$_POST['email'];
            $infoCli = ClienteApi::TraerClientePorMail($email);
                    
            if($infoCli == "false" || $infoCli == false){
                echo "ese mail no existe";
            }
            else{
                $array = json_decode($infoCli,true);
            //var_dump($array["idTransportista"]);
                //echo "ese mail si existe";
                $idCli = $array["id"];
                $arrayIdPedidos = PedidosApi::traerArrayIdPedidosDeUnIdCliente($idCli);
                $respuesta = " ";
                //var_dump(count($arrayIdPedidos));
                if(count($arrayIdPedidos)>0){
                    //var_dump($firstIdPedido);
           
                    //var_dump($firstIdPedido);
                    $query="SELECT * FROM `viajes` WHERE ";
                for($i=0;$i<count($arrayIdPedidos);$i++){
                    if($i!=0){
                        $query =$query . " OR ";
                    }
                    $auxIdPedido = $arrayIdPedidos[$i]["idPedido"];
                    $auxFinalQuery = "idPedido = $auxIdPedido";
                    $query = $query . $auxFinalQuery;
                    
                }
                    $resultado = metodoGet($query);
                    
                    $JsonRta = json_encode($resultado->fetchAll());
        
                    $array = json_decode($JsonRta,true);
                    $auxArray = [];
                   
                    for($i = 0;$i<count($array);$i++){
                         //var_dump($array[$i]["estado"]);
                        if($array[$i]["estado"] == "Finalizado" || $array[$i]["estado"] == "Cancelado por Transportista" || $array[$i]["estado"] == "Cancelado por Cliente"){
                           //echo "entro";
                            //unset($array[$i]);
                            //var_dump($array);
                        }
                        else if(isset($array[$i]["idPedido"])){
        
        
                             $idPedido= $array[$i]["idPedido"];
                             $infoPedido = json_decode(PedidosApi::TraerUnobyIdPedido($idPedido),true);
                             $array[$i]["infoPedido"] = $infoPedido;
  
                            //  $idTransportista= $array[$i]["idTransportista"];
                            //  $infoTransp = json_decode(TransportistaApi::ExisteTransportistaId($idTransportista),true);
                            //  $array[$i]["infoTransp"] = $infoTransp;
                             //
                             $idPropuesta= $array[$i]["idPropuesta"];
                             $infoPropuesta = json_decode(PropuestaApi::existePropuestaPorId($idPropuesta),true);
                             $array[$i]["infoPropuesta"] = $infoPropuesta;
                             //
                             //var_dump($array[$i]["infoPedido"]);
                             $DireccionOrigen = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionOrigen"]),true);
                             $DireccionDestino = json_decode(Direcciones::TraerDireccionById($array[$i]["infoPedido"]["DireccionLlegada"]),true);
                             $array[$i]["DireccionOrigen"] =   $DireccionOrigen ;
                             $array[$i]["DireccionLlegada"] =  $DireccionDestino;
                            $auxArray[$i] = $array[$i];
                             
                        }
                        // echo "llego aca";
                        
                       
        
                    }
                    //var_dump($auxArray);
                    $respuesta =json_encode($auxArray,true);
                    // header("HTTP/1.1 200 OK");
                    // $respuesta =json_encode($array,true);
                    header("HTTP/1.1 200 OK");
                   return $respuesta;
                }
                }
            

            }
        else {
            echo "falta el mail del cliente";
        }
    }



    public function generarViaje($request, $response, $args){
        if(isset($_POST['idPedido']) && isset($_POST['idPropuesta']) && isset($_POST['idTransportista'])){
            $idPedido=$_POST['idPedido'];
            $idPropuesta=$_POST['idPropuesta'];
            $idTransportista=$_POST['idTransportista'];
        
            $pedidoExiste =  PedidosApi::TraerUnobyIdPedido($idPedido);
            
            $IdPropuestaEsViaje = ViajeApi::yaExisteUnViajeParaIdPropuesta($idPropuesta);
            $transpExiste = TransportistaApi::ExisteTransportistaId($idTransportista);
         
            if($pedidoExiste != "false" && !$IdPropuestaEsViaje && $transpExiste != "false"){
                $query="INSERT INTO `viajes`(`idPedido`, `estado`, `idTransportista`, `idPropuesta`) VALUES 
                ($idPedido , 'Viaje Pactado', $idTransportista,'$idPropuesta')";
             $queryAutoIncrement="select MAX(idViaje) as id from viajes";
             $update = PedidosApi::updateEstadoPedidoById((int)$idPedido,"es viaje");
             $resultado=metodoPost($query, $queryAutoIncrement);
             header("HTTP/1.1 200 OK");
             return $resultado["id"];
            
            }
            else{
                echo "no exsite ese idPedido o ese IdPropuesta ya es un viaje o ese idTransportista no existe";
            }

        }
        else{
            echo "no estas pasando todo lo q necesito";
        }
    }

    public function CambiarEstadoViaje($request, $response, $args){
        if(isset($_POST['idViaje']) && isset($_POST['estado'])){
             $idViaje=$_POST['idViaje'];
             $estado=$_POST['estado'];
            // $idPropuesta=$_POST['idPropuesta'];
            // $idTransportista=$_POST['idTransportista'];
        
         
                if(ViajeApi::existeViaje($idViaje)){
                    $query2 = "UPDATE `viajes` SET `estado`= '$estado'  WHERE idViaje = $idViaje"; 
                    $resultado2 =  metodoPut($query2);
                    header("HTTP/1.1 200 OK");
                    return json_encode($resultado2);
                }
                else{
                    echo "ese viaje no existe";
                }


            
            }
            else{
               echo "no estas pasando todo lo q necesito";
            }

    }

    public function CambiarEstadoByIdViaje($idViaje, $estado){

                if(ViajeApi::existeViaje($idViaje)){
                    $query2 = "UPDATE `viajes` SET `estado`= '$estado'  WHERE idViaje = $idViaje"; 
                    $resultado2 =  metodoPut($query2);
                    header("HTTP/1.1 200 OK");
                    return json_encode($resultado2);
                }
                else{
                    return false;
                    //echo "ese viaje no existe";
                }
        

    }
    public function cancelarViaje($request, $response, $args){
        if(isset($_POST['idViaje']) && isset($_POST['tipo'])){
             $idViaje=$_POST['idViaje'];
             $tipo=$_POST['tipo'];
            // $idPropuesta=$_POST['idPropuesta'];
            // $idTransportista=$_POST['idTransportista'];
           // var_dump(ViajeApi::existeViaje($idViaje));
         
                if(ViajeApi::existeViaje($idViaje)){
                    if($tipo == "cliente"){
                        $query2 = "UPDATE `viajes` SET `estado`= 'Cancelado por Cliente'  WHERE idViaje = $idViaje"; 
                        $resultado2 =  metodoPut($query2);
                        header("HTTP/1.1 200 OK");
                        return json_encode($resultado2);
                    }
                    else if($tipo == "transportista"){
                        //echo "es transportista";
                        $existeEseStrike = StrikesApi::eseidViajeYaEsStrike($idViaje);
                        if($existeEseStrike == false){
                            $idTransportista = ViajeApi::getTransportistaByIdViaje($idViaje);
                            $strickesActuales = StrikesApi::cuantoStrikeTiene($idTransportista);
                            //var_dump($strickesActuales);
                            if($strickesActuales < 3){
                                $subioStrike = StrikesApi::subirStrike($idTransportista,$idViaje);
                                //var_dump($subioStrike);
                                $query2 = "UPDATE `viajes` SET `estado`= 'Cancelado por Transportista'  WHERE idViaje = $idViaje"; 
                                $resultado2 =  metodoPut($query2);
                                //header("HTTP/1.1 200 OK");
                                //return json_encode($resultado2);
                                $strickesActuales = StrikesApi::cuantoStrikeTiene($idTransportista);
                                echo $strickesActuales;
                                if($strickesActuales == 3){
                                    TransportistaApi::DesHabilitarByIdTRansp($idTransportista);
                                }
                            }else{
                                echo "ya tenes 3 strikes, que haces aca todavia?";
                            }
                            
      
                        //informa la cantidad de strickes q tiene ahora
                        //si tiene 3 lo doy de baja
                        }
                        else{
                            echo "ya tiene un strike asignado ese idViaje";
                        }
                        
 
                        
                    }
                    else{
                        echo "El tipo debe ser cliente o transp";
                    }

                }
                else{
                    echo "ese viaje no existe";
                }


            
            }
            else{
               echo "no estas pasando todo lo q necesito";
            }

    }
    

    function yaExisteUnViajeParaIdPropuesta($idPropuesta){
        $query="SELECT `idPedido`, `estado`, `idViaje`, `idTransportista`, `idPropuesta` FROM `viajes` WHERE idPropuesta = $idPropuesta";
        $resultado = metodoGet($query);
        header("HTTP/1.1 200 OK");
        if(json_encode($resultado->fetch(PDO::FETCH_ASSOC)) == "false"){
            return false;
        }
        else{
            return true;
        }
        
    }
    function existeViaje($idViaje){
        $query="SELECT `idPedido`, `estado`, `idViaje`, `idTransportista`, `idPropuesta` FROM `viajes` WHERE idViaje = $idViaje";
        $resultado = metodoGet($query);
        header("HTTP/1.1 200 OK");
        if(json_encode($resultado->fetch(PDO::FETCH_ASSOC)) == "false"){
            return false;
        }
        else{
            return true;
        }
        
    }

   function getIdPedidoByIdViaje($idViaje){
       //var_dump($idViaje);
    $query="SELECT `idPedido` FROM `viajes` WHERE idViaje = $idViaje";
    $resultado = metodoGet($query);
    header("HTTP/1.1 200 OK");
   $rta =json_encode($resultado->fetch(PDO::FETCH_ASSOC));
   //var_dump($rta);
    if($rta == "false"){
    //     echo "entro a if";
         return false;
     }
     else{
    //     echo "entro a else";
    //     var_dump(json_encode($resultado->fetch(PDO::FETCH_ASSOC)));
    return $rta;
    }
   }


    function getIdClienteByIdViaje($idViaje){
        $idPedido = ViajeApi::getIdPedidoByIdViaje($idViaje);
        if($idPedido!= false){
              
                $idPed = json_decode($idPedido);
                $idPedEste = (int) $idPed->idPedido;
                $query="SELECT `idCliente` FROM `pedido` WHERE idPedido = $idPedEste";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
               
                $rta = json_encode($resultado->fetch(PDO::FETCH_ASSOC));
                $rtaArray = json_decode($rta);
                return (int)$rtaArray->idCliente;
            }  
        else {return false;}
    }

    function getTransportistaByIdViaje($idViaje){

                $query="SELECT `idTransportista` FROM `viajes` WHERE idViaje = $idViaje";
    
                $resultado = metodoGet($query);
                header("HTTP/1.1 200 OK");
               
                $rta = json_encode($resultado->fetch(PDO::FETCH_ASSOC));
                $rtaArray = json_decode($rta);
                return (int)$rtaArray->idTransportista;
            
    }

    function getEstadoByIdViaje($idViaje){

        $query="SELECT `estado` FROM `viajes` WHERE idViaje = $idViaje";

        $resultado = metodoGet($query);
        header("HTTP/1.1 200 OK");
       
        $rta = json_encode($resultado->fetch(PDO::FETCH_ASSOC));
        $rtaArray = json_decode($rta);
        return $rtaArray->estado;
    
}

    


}   

?>
