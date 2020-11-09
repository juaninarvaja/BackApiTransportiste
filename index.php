<?php
include 'BD/bd.php';
include_once "Acciones/UsuariosApi.php";
include_once "Acciones/PedidosApi.php";
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
header('Access-Control-Allow-Origin: *'); 

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;
$app = new \Slim\App(['settings' => $config]);    

$app->group('/usuarios', function () {
    $this->get('/', \UsuariosApi::class . ':TraerTodos');
    $this->post('/', \UsuariosApi::class . ':SubirUno');
    $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});
$app->group('/pedidos', function () {
    $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/', \PedidosApi::class . ':SubirUno');
    // $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    // $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});


$app->run();
?>