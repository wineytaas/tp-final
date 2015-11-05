<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'categoriaDAO.php';
require 'tarefaDAO.php';
require 'usuarioDAO.php';


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/usuarios/:id', function ($id) {
    //recupera o cliente
    $usuario = UsuarioDAO::getUsuarioByID($id);
    
    echo json_encode($usuario);
});

$app->get('/usuarios', function() {
    // recupera todos os clientes
    $usuarios = UsuarioDAO::getAll();
    echo json_encode($usuarios);
});

$app->post('/usuarios', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoUsuario = json_decode($request->getBody());
    $novoUsuario = UsuarioDAO::addUsuario($novoUsuario);

    echo json_encode($novoUsuario);
});

$app->put('/usuarios/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o cliente
    $usuario = json_decode($request->getBody());
    $usuario = UsuarioDAO::updateUsuario($usuario, $id);

    echo json_encode($usuario);
});

$app->delete('/usuarios/:id', function($id) {
    // exclui o cliente
    $isDeleted = UsuarioDAO::deleteUsuario($id);

    // verifica se houve problema na exclusão
    if ($isDeleted) {
        echo "{'message':'Usuario excluído'}";
    } else {
        echo "{'message':'Erro ao excluir usuario'}";
    }
});

//---------------------------CATEGORIA-----------------------------------------

$app->get('/categorias/:id', function ($id) {
    //recupera o cliente
    $categoria = CategoriaDAO::getCategoriaByID($id);
    
    echo json_encode($categoria);
});

$app->get('/categorias', function() {
    // recupera todos os clientes
    $categorias = CategoriaDAO::getAll();
    echo json_encode($categorias);
});

$app->post('/categorias', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoCategoria = json_decode($request->getBody());
    $novoCategoria = CategoriaDAO::addCategoria($novoCategoria);

    echo json_encode($novoCategoria);
});

$app->put('/categorias/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o cliente
    $categoria = json_decode($request->getBody());
    $categoria = CategoriaDAO::updateCategoria($categoria, $id);

    echo json_encode($categoria);
});

$app->delete('/categorias/:id', function($id) {
    // exclui o cliente
    $isDeleted = CategoriaDAO::deleteCategoria($id);

    // verifica se houve problema na exclusão
    if ($isDeleted) {
        echo "{'message':'Categoria excluída'}";
    } else {
        echo "{'message':'Erro ao excluir categoria'}";
    }
});

//---------------------------TAREFA-----------------------------------------

$app->get('/tarefas/:id', function ($id) {
    //recupera o cliente
    $tarefa = TarefaDAO::getTarefaByID($id);
    
    echo json_encode($tarefa);
});

$app->get('/tarefas', function() {
    // recupera todos os clientes
    $tarefas = TarefaDAO::getAll();
    echo json_encode($tarefas);
});

$app->post('/tarefas', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoTarefa = json_decode($request->getBody());
    $novoTarefa = TarefaDAO::addTarefa($novoTarefa);

    echo json_encode($novoTarefa);
});

$app->put('/tarefas/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o cliente
    $tarefa = json_decode($request->getBody());
    $tarefa = TarefaDAO::updateTarefa($tarefa, $id);

    echo json_encode($tarefa);
});

$app->delete('/tarefas/:id', function($id) {
    // exclui o cliente
    $isDeleted = TarefaDAO::deleteTarefa($id);

    // verifica se houve problema na exclusão
    if ($isDeleted) {
        echo "{'message':'Tarefa excluída'}";
    } else {
        echo "{'message':'Erro ao excluir tarefa'}";
    }
});

$app->run();
