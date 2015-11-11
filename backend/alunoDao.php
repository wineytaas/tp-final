<?php

class AlunoDAO {
    
    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $alunos = array();
        while ($aluno = mysqli_fetch_object($result)) {
            if ($aluno != null) {
                $alunos[] = $aluno;
            }
        }
        return $alunos;
    }

    public static function getAlunoById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $aluno = mysqli_fetch_object($result);

        return $aluno;
    }
    
    public static function getAlunoByLogin($login, $senha) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno WHERE login = '$login' AND senha = '$senha'";
        $result = mysqli_query($connection, $sql);
        $aluno = mysqli_fetch_object($result);
        
        $numrows = mysqli_num_rows($result);
        $ar = new stdClass();
        if ($numrows == 0) {
            $ar->result = false;
        }else{
            $ar->result = true;
            $ar->aluno = $aluno;
        } 
        return $ar;
    }

    
    public static function updateAluno($aluno, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_aluno SET nome='$aluno->nome' ,rg='$aluno->rg' ,cpf='$aluno->cpf' ,logradouro='$aluno->logradouro' ,numero='$aluno->numero' ,bairro='$aluno->bairro' ,cidade='$aluno->cidade' ,cep='$aluno->cep' ,parcelaspagas='$aluno->parcelaspagas' ,parcelastotais='$aluno->parcelastotais' ,valortotal='$aluno->valortotal',login='$aluno->login',senha='$aluno->senha' WHERE id = $id";
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
        $sql = "INSERT INTO as_aluno (nome,rg,cpf,logradouro,numero,bairro,cidade,cep,parcelaspagas,parcelastotais,valortotal,login,senha)"
                . " VALUES('$aluno->nome' ,'$aluno->rg' ,'$aluno->cpf','$aluno->logradouro' ,'$aluno->numero' ,'$aluno->bairro' ,'$aluno->cidade' ,'$aluno->cep' ,'$aluno->parcelaspagas' ,'$aluno->parcelastotais' ,'$aluno->valortotal','$aluno->login','$aluno->senha')";
        $result = mysqli_query($connection, $sql);

        $sql = "SELECT * FROM `as_aluno` WHERE nome = '$aluno->nome' AND cpf = '$aluno->cpf' AND rg = '$aluno->rg' ";
        $result = mysqli_query($connection, $sql);
        $aluno->id = mysqli_fetch_object($result)->id;

        $novoAluno = AlunoDAO::getAlunoById($aluno->id);
        return $novoAluno;
    }

}
