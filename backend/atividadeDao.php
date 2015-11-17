<?php

class AtividadeDAO {

    public static function addAtividade($atividade) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_atividade (nome,descricao,nota,id_turma)"
                . "VALUES('$atividade->nome' ,'$atividade->descricao' ,'$atividade->nota',$atividade->id_turma)";
        
        echo "OLA MUNDODDDDDDDDDDD";
        echo $sql;
        
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

    public static function getProfessorById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_professor WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $professor = mysqli_fetch_object($result);

        return $professor;
    }

    public static function getProfessorByLogin($login, $senha) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_professor WHERE login = '$login' AND senha = MD5('$senha') ";
        $result = mysqli_query($connection, $sql);
        $professor = mysqli_fetch_object($result);
        unset($professor->senha);
        $numrows = mysqli_num_rows($result);
        $ar = new stdClass();
        if ($numrows == 0) {
            $ar->result = false;
        } else {
            $ar->result = true;
            $ar->auth_key = ProfessorDAO::generateKey($login, md5($senha));
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
    
    public static function checkAuthorizationKey($key){
        $professores = ProfessorDAO::getAll();
        $ar = new stdClass();
        $ar->result = false;
        foreach($professores as $professor){
            $genKey = ProfessorDAO::generateKey($professor->login, $professor->senha);
            if($genKey == $key) {
                $ar->result = true;
                $ar->user = $professor;
            }
        }
        return $ar;
    }
    
    public static function generateKey($user,$password){
        return md5("professor".$user.$password.date("d"));
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
