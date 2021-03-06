<?php

class AlunoDAO {

    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno ORDER BY turma_id, nome";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $alunos = array();
        while ($aluno = mysqli_fetch_object($result)) {
            if ($aluno != null) {
                $ar = new stdClass();
                $t = TurmaDAO::getTurmaById($aluno->turma_id);
                $ar->aluno = $aluno;
                $ar->turma = $t;
                $alunos[] = $ar;
            }
        }
        return $alunos;
    }

    public static function getAlunoByTurma($turmaId) {

        $connection = Connection::getConnection();
        $sql = "SELECT nome FROM as_aluno WHERE turma_id=$turmaId ORDER BY nome";
        $result = mysqli_query($connection, $sql);
        $alunos = array();
        while ($aluno = mysqli_fetch_object($result)) {
            if ($aluno != null) {
                $alunos[] = $aluno;
            }
        }
        $t = TurmaDAO::getTurmaById($turmaId);
        $ar = new stdClass();
        $ar->turma = $t;
        $ar->alunos = $alunos;
        return $ar;
    }

    public static function checkAuthorizationKey($key) {
        $alunos = AlunoDAO::getAll();
        $ar = new stdClass();
        $ar->result = false;
        foreach ($alunos as $aluno) {
            $genKey = AlunoDAO::generateKey($aluno->aluno->login, $aluno->aluno->senha);
            if ($genKey == $key) {
                $ar->result = true;
                $ar->user = $aluno->aluno;
            }
        }
        return $ar;
    }

    public static function generateKey($user, $password) {
        return md5("aluno" . $user . $password . date("d"));
    }

    public static function getAlunoById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $aluno = mysqli_fetch_object($result);
        unset($aluno->senha);
        $aluno->turma = TurmaDAO::getTurmaById($aluno->turma_id);
        return $aluno;
    }

    public static function getAlunoByLogin($login, $senha) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno WHERE login = '$login' AND senha = MD5('$senha')";
        $result = mysqli_query($connection, $sql);
        $aluno = mysqli_fetch_object($result);
        $numrows = mysqli_num_rows($result);
        $ar = new stdClass();
        if ($numrows == 0) {
            $ar->result = false;
        } else {
            $ar->result = true;
            $ar->auth_key = AlunoDAO::generateKey($login, md5($senha));
            $ar->user = $aluno;
        }
        return $ar;
    }

    public static function updateAluno($aluno, $id) {
        $connection = Connection::getConnection();
        if (isset($aluno->senha))
            $sql = "UPDATE as_aluno SET nome='$aluno->nome' ,rg='$aluno->rg' ,cpf='$aluno->cpf' ,logradouro='$aluno->logradouro' ,numero='$aluno->numero' ,bairro='$aluno->bairro' ,cidade='$aluno->cidade' ,cep='$aluno->cep', senha='$aluno->senha' WHERE id = $id";
        if (!isset($aluno->senha))
            $sql = "UPDATE as_aluno SET nome='$aluno->nome' ,rg='$aluno->rg' ,cpf='$aluno->cpf' ,logradouro='$aluno->logradouro' ,numero='$aluno->numero' ,bairro='$aluno->bairro' ,cidade='$aluno->cidade' ,cep='$aluno->cep' WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $alunoAtualizado = AlunoDAO::getAlunoById($id);
        return $alunoAtualizado;
    }

    public static function updateAlunoCompleto($aluno, $id) {
        $connection = Connection::getConnection();
        if (isset($aluno->senha)) $sql = "UPDATE as_aluno SET turma_id='$aluno->turma_id', nome='$aluno->nome' ,rg='$aluno->rg' ,cpf='$aluno->cpf' ,logradouro='$aluno->logradouro' ,numero='$aluno->numero' ,bairro='$aluno->bairro' ,cidade='$aluno->cidade' ,cep='$aluno->cep' ,parcelaspagas='$aluno->parcelaspagas' ,parcelastotais='$aluno->parcelastotais' ,valortotal='$aluno->valortotal',login='$aluno->login',senha='$aluno->senha' WHERE id = $id";
        if (!isset($aluno->senha)) $sql = "UPDATE as_aluno SET turma_id='$aluno->turma_id', nome='$aluno->nome' ,rg='$aluno->rg' ,cpf='$aluno->cpf' ,logradouro='$aluno->logradouro' ,numero='$aluno->numero' ,bairro='$aluno->bairro' ,cidade='$aluno->cidade' ,cep='$aluno->cep' ,parcelaspagas='$aluno->parcelaspagas' ,parcelastotais='$aluno->parcelastotais' ,valortotal='$aluno->valortotal',login='$aluno->login' WHERE id = $id";
        $result = mysqli_query($connection, $sql);

        $alunoAtualizado = AlunoDAO::getAlunoById($id);
        return $alunoAtualizado;
    }

    public static function deleteAluno($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_aluno WHERE id = $id";
        $result = mysqli_query($connection, $sql);

        $ar = new stdClass();
        if ($result === FALSE) {
            $ar->result = false;
            $ar->mensagem = "Erro ao deletar professor!";
        } else {
            $ar->result = true;
            $ar->mensagem = "Professor deletado!";
        }

        return $ar;
    }

    public static function addAluno($aluno) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_aluno (nome,rg,cpf,logradouro,numero,bairro,cidade,cep,parcelaspagas,parcelastotais,valortotal,login,senha,turma_id)"
                . " VALUES('$aluno->nome' ,'$aluno->rg' ,'$aluno->cpf','$aluno->logradouro' ,'$aluno->numero' ,'$aluno->bairro' ,'$aluno->cidade' ,"
                . " '$aluno->cep' ,'0' ,'$aluno->parcelastotais' ,'$aluno->valortotal','$aluno->login','$aluno->senha','$aluno->turma_id')";
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            $error = new stdClass();
            $error->error = 2;
            $error->description = "Não foi possível adicionar o aluno";
            return $error;
        } else {
            $sql = "SELECT * FROM `as_aluno` WHERE nome = '$aluno->nome' AND cpf = '$aluno->cpf' AND rg = '$aluno->rg' ";
            $result = mysqli_query($connection, $sql);
            $aluno->id = mysqli_fetch_object($result)->id;

            $novoAluno = AlunoDAO::getAlunoById($aluno->id);
            return $novoAluno;
        }
    }

}
