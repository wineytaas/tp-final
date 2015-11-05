<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'alunoDao.php';


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');

$app->get('/alunos/:id', function ($id) {
    //recupera o cliente
    $aluno = AlunoDao::getAlunoById($id);
    
    echo json_encode($aluno);
});

$app->get('/alunos', function() {
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

$app->run();
