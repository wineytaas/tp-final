<?php

class AlunoDao {

    public static function getAlunoById($id) {
        $connection = Connection::getConnection();
        $sql = "SELECT * FROM as_aluno WHERE id = $id";
        $result = mysqli_query($connection, $sql);
        $aluno = mysqli_fetch_object($result);

//        //recupera cidade do categoria
//        $sql = "SELECT * FROM toDoList_usuarios WHERE id=$categoria->usuario_id";
//        $result = mysqli_query($connection, $sql);
//        $categoria->usuario = mysqli_fetch_object($result);

        return $aluno;
    }

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

    public static function updateCategoria($categoria, $id) {
        $connection = Connection::getConnection();
        $sql = "UPDATE toDoList_categorias SET nome='$categoria->nome', usuario_id='$categoria->usuario_id' WHERE id=$id";
        $result = mysqli_query($connection, $sql);

        $categoriaAtualizado = CategoriaDAO::getCategoriaByID($id);
        return $categoriaAtualizado;
    }

    public static function deleteCategoria($id) {
        $connection = Connection::getConnection();
        $sql = "DELETE FROM toDoList_categorias WHERE id=$id";
        $result = mysqli_query($connection, $sql);

        if ($result === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public static function addCategoria($categoria) {
        $connection = Connection::getConnection();
        $sql = "INSERT INTO toDoList_categorias ( nome, usuario_id) VALUES ('$categoria->nome', $categoria->usuario_id)";
        $result = mysqli_query($connection, $sql);

        $sql = "SELECT * FROM `toDoList_categorias` WHERE nome = '$categoria->nome' AND usuario_id = '$categoria->usuario_id' ";
        $result = mysqli_query($connection, $sql);
        $categoria->id = mysqli_fetch_object($result)->id;

        $novoCategoria = CategoriaDAO::getCategoriaByID($categoria->id);
        return $novoCategoria;
    }

}
