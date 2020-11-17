<?php


class ViajeApi{
    //para crear un viaje tiene q llegar un idpedido, una idpropuesta, un idtransportista, y se setea en estado "Viaje pactado" ;
    //tengo q validar q haya uno solo por idPedido
    //el idViaje es autoincremental

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
}   

?>
