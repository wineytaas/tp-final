<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'alunoDao.php';


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');


$app->post('/menu', function(){
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $answer = new stdClass();
    $menu = array();
    $optLogout = new stdClass();
    $optLogout->descricao = "Deslogar";
    $optLogout->url = "/logout";
    $menu[] = $optLogout;
    $answer->auth_key = $authorization;
});

$app->post('/login', function() {
    
    $request = \Slim\Slim::getInstance()->request();
    // recupera todos os clientes
    $user = json_decode($request->getBody());
    if($user->tipo == "alunos") echo json_encode(AlunoDAO::getAlunoByLogin($user->login,$user->senha));
    if($user->tipo == "professores") echo json_encode(ProfessorDAO::getProfessorByLogin($user->login,$user->senha));
    if($user->tipo == "secretarias") echo json_encode(SecretariaDAO::getSecretariaByLogin($user->login,$user->senha));
});

//-------------------------------------  PROPRIEDADES DO ALUNO  -------------------------------------
$app->get('/alunos', function() {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    // recupera todos os clientes
    $alunos = AlunoDAO::getAll();
    $cl = new stdClass();
    $cl->alunos = $alunos;
    $cl->auth_key = $authorization;
    echo json_encode($cl);
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
    $novaSecretaria = json_decode($request->getBody());
    $novaSecretaria = SecretariaDAO::addSecretaria($novaSecretaria);

    echo json_encode($novaSecretaria);
});


$app->get('/secretarias/:id', function ($id) {
    //recupera o cliente
    $professor = SecretariaDAO::getSecretariaById($id);
    
    echo json_encode($professor);
});

$app->get('/secretarias/:login/:senha', function ($login,$senha) {
    //recupera o cliente
    $secretaria = SecretariaDAO::getSecretariaByLogin($login,$senha);
    
    echo json_encode($secretaria);
});

$app->get('/secretarias', function () {
    //recupera o cliente
    $secretaria = SecretariaDAO::getAll();
    
    echo json_encode($secretaria);
});

$app->put('/secretarias/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $secretaria = json_decode($request->getBody());
    $secretaria = SecretariaDAO::updateSecretaria($secretaria, $id);

    echo json_encode($secretaria );
});

$app->delete('/secretarias/:id', function($id) {
    // exclui o cliente
    $isDeleted = SecretariaDAO::deleteSecretaria($id);

    echo json_encode($isDeleted);
});

//-------------------------------------  PROPRIEDADES DA SECRETARIA  -------------------------------------


$app->post('/turmas', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novaTurma = json_decode($request->getBody());
    $novaTurma = TurmaDAO::addTurma($novaTurma);

    echo json_encode($novaTurma);
});


$app->get('/turmas/:id', function ($id) {
    //recupera o cliente
    $turma = TurmaDAO::getTurmaById($id);
    
    echo json_encode($turma);
});

$app->get('/turmas', function () {
    //recupera o cliente
    $turma = TurmaDAO::getAll();
    
    echo json_encode($turma);
});

$app->put('/turmas/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $turma = json_decode($request->getBody());
    $turma = TurmaDAO::updateTurma($turma, $id);

    echo json_encode($turma );
});

$app->delete('/turmas/:id', function($id) {
    // exclui o cliente
    $isDeleted = TurmaDAO::deleteTurma($id);

    echo json_encode($isDeleted);
});
$app->run();
