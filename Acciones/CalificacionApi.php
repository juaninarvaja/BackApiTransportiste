<?php


class CalificacionApi{

   //si mando un viaje, me calcula el idCliente del viaje y el idTransportista
   //recibo el tipo de cliente q soy
   //La calificacion valida que exista el idviaje que recibe 
   //en estado "finalizado"
      //si existe ya ese registro deja puntuar a la parte que falta
   //si no existe crea el registro en la bd y puntua el que realizo este pedido

   //___________


   //al termianar esto, deberia recolectar los idcliente y idtransportista desde el id viaje, 
   //y luego iterar en la base de datos para poder traer
   //la cantidad de notas que tiene cada uno y sus valores, con estos saca promedio
   //y lo setea en su tabla "transportista" o "cliente" segun corresponda
   public function Calificar($request, $response, $args) {
      if(isset($_POST['idViaje']) && isset($_POST['calificacion']) && isset($_POST['tipo']) && ($_POST['tipo'] == "transportista" || $_POST['tipo'] == "cliente")){
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
                  echo "tengo q updatear";
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
          echo "falta el id o el tipo (debe ser cliente o transportista) o la calificacion";
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
         echo json_encode($resultado);
         header("HTTP/1.1 200 OK");
         exit();
      }
      else if ($tipo == "transportista"){
         $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlCliente`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
         $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
         $resultado=metodoPost($query,$queryAutoIncrement);
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
        echo json_encode($resultado);
        header("HTTP/1.1 200 OK");

        exit();
      }
      else if ($tipo == "transportista"){
         $query = "UPDATE `calificaciones` SET `puntuacionAlCliente`= $calificacion WHERE idViaje = $idViaje";
         // $query="INSERT INTO `calificaciones`(`idViaje`, `puntuacionAlTransportista`, `idTransportista`, `idCliente`) VALUES ($idViaje,$calificacion,$idTransportista,$idCliente);";
         $resultado = metodoPut($query);
         
         header("HTTP/1.1 200 OK");
         CalificacionApi::calcularPromedioTransportista($idTransportista);
         echo json_encode($resultado);
         exit();
      }

   }
   public function calcularPromedioTransportista($idTransportista){
      $query="SELECT `puntuacionAlTransportista` FROM `calificaciones` WHERE idTransportista= $idTransportista";

      $resultado = metodoGet($query);
      header("HTTP/1.1 200 OK");
     
      //$rta = $resultado->fetch(PDO::FETCH_ASSOC);
      $JsonRta = json_encode($resultado->fetchAll());
     // $array = json_decode($JsonRta,true);
      //$auxArray = array();
      var_dump($JsonRta);
      //PQ me duevuleve solo una?
   }
   
    
}   

?>
