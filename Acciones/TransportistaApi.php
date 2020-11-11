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

    }
    ?>
    