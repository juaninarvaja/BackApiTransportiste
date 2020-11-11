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
            // echo "data dentro de function";
            // echo $idCliente;
            // echo $idPedido;
        }

    }
    ?>
    