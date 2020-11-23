<?php


    class TransportistaApi{        

        // Retorna array json de todos los elementos.
        public function TraerTransportPorMail($mail) {
            $query="SELECT * FROM `transportistas` WHERE email = '$mail'";

            $resultado=metodoGet($query);
     
            header("HTTP/1.1 200 OK");
            return json_encode($resultado->fetch(PDO::FETCH_ASSOC));

            
        }
        public function ExisteTransportistaId($id) {
            $query="SELECT * FROM `transportistas` WHERE idTransportista = $id";

            $resultado=metodoGet($query);
     
            header("HTTP/1.1 200 OK");
            return json_encode($resultado->fetch(PDO::FETCH_ASSOC));

        }

        public function TraerPorMailPost($request, $response, $args) {
            if(isset($_POST['mail'])){
                $mail=$_POST['mail'];
                $rta =TransportistaApi::TraerTransportPorMail($mail);
                echo $rta;
            }   
            else {
                echo "falta el mail";
            }
        }
        public function TraerNoHabilitados($request, $response, $args) {
            $query="SELECT * FROM `transportistas` WHERE habilitado = 0";
                
            $resultado = metodoGet($query);
            
            echo json_encode($resultado->fetchAll());

        }
        

        public function  TraerPorIdPost($request, $response, $args) {
            if(isset($_POST['id'])){
                $id=$_POST['id'];
                $rta =TransportistaApi::ExisteTransportistaId($id);
                echo $rta;
            }
            else {
                echo "falta el id";
            }
        }

        public function setearCalificacionTransp($idTransp, $calif){
             
           $query = "UPDATE `transportistas` SET `calificacion`= $calif WHERE `idTransportista`= $idTransp";
           $resultado = metodoPut($query);
           echo json_encode($resultado);
           header("HTTP/1.1 200 OK");
           exit();

        }
        public function HabilitarByEmail($request, $response, $args){
            if(isset($_POST['mail'])){
                $mail=$_POST['mail'];
                $existe = TransportistaApi::TraerTransportPorMail($mail);
                if($existe != "false" && $existe != null){
                    $query = "UPDATE `transportistas` SET `habilitado`= 1 WHERE `email`= '$mail'";
                    $resultado = metodoPut($query);
                    echo json_encode($resultado);
                    header("HTTP/1.1 200 OK");
                    exit();
                }
                else {
                    echo "no existe ese mail";
                }

            }
            else {
                echo "mandame mail";
            } 
         }

         public function TraerEstadoByMail($request, $response, $args){
            if(isset($_POST['mail'])){
                $mail=$_POST['mail'];
                $existe = TransportistaApi::TraerTransportPorMail($mail);
                if($existe != "false" && $existe != null){
                    $query = "SELECT `habilitado` FROM `transportistas` WHERE  `email`= '$mail'";
                    $resultado = metodoGet($query);
                    echo json_encode($resultado->fetchAll());
                    //echo json_encode($resultado);
                    header("HTTP/1.1 200 OK");
                    exit();
                }
                else {
                    echo "no existe ese mail";
                }

            }
            else {
                echo "mandame mail";
            } 
         }
         
    }
    ?>
    