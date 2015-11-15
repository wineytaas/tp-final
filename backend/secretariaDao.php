<?php

class SecretariaDAO {

    public static function addSecretaria($secretaria) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_secretaria (nome,logradouro,numero,bairro,cidade,cep,complemento,login,senha)"
                . "VALUES('$secretaria->nome' ,'$secretaria->logradouro' ,'$secretaria->numero' , "
                . "'$secretaria->bairro' ,'$secretaria->cidade' ,'$secretaria->cep' ,'$secretaria->complemento' ,'$secretaria->login','$secretaria->senha')";
        $result = mysqli_query($connection, $sql);

        $sql = "SELECT * FROM `as_secretaria` WHERE login = '$secretaria->login'";
        $result = mysqli_query($connection, $sql);
        $secretaria->id = mysqli_fetch_object($result)->id;

        $novoProfessor = SecretariaDAO::getSecretariaById($secretaria->id);
        return $novoProfessor;
    }

    public static function getSecretariaById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_secretaria WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $secretaria = mysqli_fetch_object($result);

        return $secretaria;
    }

    public static function getSecretariaByLogin($login, $senha) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_secretaria WHERE login = '$login' AND senha = MD5('$senha') ";
        $result = mysqli_query($connection, $sql);
        $secretaria = mysqli_fetch_object($result);
        
        $numrows = mysqli_num_rows($result);
        $ar = new stdClass();
        if ($numrows == 0) {
            $ar->result = false;
        } else {
            $ar->result = true;
            $ar->auth_key = SecretariaDAO::generateKey($login, md5($senha));
            $ar->user = $secretaria;
        }
        return $ar;
    }
    
    public static function checkAuthorizationKey($key){
        $secretarias = SecretariaDAO::getAll();
        $ar = new stdClass();
        $ar->result = false;
        foreach($secretarias as $secretaria){
            $genKey = SecretariaDAO::generateKey($secretaria->login, $secretaria->senha);
            if($genKey == $key) {
                $ar->result = true;
                $ar->user = $secretaria;
            }
        }
        return $ar;
    }
    
    public static function generateKey($user,$password){
        return md5("secretaria".$user.$password.date("d"));
    }

    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_secretaria";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $secretarias = array();
        while ($secretaria = mysqli_fetch_object($result)) {
            if ($secretaria != null) {
                $secretarias[] = $secretaria;
            }
        }
        return $secretarias;
    }

    public static function updateSecretaria($secretaria, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_secretaria SET nome='$secretaria->nome' , logradouro='$secretaria->logradouro' ,numero='$secretaria->numero' ,bairro='$secretaria->bairro' ,cidade='$secretaria->cidade' ,cep='$secretaria->cep' , login = '$secretaria->login', senha = '$secretaria->senha' WHERE id = '$id' ";
        $result = mysqli_query($connection, $sql);

        $secretariaAtualizado = SecretariaDAO::getSecretariaById($id);
        return $secretariaAtualizado;
    }

    public static function deleteSecretaria($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_secretaria WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        
        $ar = new stdClass();
        if ($result === FALSE) {
            $ar->result = false;
            $ar->mensagem = "Erro ao deletar secretaria!";
        } else {
            $ar->result = true;
            $ar->mensagem = "Secretaria deletada!";
        }
        
        return $ar;
    }

}
