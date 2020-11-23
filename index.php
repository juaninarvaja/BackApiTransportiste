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
    $this->post('/delete/', \UsuariosApi::class . ':BorrarById');
});
$app->group('/pedidos', function () {
     $this->get('/', \PedidosApi::class . ':TraerTodos');
    //$this->get('/', \PedidosApi::class . ':TraerTodosTodos');
    $this->post('/', \PedidosApi::class . ':SubirUno');
    $this->post('/propuestas/', \PedidosApi::class . ':TraerPedidosConSusPropuestas');
    $this->post('/cancelar/', \PedidosApi::class . ':cancelarPedidoPorIdPedido');

    // $this->put('/', \UsuariosApi::class . ':ActualizarUno');
    // $this->post('/delete', \UsuariosApi::class . ':BorrarById');
});
$app->group('/propuesta', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/', \PropuestaApi::class . ':CotizarUno');
    $this->post('/TraerPorId/', \PropuestaApi::class . ':TraerPorId');
    $this->post('/TraerPorIdTransp/', \PropuestaApi::class . ':TraerPorIdTransp');
    $this->post('/TraerPorEmailTransp/', \PropuestaApi::class . ':TraerPorEmailTransp');
    $this->post('/cancelar/', \PropuestaApi::class . ':cancelarPropuestaByIdProp');
});
$app->group('/transp', function () {
    // $this->get('/', \PedidosApi::class . ':TraerTodos');
    $this->post('/mail/', \TransportistaApi::class . ':TraerPorMailPost');
    $this->post('/id/', \TransportistaApi::class . ':TraerPorIdPost');
    $this->post('/habilitar/', \TransportistaApi::class . ':HabilitarByEmail');
    $this->get('/solicitudesTransp/', \TransportistaApi::class . ':TraerNoHabilitados');
    $this->post('/estaHabilitado/', \TransportistaApi::class . ':TraerEstadoByMail');
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
    $this->post('/traerPorIdPedido/', \ViajeApi::class . ':traerViajesPorIdPedido');
    $this->post('/estado/', \ViajeApi::class . ':CambiarEstadoViaje');
    $this->post('/traerPorIdTransp/', \ViajeApi::class . ':traerViajesPorIdTransportista');
    $this->post('/traerPorMailTransp/', \ViajeApi::class . ':traerViajesPorMailTransportista');
    $this->post('/traerPorMailCliente/', \ViajeApi::class . ':traerViajesPorMailCliente');

});

// $app->group('/admin', function () {
//     // $this->get('/', \PedidosApi::class . ':TraerTodos');
//     $this->get('/solicitudesTransp/', \CalificacionApi::class . ':Calificar');

    
// });



$app->run();
?>