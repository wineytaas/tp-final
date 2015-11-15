<?php

class NoticiaDAO {

    public static function addNoticia($noticia,$secretaria_id) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO as_noticia (descricao,data,noticia,secretaria_id)"
                . "VALUES('$noticia->descricao' ,CURDATE() ,'$noticia->noticia' , "
                . "'$secretaria_id')";
        $result = mysqli_query($connection, $sql);
        
        $ar = new stdClass();
        if($result){
            $ar->result = true;
            $ar->descricao = "Notícia inserida.";
        } else {
            $ar->error = 3;
            $ar->description = "Erro na execução da query.";
        }
        return $ar;
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
        $sql = "SELECT as_noticia.id,descricao,DATE_FORMAT(data, '%d/%m') as data,noticia,as_secretaria.nome as autor FROM as_noticia JOIN as_secretaria ON as_noticia.secretaria_id = as_secretaria.id GROUP BY as_noticia.id ORDER BY as_noticia.data LIMIT 6";

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
    
    public static function getAlll() {
        $connection = Connection::getConnection();
        $sql = "SELECT as_noticia.id,descricao,DATE_FORMAT(data, '%d/%m') as data,noticia,as_secretaria.nome as autor FROM as_noticia JOIN as_secretaria ON as_noticia.secretaria_id = as_secretaria.id GROUP BY as_noticia.id ORDER BY as_noticia.data";

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
            $ar->error = 3;
            $ar->description = "Erro ao deletar noticia!";
        } else {
            $ar->result = true;
            $ar->mensagem = "Noticia deletada!";
        }
        
        return $ar;
    }

}
