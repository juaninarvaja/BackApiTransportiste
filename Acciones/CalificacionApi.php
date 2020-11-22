<?php


class CalificacionApi{

   //si mando un viaje, me calcula el idCliente del viaje y el idTransportista
   //recibo el tipo de cliente q soy
   //La calificacion valida que exista el idviaje que recibe 
   //en estado "finalizado"
      //si existe ya ese registro deja puntuar a la parte que falta
   //si no existe crea el registro en la bd y puntua el que realizo este pedido

   //al termianar esto, deberia recolectar los idcliente y idtransportista desde el id viaje, 
   //y luego iterar en la base de datos para poder traer
   //la cantidad de notas que tiene cada uno y sus valores, con estos saca promedio
   //y lo setea en su tabla "transportista" o "cliente" segun corresponda
   //___________
   //tengo q valiar q la puntuacion sea de 1 a 10

   public function Calificar($request, $response, $args) {
      if(isset($_POST['idViaje']) && isset($_POST['calificacion']) && isset($_POST['tipo']) && ($_POST['tipo'] == "transportista" || $_POST['tipo'] == "cliente")
       && $_POST['calificacion'] > 0 && $_POST['calificacion'] <= 10 ){
          $idViaje=$_POST['idViaje'];
          $tipo=$_POST['tipo'];
          $calificacion=$_POST['calificacion'];
         $idCliente = ViajeApi::getIdClienteByIdViaje((int)$idViaje);
         if($idCliente != false){
            $idTransportista = ViajeApi::getTransportistaByIdViaje((int)$idViaje);
            $estado =  ViajeApi::getEstadoByIdViaje((int)$idViaje);
           // echo $estado;
            if($estado == "Finalizado"){
              // echo "podes entrar pq esta en finalizado";
              $existeya = CalificacionApi::existeCalificacionParaIdViaje($idViaje);

              //var_dump($existeya);
               if($existeya){
                  //echo "tengo q updatear";
                  //update
                  CalificacionApi::UpdateUna($idViaje,$idCliente,$idTransportista,$tipo,$calificacion);
                  
                  //aca calculo el promedio nuevo y lo seteo
               }else{
                  //insert
                    CalificacionApi::SubirUna($idViaje,$idCliente,$idTransportista,$tipo,$calificacion);
                    //aca calculo el promedio nuevo y lo seteo
                  }

                  
               }
            else{
               echo "este pedido no esta finalizado";
            }

         }
         else{
            echo "no pude encontrar un id cliente para ese idViaje o ese idViaje no existe";
         }
         //  $rta =ClienteApi::TraerClientePorId($id);
         //  echo $rta;
        
      }
      else {
          echo "falta el id o el tipo (debe ser cliente o transportista) o la calificacion (debe estar entre 0 y 10)";
      }
  }

   public function existeCalificacionParaIdViaje($idViaje){
      $query="SELECT * FROM `calificaciones` WHERE idViaje = $idViaje";

      $resultado = metodoGet($query);
      header("HTTP/1.1 200 OK");
     
      $rta = json_encode($resultado->fetch(PDO::FETCH_ASSOC));
      if($rta != "false"){
         return true;
      }
      else{
         return false;
      }
   }

   public function SubirUna($idViaje,$idCliente,$idTransportista,$tipo,$calificacion){
      if($tipo == "cliente"){
         
         $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlTransportista`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
         $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
         $resultado=metodoPost($query,$queryAutoIncrement);
         $promedioT = CalificacionApi::calcularPromedioTransportista($idTransportista);
         TransportistaApi::setearCalificacionTransp($idTransportista,$promedioT);
         echo json_encode($resultado);
         header("HTTP/1.1 200 OK");
         exit();
      }
      else if ($tipo == "transportista"){
         $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlCliente`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
         $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
         $resultado=metodoPost($query,$queryAutoIncrement);
         $promedioC = CalificacionApi::calcularPromedioCliente($idCliente);
         ClienteApi::setearCalificacionCliente($idCliente,$promedioC);
         echo json_encode($resultado);
         header("HTTP/1.1 200 OK");
         exit();
      }

   }
   public function UpdateUna($idViaje,$idCliente,$idTransportista,$tipo,$calificacion){
      if($tipo == "cliente"){
        $query = "UPDATE `calificaciones` SET `puntuacionAlTransportista`= $calificacion WHERE idViaje = $idViaje";
        // $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlTransportista`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
        $resultado = metodoPut($query);
        $promedioT = CalificacionApi::calcularPromedioTransportista($idTransportista);
        TransportistaApi::setearCalificacionTransp($idTransportista,$promedioT);
      
        echo json_encode($resultado);
        header("HTTP/1.1 200 OK");

        exit();
      }
      else if ($tipo == "transportista"){
         $query = "UPDATE `calificaciones` SET `puntuacionAlCliente`= $calificacion WHERE idViaje = $idViaje";
         // $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlTransportista`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
         $resultado = metodoPut($query);
         $promedioC = CalificacionApi::calcularPromedioCliente($idCliente);
          ClienteApi::setearCalificacionCliente($idCliente,$promedioC);
        
       

         echo json_encode($resultado);
         header("HTTP/1.1 200 OK");
         exit();
      }

   }
   public function calcularPromedioTransportista($idTransportista){
      $query="SELECT `puntuacionAlTransportista` FROM `calificaciones` WHERE idTransportista= $idTransportista";

      $resultado = metodoGet($query);
      header("HTTP/1.1 200 OK");
     
      $JsonRta = json_encode($resultado->fetchAll());
      $array = json_decode($JsonRta,true);

      $contador = 0;
      $acumulador = 0;
      for($i=0;$i<count($array);$i++){
         if($array[$i]["puntuacionAlTransportista"]  != "0" || $array[$i]["puntuacionAlTransportista"]  != 0){
            $contador = $contador +1;
            $acumulador = $acumulador + (int)$array[$i]["puntuacionAlTransportista"];

         }
      }
      $promedio = $acumulador / $contador;
      return  bcdiv($promedio, '1', 2);;

   }

   public function calcularPromedioCliente($idCliente){
      $query="SELECT `puntuacionAlCliente` FROM `calificaciones` WHERE idCliente= $idCliente";

      $resultado = metodoGet($query);
      header("HTTP/1.1 200 OK");
     
      $JsonRta = json_encode($resultado->fetchAll());
      $array = json_decode($JsonRta,true);

      $contador = 0;
      $acumulador = 0;
      for($i=0;$i<count($array);$i++){
         if($array[$i]["puntuacionAlCliente"]  != "0" || $array[$i]["puntuacionAlCliente"]  != 0){
            $contador = $contador +1;
            $acumulador = $acumulador + (int)$array[$i]["puntuacionAlCliente"];

         }
      }
      $promedio = $acumulador / $contador;
      return  bcdiv($promedio, '1', 2);;

   }
   
   
    
}   

?>
