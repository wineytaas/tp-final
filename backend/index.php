<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'alunoDao.php';


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');


$app->post('/login', function() {
    
    $request = \Slim\Slim::getInstance()->request();
    // recupera todos os clientes
    $user = json_decode($request->getBody());
    if($user->tipo == "alunos") echo json_encode(AlunoDAO::getAlunoByLogin($user->login,$user->senha));
    if($user->tipo == "professores") echo json_encode(ProfessorDAO::getProfessorByLogin($user->login,$user->senha));
});

//-------------------------------------  PROPRIEDADES DO ALUNO  -------------------------------------
$app->get('/alunos', function() {
    // recupera todos os clientes
    $alunos = AlunoDAO::getAll();
    echo json_encode($alunos);
});

$app->get('/alunos/:id', function ($id) {
    //recupera o cliente
    $aluno = AlunoDAO::getAlunoById($id);
    
    echo json_encode($aluno);
});

$app->get('/alunos/:login/:senha', function ($login,$senha) {
    //recupera o cliente
    $aluno = AlunoDAO::getAlunoByLogin($login,$senha);
    
    echo json_encode($aluno);
});

$app->post('/alunos', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoUsuario = json_decode($request->getBody());
    $novoUsuario = AlunoDAO::addAluno($novoUsuario);

    echo json_encode($novoUsuario);
});

$app->put('/alunos/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $aluno = json_decode($request->getBody());
    $aluno = AlunoDAO::updateAluno($aluno, $id);

    echo json_encode($aluno );
});

$app->delete('/alunos/:id', function($id) {
    // exclui o cliente
    $isDeleted = AlunoDAO::deleteAluno($id);

    echo json_encode($isDeleted);
});

//-------------------------------------  PROPRIEDADES DO PROFESSOR  -------------------------------------

//ADD PROFESSOR
$app->post('/professores', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoProfessor = json_decode($request->getBody());
    $novoProfessor = ProfessorDAO::addProfessor($novoProfessor);

    echo json_encode($novoProfessor);
});

//
$app->get('/professores/:id', function ($id) {
    //recupera o cliente
    $professor = ProfessorDAO::getProfessorById($id);
    
    echo json_encode($professor);
});

$app->get('/professores/:login/:senha', function ($login,$senha) {
    //recupera o cliente
    $professor = ProfessorDAO::getProfessorByLogin($login,$senha);
    
    echo json_encode($professor);
});

$app->get('/professores', function () {
    //recupera o cliente
    $professor = ProfessorDAO::getAll();
    
    echo json_encode($professor);
});

$app->put('/professores/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $professor = json_decode($request->getBody());
    $professor = ProfessorDAO::updateProfessor($professor, $id);

    echo json_encode($professor );
});

$app->delete('/professores/:id', function($id) {
    // exclui o cliente
    $isDeleted = ProfessorDAO::deleteProfessor($id);

    echo json_encode($isDeleted);
});


//-------------------------------------  PROPRIEDADES DA SECRETARIA  -------------------------------------


$app->post('/secretarias', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novoProfessor = json_decode($request->getBody());
    $novoProfessor = ProfessorDAO::addProfessor($novoProfessor);

    echo json_encode($novoProfessor);
});


$app->get('/professores/:id', function ($id) {
    //recupera o cliente
    $professor = ProfessorDAO::getProfessorById($id);
    
    echo json_encode($professor);
});

$app->get('/professores/:login/:senha', function ($login,$senha) {
    //recupera o cliente
    $professor = ProfessorDAO::getProfessorByLogin($login,$senha);
    
    echo $professor;
});

$app->get('/professores', function () {
    //recupera o cliente
    $professor = ProfessorDAO::getAll();
    
    echo json_encode($professor);
});

$app->put('/professores/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $professor = json_decode($request->getBody());
    $professor = ProfessorDAO::updateProfessor($professor, $id);

    echo json_encode($professor );
});

$app->delete('/professores/:id', function($id) {
    // exclui o cliente
    $isDeleted = ProfessorDAO::deleteProfessor($id);

    echo $isDeleted;
});
$app->run();
