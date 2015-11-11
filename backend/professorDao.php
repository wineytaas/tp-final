<?php

class ProfessorDAO {

    public static function addProfessor($professor) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_professor (nome,logradouro,numero,bairro,cidade,cep,salario,complemento,login,senha)"
                . "VALUES('$professor->nome' ,'$professor->logradouro' ,'$professor->numero' , "
                . "'$professor->bairro' ,'$professor->cidade' ,'$professor->cep','$professor->salario' ,'$professor->complemento' ,'$professor->login','$professor->senha')";
        $result = mysqli_query($connection, $sql);

        $sql = "SELECT * FROM `as_professor` WHERE login = '$professor->login'";
        $result = mysqli_query($connection, $sql);
        $professor->id = mysqli_fetch_object($result)->id;

        $novoProfessor = ProfessorDAO::getProfessorById($professor->id);
        return $novoProfessor;
    }

    public static function getProfessorById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_professor WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $professor = mysqli_fetch_object($result);

        return $professor;
    }

    public static function getProfessorByLogin($login, $senha) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_professor WHERE login = '$login' AND senha = '$senha' ";
        $result = mysqli_query($connection, $sql);
        $professor = mysqli_fetch_object($result);
        
        $numrows = mysqli_num_rows($result);
        $ar = new stdClass();
        if ($numrows == 0) {
            $ar->result = false;
        } else {
            $ar->result = true;
            $ar->user = $professor;
        }
        return $ar;
    }

    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_professor";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $professores = array();
        while ($professor = mysqli_fetch_object($result)) {
            if ($professor != null) {
                $professores[] = $professor;
            }
        }
        return $professores;
    }

    public static function updateProfessor($professor, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_professor SET nome='$professor->nome' , logradouro='$professor->logradouro' ,numero='$professor->numero' ,bairro='$professor->bairro' ,cidade='$professor->cidade' ,cep='$professor->cep' , salario = '$professor->salario', login = '$professor->login', senha = '$professor->senha' WHERE id = '$id' ";
        $result = mysqli_query($connection, $sql);

        $professorAtualizado = ProfessorDAO::getProfessorById($id);
        return $professorAtualizado;
    }

    public static function deleteProfessor($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_professor WHERE id = $id";
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

}
