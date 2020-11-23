<?php


class StrikesApi{


    function eseidViajeYaEsStrike($idViaje){
        $query="SELECT * FROM `strikes` WHERE idViaje = $idViaje";

        $resultado = metodoGet($query);
        header("HTTP/1.1 200 OK");
       
        $rta = json_encode($resultado->fetch(PDO::FETCH_ASSOC));
        $rtaArray = json_decode($rta);
        return $rtaArray;
    
    }
    
    function cuantoStrikeTiene($idTransportista){
         $query="SELECT * FROM `strikes` WHERE idTransportista = $idTransportista";

         $resultado = metodoGet($query);
        // header("HTTP/1.1 200 OK");
       
         $rta = json_encode($resultado->fetchAll());
         $array = json_decode($rta,true);
         return count($array);
        // $rtaArray = json_decode($rta);
        // return $rtaArray;
    
    }
    function subirStrike($idTransportista,$idViaje){
        
        $query="INSERT INTO `strikes`(`idTransportista`, `idViaje`) VALUES ($idTransportista,$idViaje)";
      //   echo $query;
      $queryAutoIncrement="select MAX(idPedido) as id from pedido";
      $resultado=metodoPost($query, $queryAutoIncrement);
      
      return json_encode($resultado);
    
    }

}   

?>