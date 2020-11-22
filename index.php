<?php
include 'BD/bd.php';
include_once "Acciones/UsuariosApi.php";
include_once "Acciones/PedidosApi.php";
include_once "Acciones/PropuestaApi.php";
include_once "Acciones/ViajeApi.php";
include_once "Acciones/CalificacionApi.php";
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
    //$this->get('/', \PedidosApi::class . ':TraerTodosTodos');
    $this->post('/', \PedidosApi::class . ':SubirUno');
    $this->post('/propuestas/', \PedidosApi::class . ':TraerPedidosConSusPropuestas');

    // $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    // $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});
$app->group('/propuesta', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/', \PropuestaApi::class . ':CotizarUno');
    $this->post('/TraerPorId/', \PropuestaApi::class . ':TraerPorId');
    // $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    // $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});
$app->group('/transp', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/mail/', \TransportistaApi::class . ':TraerPorMailPost');
    $this->post('/id/', \TransportistaApi::class . ':TraerPorIdPost');

    // $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    // $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});
$app->group('/cliente', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/mail/', \ClienteApi::class . ':TraerPorMailPost');
    $this->post('/id/', \ClienteApi::class . ':TraerPorIdPost');
    $this->post('/pedidos/', \ClienteApi::class . ':getClienteConPedidosbyEmail');
    
});
$app->group('/calificar', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/', \CalificacionApi::class . ':Calificar');

    
});
$app->group('/viaje', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/', \ViajeApi::class . ':generarViaje');
    $this->post('/traerPorIdPedido', \ViajeApi::class . ':traerViajesPorIdPedido');
    $this->post('/estado', \ViajeApi::class . ':CambiarEstadoViaje');
    $this->post('/traerPorIdTransp', \ViajeApi::class . ':traerViajesPorIdTransportista');
    

    
});


$app->run();
?>