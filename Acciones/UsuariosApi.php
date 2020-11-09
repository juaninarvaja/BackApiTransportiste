  
<?php
include  "ClienteApi.php";
include  "TRansportistaApi.php";

    class UsuariosApi{        

        // Retorna array json de todos los elementos.
        public function TraerTodos($request, $response, $args) {

            if($_SERVER['REQUEST_METHOD']=='GET'){
                //si contiene un id es a un solo registro
                if(isset($_GET['email'])){
                    $query="select * from usuarios where email=".$_GET['email'];
                    $resultado=metodoGet($query);
                    echo json_encode($resultado->fetch(PDO::FETCH_ASSOC));
                }else{ //sino a todos
                    $query="select * from usuarios";
                    $resultado=metodoGet($query);
                    $JsonRta =json_encode($resultado->fetchAll());
                    $array = json_decode($JsonRta,true); 
                    // var_dump($array);
                    for($i= 0; $i<count($array);$i++){
                        if($array[$i]["tipoUsuario"] == "cliente"){
                            $dataCliente =  json_decode(ClienteApi::TraerClientePorMail($array[$i]["email"]),true);
                                 $array[$i]["dataCliente"] =  $dataCliente;
  


                        }
                        else if($array[$i]["tipoUsuario"] == "transportista"){
                            $dataCliente =  json_decode(ClienteApi::TraerClientePorMail($array[$i]["email"]),true);
                            $array[$i]["dataCliente"] =  $dataCliente;
                            $dataTransp =  json_decode(TransportistaApi::TraerTransportPorMail($array[$i]["email"]),true);
                            $array[$i]["dataTransp"] =  $dataTransp;
                            
                        }
                    }
                }
                echo json_encode($array);
                header("HTTP/1.1 200 OK");
                exit();
            }
        }
        public function SubirUno($request, $response, $args){
                // if($_POST['METHOD']=='POST'){
                unset($_POST['METHOD']);
                $tipouser=$_POST['tipoUsuario'];
                $mail=$_POST['email'];
                $contrasenia=$_POST['contrasenia'];
                if($tipouser == "admin") {
                    $query="INSERT INTO `usuarios` (`tipoUsuario`, `email`, `contrasenia`) VALUES ('$tipouser', '$mail', '$contrasenia')";
                    $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
                    $resultado=metodoPost($query, $queryAutoIncrement);
                    echo json_encode($resultado);
                    header("HTTP/1.1 200 OK");
                    exit();
                }
                else if($tipouser == "cliente"){
                    $query="INSERT INTO `usuarios` (`tipoUsuario`, `email`, `contrasenia`) VALUES ('$tipouser', '$mail', '$contrasenia')";
                    $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
                    $resultado=metodoPost($query, $queryAutoIncrement);
         
                    $idAuth = $resultado['id'];
                    // echo json_encode($resultado);
                    $query2 = "INSERT INTO `clientes`( `viajes`, `pedidos`, `calificacion`, `idAuth`, `email`  ) VALUES ('[{}]','[{}]',0,$idAuth,'$mail')";
                    $queryAutoIncrement2="select MAX(id) as id from clientes";
                    $resultado=metodoPost($query2, $queryAutoIncrement2);
                    echo json_encode($resultado);
                   
                    exit();
                 
                }
                else if($tipouser == "transportista") {
                    if($_POST['papeles']){
                        $query="INSERT INTO `usuarios` (`tipoUsuario`, `email`, `contrasenia`) VALUES ('$tipouser', '$mail', '$contrasenia')";
                        $queryAutoIncrement="select MAX(idAuth) as id from usuarios";
                        $resultado=metodoPost($query, $queryAutoIncrement);
             
                        $idAuth = $resultado['id'];
                        // echo json_encode($resultado);
                        $query2 = "INSERT INTO `clientes`( `viajes`, `pedidos`, `calificacion`, `idAuth`,`email` ) VALUES ('[{}]','[{}]',0,$idAuth,'$mail')";
                        $queryAutoIncrement2="select MAX(id) as id from clientes";
                        $resultado2=metodoPost($query2, $queryAutoIncrement2);
                        // echo json_encode($resultado2);
                $papeles = $_POST['papeles'];
             
                        $query3 = "INSERT INTO `transportistas`(`habilitado`, `papeles`, `calificacion`, `idAuth`,`email`) VALUES 
                        (0,'$papeles',0,$idAuth,'$mail')";
    
                        $queryAutoIncrement3="select MAX(idTransportista) as id from transportistas";
                        
                        $resultado3=metodoPost($query3, $queryAutoIncrement3);
                        echo json_encode($resultado3);
                        exit();
                    }
                    else{
                        echo "no tiene papeles";
                    }
 
                    //da de alta en tablea trasnportista
                    //da de alta en cliente
                }

            // }
        }
       public function ActualizarUno($request, $response, $args){
        //    echo("entro a actualizar uno");
             $data = $request->getParsedBody();
             $id = isset($data["idAuth"])?$data["idAuth"]:null;
             $tipouser= isset($data["tipoUsuario"])?$data["tipoUsuario"]:null;
             $mail= isset($data["email"])?$data["email"]:null;
             $contrasenia= isset($data["contrasenia"])?$data["contrasenia"]:null;

            $query = "UPDATE `usuarios` SET `tipoUsuario`= '$tipouser',`email`='$mail',`contrasenia`='$contrasenia' WHERE `idAuth`= $id";
            $resultado = metodoPut($query);
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
            exit();
       }
       
       public function BorrarById($request, $response, $args){
         
        if($_POST['METHOD']=='DELETE'){
            unset($_POST['METHOD']);
            $id=$_POST['id'];
            $query="DELETE FROM usuarios WHERE idAuth='$id'";
            $resultado=metodoDelete($query);
            echo json_encode($resultado);
            header("HTTP/1.1 200 OK");
            exit();
        }
       }

 

    }
?>
