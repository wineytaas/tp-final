<?php

class AtividadeDAO {

    public static function addAtividade($atividade) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_atividade (nome,descricao,nota,id_turma)"
                . "VALUES('$atividade->nome' ,'$atividade->descricao' ,'$atividade->nota',$atividade->id_turma)";
                
        $result = mysqli_query($connection, $sql);
        $ar = new stdClass();
        if (!$result) {
            $ar->result = false;
        } else {
            $ar->result = true;
            $ar->user = $atividade;
        }
        return $ar;
    }

    public static function getAtividadeById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_atividade WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $atividade = mysqli_fetch_object($result);
        
        $ar = new stdClass();
        if (!$atividade) {
            $ar->error = 2;
            $ar->description = "Atividade não encontrada";
        } else {
            $ar->result = true;
            $ar->user = $atividade;
        }
        return $ar;
    }


    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_atividade";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $atividades = array();
        while ($atividade = mysqli_fetch_object($result)) {
            if ($atividade != null) {
                $atividades[] = $atividade;
            }
        }
        return $atividades;
    }
    
    public static function updateAtividade($atividade, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_atividade SET nome='$atividade->nome' , descricao='$atividade->descricao' ,nota='$atividade->nota' ,id_turma='$atividade->id_turma' WHERE id = '$id' ";
        $result = mysqli_query($connection, $sql);

        $atividadeAtualizado = AtividadeDAO::getAtividadeById($id);
        return $atividadeAtualizado;
    }

    public static function deleteAtividade($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_atividade WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        
        $ar = new stdClass();
        if ($result === FALSE) {
            $ar->result = false;
            $ar->error = 2;
            $ar->description = "Atividade não pode ser excluida";
        } else {
            $ar->result = true;
            $ar->description = "Atividade excluida com êxito!";
        }
        
        return $ar;
    }

}
