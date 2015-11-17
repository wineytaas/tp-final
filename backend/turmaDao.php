<?php

class TurmaDAO {

    public static function addTurma($turma) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_turma (nome,professor_id,horario)"
                . "VALUES('$turma->nome' ,'$turma->professor_id' ,'$turma->horario')";
        $result = mysqli_query($connection, $sql);

        $sql = "SELECT * FROM `as_turma` WHERE nome = '$turma->nome'";
        $result = mysqli_query($connection, $sql);
        $turma->id = mysqli_fetch_object($result)->id;

        $novaTurma = TurmaDAO::getTurmaById($turma->id);
        return $novaTurma;
    }

    public static function getTurmaById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_turma WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $turma = mysqli_fetch_object($result);
        return $turma;
    }

    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_turma";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $turmas = array();
        while ($turma = mysqli_fetch_object($result)) {
            if ($turma != null) {                
                $turma->professor = ProfessorDAO::getProfessorById($turma->professor_id);
                $turmas[] = $turma;
            }
        }
        return $turmas;
    }

    public static function updateTurma($turma, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_turma SET nome='$turma->nome' , horario='$turma->horario' ,professor_id='$turma->professor_id'  WHERE id = '$id' ";
        $result = mysqli_query($connection, $sql);

        $turmaAtualizado = TurmaDAO::getTurmaById($id);
        return $turmaAtualizado;
    }

    public static function deleteTurma($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_turma WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        
        $ar = new stdClass();
        if ($result === FALSE) {
            $ar->result = false;
            $ar->mensagem = "Erro ao deletar turma!";
        } else {
            $ar->result = true;
            $ar->mensagem = "Turma deletada!";
        }
        
        return $ar;
    }

}
