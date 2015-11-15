<?php

class NoticiaDAO {

    public static function addNoticia($professor) {
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

    public static function getNoticiaById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT as_noticia.id,descricao,DATE_FORMAT(data, '%d/%m') as data,noticia,as_secretaria.nome as autor FROM as_noticia JOIN as_secretaria ON as_noticia.secretaria_id = as_secretaria.id GROUP BY as_noticia.id HAVING as_noticia.id = $id";
        $result = mysqli_query($connection, $sql);
        $noticia = mysqli_fetch_object($result);

        return $noticia;
    }

    public static function getAll() {
        $connection = Connection::getConnection();
        $sql = "SELECT as_noticia.id,descricao,DATE_FORMAT(data, '%d/%m') as data,noticia,as_secretaria.nome as autor FROM as_noticia JOIN as_secretaria ON as_noticia.secretaria_id = as_secretaria.id GROUP BY as_noticia.id";

        // recupera todos os categorias
        $result = mysqli_query($connection, $sql);
        $noticias = array();
        while ($noticia = mysqli_fetch_object($result)) {
            if ($noticia != null) {
                $noticias[] = $noticia;
            }
        }
        return $noticias;
    }

    public static function updateNoticia($professor, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE as_professor SET nome='$professor->nome' , logradouro='$professor->logradouro' ,numero='$professor->numero' ,bairro='$professor->bairro' ,cidade='$professor->cidade' ,cep='$professor->cep' , salario = '$professor->salario', login = '$professor->login', senha = '$professor->senha' WHERE id = '$id' ";
        $result = mysqli_query($connection, $sql);

        $professorAtualizado = ProfessorDAO::getProfessorById($id);
        return $professorAtualizado;
    }

    public static function deleteNoticia($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM as_noticia WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        
        $ar = new stdClass();
        if ($result === FALSE) {
            $ar->result = false;
            $ar->mensagem = "Erro ao deletar noticia!";
        } else {
            $ar->result = true;
            $ar->mensagem = "Noticia deletada!";
        }
        
        return $ar;
    }

}
