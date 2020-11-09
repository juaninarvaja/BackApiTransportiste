<?php


    class TransportistaApi{        

        // Retorna array json de todos los elementos.
        public function TraerTransportPorMail($mail) {
            $query="SELECT * FROM `transportistas` WHERE email = '$mail'";

            $resultado=metodoGet($query);
     
            header("HTTP/1.1 200 OK");
            return json_encode($resultado->fetch(PDO::FETCH_ASSOC));

        }

    }
    ?>
    