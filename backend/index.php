<?php

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require 'connection.php';
require 'alunoDao.php';
require 'atividadeDao.php';
require 'professorDao.php';
require 'secretariaDao.php';
require 'noticiaDao.php';
require 'turmaDao.php';

date_default_timezone_set("America/Sao_Paulo");


$app = new \Slim\Slim();
$app->response()->header('Content-Type', 'application/json;charset=utf-8');


$app->get('/menu', function() {
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
    if ($alunor) {
        $optDados = new stdClass();
        $optDados->descricao = "Meus dados";
        $optDados->url = "#/meusdados";
        $optTurma = new stdClass();
        $optTurma->descricao = "Minha Turma";
        $optTurma->url = "#/minhaturma";
        $optNotas = new stdClass();
        $optNotas->descricao = "Minhas notas";
        $optNotas->url = "#/minhasnotas";
        $menu[] = $optDados;
        $menu[] = $optTurma;
        $menu[] = $optNotas;
    }

    if ($professorr) {
        $optAtividades = new stdClass();
        $optAtividades->descricao = "Gerenciar atividades";
        $optAtividades->url = "#/gerenciaratividades";
        $optTurmas = new stdClass();
        $optTurmas->descricao = "Lançar nota";
        $optTurmas->url = "#/lancarnota";
        $menu[] = $optAtividades;
        $menu[] = $optTurmas;
    }

    if ($secretariar) {
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

// Noticias

$app->get('/noticias', function() {
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

$app->get('/noticiasa', function() {
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

$app->get('/noticias/:id', function($id) {
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

    if ($secretariar->result) {
        // insere o cliente
        $nnoticiaBody = json_decode($request->getBody());
        if (isset($nnoticiaBody->descricao) && isset($nnoticiaBody->noticia)) {
            $nnoticia = NoticiaDAO::addNoticia($nnoticiaBody, $secretariar->user->id);
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
    if ($secretariar) {
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

    if ($secretariar->result) {
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

// Login

$app->post('/login', function() {

    $request = \Slim\Slim::getInstance()->request();
    // recupera todos os clientes
    $user = json_decode($request->getBody());
    if (isset($user->tipo) && isset($user->login) && isset($user->senha)) {
        if ($user->tipo == "alunos")
            echo json_encode(AlunoDAO::getAlunoByLogin($user->login, $user->senha));
        if ($user->tipo == "professores")
            echo json_encode(ProfessorDAO::getProfessorByLogin($user->login, $user->senha));
        if ($user->tipo == "secretarias")
            echo json_encode(SecretariaDAO::getSecretariaByLogin($user->login, $user->senha));
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
    $alunor = AlunoDAO::checkAuthorizationKey($authorization);

    if ($secretariar) {
        // recupera todos os clientes
        $alunos = AlunoDAO::getAll();
        $cl = new stdClass();
        $cl->alunos = $alunos;
        $cl->auth_key = $authorization;
        echo json_encode($cl);
    } else if ($alunor->result) {
        // recupera todos os clientes
        $alunos = AlunoDAO::getAlunoByTurma($alunor->user->turma_id);
        $alunos->turma->professor = ProfessorDAO::getProfessorById($alunos->turma->professor_id);
        $alunos->auth_key = $authorization;
        echo json_encode($alunos);
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
    if ($id == 0) {
        if ($alunor->result) {
            echo json_encode($alunor->user);
        } else {
            $error = new stdClass();
            $error->error = 2;
            $error->description = "Permissões insuficientes!";
            echo json_encode($error);
        }
    } else
    if ($secretariar) {
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

    if ($secretariar) {

        $aluno = json_decode($request->getBody());

        unset($aluno->senha);
        if (isset($aluno->novaSenha) && isset($aluno->novaSenha2) && !empty($aluno->novaSenha) && !empty($aluno->novaSenha2)) {
            if ($aluno->novaSenha == $aluno->novaSenha2) {
                $aluno->senha = md5($aluno->novaSenha);
                // insere o cliente
                $novoUsuario = AlunoDAO::addAluno($aluno);
                echo json_encode($novoUsuario);
            } else {
                $error = new stdClass();
                $error->error = 2;
                $error->description = "As senhas não conferem!";
                echo json_encode($error);
            }
        }
    } else {

        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->put('/alunos/:id', function ($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    //recupera o cliente
    $alunor = AlunoDAO::checkAuthorizationKey($authorization);
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $aluno = json_decode($request->getBody());


    if ($id == 0) {
        if ($alunor->result) {
            unset($aluno->senha);
            if (isset($aluno->novaSenha) && isset($aluno->novaSenha2) && !empty($aluno->novaSenha) && !empty($aluno->novaSenha2)) {
                if ($aluno->novaSenha == $aluno->novaSenha2) {
                    $aluno->senha = md5($aluno->novaSenha);
                } else {
                    $error = new stdClass();
                    $error->error = 2;
                    $error->description = "As senhas não conferem!";
                    echo json_encode($error);
                }
            }
            if (!isset($error)) {
                $alunof = AlunoDAO::updateAluno($aluno, $alunor->user->id);
                echo json_encode($alunof);
            }
        } else {
            $error = new stdClass();
            $error->error = 2;
            $error->description = "Permissões insuficientes!";
            echo json_encode($error);
        }
    } else
    if ($secretariar) {
        unset($aluno->senha);
        if (isset($aluno->novaSenha) && isset($aluno->novaSenha2) && !empty($aluno->novaSenha) && !empty($aluno->novaSenha2)) {
            if ($aluno->novaSenha == $aluno->novaSenha2) {
                $aluno->senha = md5($aluno->novaSenha);
            } else {
                $error = new stdClass();
                $error->error = 2;
                $error->description = "As senhas não conferem!";
                echo json_encode($error);
            }
        }
        if (!isset($error)) {
            $aluno = AlunoDAO::updateAlunoCompleto($aluno, $id);
            echo json_encode($aluno);
        }
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->delete('/alunos/:id', function($id) {

    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;

    if ($secretariar) {
        // recupera todos os clientes
        $isDeleted = AlunoDAO::deleteAluno($id);
        $cl = new stdClass();
        $cl->alunos = $isDeleted;
        $cl->auth_key = $authorization;
        echo json_encode($cl);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
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

$app->get('/professores/:login/:senha', function ($login, $senha) {
    //recupera o cliente
    $professor = ProfessorDAO::getProfessorByLogin($login, $senha);

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

    echo json_encode($professor);
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

$app->get('/secretarias/:login/:senha', function ($login, $senha) {
    //recupera o cliente
    $secretaria = SecretariaDAO::getSecretariaByLogin($login, $senha);

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

    echo json_encode($secretaria);
});

$app->delete('/secretarias/:id', function($id) {
    // exclui o cliente
    $isDeleted = SecretariaDAO::deleteSecretaria($id);

    echo json_encode($isDeleted);
});

//-------------------------------------  PROPRIEDADES DA TURMA  -------------------------------------


$app->post('/turmas', function() {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // insere o cliente
    $novaTurma = json_decode($request->getBody());
    $novaTurma = TurmaDAO::addTurma($novaTurma);

    echo json_encode($novaTurma);
});


$app->get('/turmas/:id', function ($id) {
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    //recupera o cliente
    $alunor = AlunoDAO::checkAuthorizationKey($authorization);
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    if ($secretariar) {
        //recupera o cliente
        $turma = TurmaDAO::getTurmaById($id);
        $turma->professor = ProfessorDAO::getProfessorById($turma->professor_id);
        $turma->alunos = AlunoDAO::getAlunoByTurma($id);

        echo json_encode($turma);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->get('/turmas', function () {

    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;

    if ($secretariar) {
        //recupera o cliente
        $turmas = TurmaDAO::getAll();
        $cl = new stdClass();
        $cl->turmas = $turmas;
        $cl->auth_key = $authorization;
        echo json_encode($cl);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->put('/turmas/:id', function ($id) {
    // recupera o request
    $request = \Slim\Slim::getInstance()->request();

    // atualiza o aluno
    $turma = json_decode($request->getBody());
    $turma = TurmaDAO::updateTurma($turma, $id);

    echo json_encode($turma);
});

$app->delete('/turmas/:id', function($id) {
    // exclui o cliente
    $isDeleted = TurmaDAO::deleteTurma($id);

    echo json_encode($isDeleted);
});

//-------------------------------------  PROPRIEDADES DA ATIVIDADE  -------------------------------------


$app->post('/atividades', function() {

    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $professor = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    echo $professor->id;

    if ($professor) {
        // recupera o request
        $request = \Slim\Slim::getInstance()->request();

        // insere o cliente
        $novaAtividade = json_decode($request->getBody());
        $novaAtividade = AtividadeDAO::addAtividade($novaAtividade);

        $cl = new stdClass();
        $cl->atividade = $novaAtividade;
        $cl->auth_key = $authorization;
        echo json_encode($cl);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});


$app->get('/atividades/:id', function ($id) {
    //recupera o cliente
    $atividade = AtividadeDAO::getAtividadeById($id);

    echo json_encode($atividade);
});

$app->get('/atividades', function () {

    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");

    $secretariar = SecretariaDAO::checkAuthorizationKey($authorization)->result;
    $alunor = AlunoDAO::checkAuthorizationKey($authorization);

    if ($secretariar) {
        // recupera todos os clientes
        $alunos = AtividadeDAO::getAll();
        $alunos->auth_key = $authorization;
        echo json_encode($alunos);
        
    } else if ($alunor->result) {
        // recupera todos os clientes
        $atividade = AtividadeDAO::getAtividadeByTurmaId($alunor->user->turma_id);
        $atividade->auth_key = $authorization;
        echo json_encode($atividade);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->put('/atividades/:id', function ($id) {

    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $professor = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    if ($professor) {
        // recupera o request
        $request = \Slim\Slim::getInstance()->request();

        // atualiza o aluno
        $atividade = json_decode($request->getBody());
        $novaAtividade = AtividadeDAO::updateAtividade($atividade, $id);
        
        $novaAtividade->auth_key = $authorization;
        echo json_encode($novaAtividade);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
});

$app->delete('/atividades/:id', function($id) {
    
    $authorization = \Slim\Slim::getInstance()->request->headers->get("Authorization");
    $professor = ProfessorDAO::checkAuthorizationKey($authorization)->result;
    if ($professor) {
        // recupera o request
        $request = \Slim\Slim::getInstance()->request();

        // deleta atividade
        $isDeleted = AtividadeDAO::deleteAtividade($id);
        
        $isDeleted->auth_key = $authorization;
        echo json_encode($isDeleted);
    } else {
        $error = new stdClass();
        $error->error = 2;
        $error->description = "Permissões insuficientes!";
        echo json_encode($error);
    }
    
    // exclui o cliente
    $isDeleted = AtividadeDAO::deleteAtividade($id);

    
});
$app->run();
