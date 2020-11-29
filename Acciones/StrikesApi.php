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
    function cuantoStrikeTieneMail($mail){
        $auxTransp= TransportistaApi::TraerTransportPorMail($mail);
        //var_dump($auxTransp);
        if($auxTransp != "false"){
            $array = json_decode($auxTransp,true);
            $idTransp = (int)$array["idTransportista"];
        $query="SELECT * FROM `strikes` WHERE idTransportista = $idTransp";

        $resultado = metodoGet($query);
       // header("HTTP/1.1 200 OK");
      
        $rta = json_encode($resultado->fetchAll());
        $array = json_decode($rta,true);
        return count($array);
       // $rtaArray = json_decode($rta);
       // return $rtaArray;
        }
        else return 0;
   }
    function subirStrike($idTransportista,$idViaje){
        
        $query="INSERT INTO `strikes`(`idTransportista`, `idViaje`) VALUES ($idTransportista,$idViaje)";
      //   echo $query;
      $queryAutoIncrement="select MAX(idPedido) as id from pedido";
      $resultado=metodoPost($query, $queryAutoIncrement);
      
      return json_encode($resultado);
    
    }
    function deleteAllStrikes($mail){
        $auxTransp= TransportistaApi::TraerTransportPorMail($mail);
        //var_dump($auxTransp);
        if($auxTransp != "false"){
            $array = json_decode($auxTransp,true);
            $idTransp = (int)$array["idTransportista"];
        $query="DELETE FROM `strikes` WHERE idTransportista = $idTransp";
      //   echo $query;
      $resultado=metodoDelete($query);
      return $resultado;
      
      //return json_encode($resultado);
        }    
    }
    function cantidadPorMail($request, $response, $args){
        
        if(isset($_POST['mail'])){
            $mail=$_POST['mail'];

            $auxTransp= TransportistaApi::TraerTransportPorMail($mail);
            //var_dump($auxTransp);
            if($auxTransp != "false"){
                $array = json_decode($auxTransp,true);
                $idTransp = (int)$array["idTransportista"];
                $query="SELECT * FROM `strikes` WHERE idTransportista = $idTransp";

                $resultado = metodoGet($query);
            // header("HTTP/1.1 200 OK");
            
                $rta = json_encode($resultado->fetchAll());
                $array = json_decode($rta,true);
                $cantidad = count($array);
               

                 echo  $cantidad;

            }
            else{
                echo "mail no valido";
            }

        }
        else{
            echo "no me llega el mail";
        }
   } 
    

}   

?>