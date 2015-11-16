<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'alunoDao.php';
require 'professorDao.php';
require 'secretariaDao.php';
require 'noticiaDao.php';
date_default_timezone_set ("America/Sao_Paulo");


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');


$app->get('/menu', function(){
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $answer = new stdClass();
    $menu = array();
    $optHome = new stdClass();
    $optHome->descricao = "Início";
    $optHome->url = "#/";
    $optLogout = new stdClass();
    $optLogout->descricao = "Deslogar";
    $optLogout->url = "#/logout";
    $menu[] = $optHome;
    $alunor = AlunoDAO::checkAuthorizationKey($authorization)->result;
    $professorr = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    if($alunor){
        $optDados = new stdClass();
        $optDados->descricao = "Meus dados";
        $optDados->url = "#/meusdados";
        $optNotas = new stdClass();
        $optNotas->descricao = "Minhas notas";
        $optNotas->url = "#/minhasnotas";
        $menu[] = $optDados;
        $menu[] = $optNotas;
    }
    
    if($professorr){
        $optAtividades = new stdClass();
        $optAtividades->descricao = "Gerenciar atividades";
        $optAtividades->url = "#/gerenciaratividades";
        $optTurmas = new stdClass();
        $optTurmas->descricao = "Lançar nota";
        $optTurmas->url = "#/lancarnota";
        $menu[] = $optAtividades;
        $menu[] = $optTurmas;
    }
    
    if($secretariar){        
        $optNoticias = new stdClass();
        $optNoticias->descricao = "Gerenciar Notícias";
        $optNoticias->url = "#/gerenciarnoticias";
        $optAlunos = new stdClass();
        $optAlunos->descricao = "Gerenciar Alunos";
        $optAlunos->url = "#/gerenciaralunos";
        $optProfessores = new stdClass();
        $optProfessores->descricao = "Gerenciar Professores";
        $optProfessores->url = "#/gerenciarprofessores";
        $optTurmas = new stdClass();
        $optTurmas->descricao = "Gerenciar Turmas";
        $optTurmas->url = "#/gerenciarturmas";
        $menu[] = $optNoticias;
        $menu[] = $optAlunos;
        $menu[] = $optProfessores;
        $menu[] = $optTurmas;
    }
    $menu[] = $optLogout;
    $answer->auth_key = $authorization;
    $answer->menu = $menu;
    if ($alunor || $professorr || $secretariar) {
        echo json_encode($answer);
    } else {
        $error = new stdClass();
        $error->error = 1;
        $error->description = "Chave de autorização inválida!";
        echo json_encode($error);
    }
});


$app->get('/noticias', function(){
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $alunor = AlunoDAO::checkAuthorizationKey($authorization)->result;
    $professorr = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    $answer = new stdClass();
    $noticias = NoticiaDAO::getAll();
    
    $answer->auth_key = $authorization;
    $answer->noticias = $noticias;
    if ($alunor || $professorr || $secretariar) {
        echo json_encode($answer);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->get('/noticiasa', function(){
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $alunor = AlunoDAO::checkAuthorizationKey($authorization)->result;
    $professorr = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    $answer = new stdClass();
    $noticias = NoticiaDAO::getAlll();
    
    $answer->auth_key = $authorization;
    $answer->noticias = $noticias;
    if ($alunor || $professorr || $secretariar) {
        $answer->edit_permission = $secretariar;
        echo json_encode($answer);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->get('/noticias/:id', function($id){
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $alunor = AlunoDAO::checkAuthorizationKey($authorization)->result;
    $professorr = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    $answer = new stdClass();
    $noticia = NoticiaDAO::getNoticiaById($id);    
    $answer->auth_key = $authorization;
    $answer->noticia = $noticia;
    if ($alunor || $professorr || $secretariar) {
        echo json_encode($answer);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->post('/noticias', function() {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();
    
    //recupera o cliente
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization);
    
    if($secretariar->result){        
        // insere o cliente
        $nnoticiaBody = json_decode($request->getBody());
        if(isset($nnoticiaBody->descricao) && isset($nnoticiaBody->noticia)){            
            $nnoticia = NoticiaDAO::addNoticia($nnoticiaBody,$secretariar->user->id);
            echo json_encode($nnoticia);
        } else {
            $error = new stdClass();
            $error->error = 2;
            $error->description = "Preencha todos os campos!";
            echo json_encode($error);              
        }
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);        
    }

    
});

$app->put('/noticias/:id', function ($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    
    //recupera o cliente
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    if($secretariar){        
        // recupera o request
        $request = \Slim\Slim::getInstance()->request();

        // atualiza o aluno
        $noticiaB = json_decode($request->getBody());
        $noticia = NoticiaDAO::updateNoticia($noticiaB, $id);

        echo json_encode($noticia);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});


$app->delete('/noticias/:id', function($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    
    //recupera o cliente
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization);
    
    if($secretariar->result){ 
        // exclui a noticia
        $isDeleted = NoticiaDAO::deleteNoticia($id);

        echo json_encode($isDeleted);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);        
    }
});

$app->post('/login', function() {
    
    $request = \Slim\Slim::getInstance()->request();
    // recupera todos os clientes
    $user = json_decode($request->getBody());
    if(isset($user->tipo) && isset($user->login) && isset($user->senha) ){
        if($user->tipo == "alunos") echo json_encode(AlunoDAO::getAlunoByLogin($user->login,$user->senha));
        if($user->tipo == "professores") echo json_encode(ProfessorDAO::getProfessorByLogin($user->login,$user->senha));
        if($user->tipo == "secretarias") echo json_encode(SecretariaDAO::getSecretariaByLogin($user->login,$user->senha));  
    } else {
        $ar = new stdClass();
        $ar->result = false;
        echo json_encode($ar);
    }
});

//-------------------------------------  PROPRIEDADES DO ALUNO  -------------------------------------
$app->get('/alunos', function() {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    if($secretariar){        
        // recupera todos os clientes
        $alunos = AlunoDAO::getAll();
        $cl = new stdClass();
        $cl->alunos = $alunos;
        $cl->auth_key = $authorization;
        echo json_encode($cl);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);        
    }
});

$app->get('/alunos/:id', function ($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    //recupera o cliente
    $alunor = AlunoDAO::checkAuthorizationKey($authorization);
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    if($id == 0){
        if($alunor->result){
            echo json_encode($alunor->user);
        } else {
            $error = new stdClass();
            $error->error = 2;
            $error->description = "Permissões insuficientes!";
            echo json_encode($error); 
        }
    } else 
    if($secretariar){        
        $aluno = AlunoDAO::getAlunoById($id);

        echo json_encode($aluno);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);        
    }
});


$app->post('/alunos', function() {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();
    
    //recupera o cliente
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    
    if($secretariar){        
        // insere o cliente
        $novoUsuario = json_decode($request->getBody());
        $novoUsuario = AlunoDAO::addAluno($novoUsuario);
        echo json_encode($novoUsuario);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);        
    }

    
});

$app->put('/alunos/:id', function ($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
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
