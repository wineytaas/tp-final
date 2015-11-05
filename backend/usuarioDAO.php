<?php


class UsuarioDAO
{

  public static function getUsuarioByID($id) {
    $connection = Connection::getConnection();
    $sql = "SELECT * FROM toDoList_usuarios WHERE id=$id";
    $result  = mysqli_query($connection, $sql);
    $usuario = mysqli_fetch_object($result);

    return $usuario;
  }


  public static function getAll()
  {
    $connection = Connection::getConnection();
    $sql = "SELECT * FROM toDoList_usuarios";

    // recupera todos os usuarios
    $result  = mysqli_query($connection, $sql);
    $usuarios = array();
    while ($usuario = mysqli_fetch_object($result)) {
      if ($usuario != null) {
        $usuarios[] = $usuario;
      }
    }
    return $usuarios;
  }


  public static function updateUsuario($usuario, $id) {
    $connection = Connection::getConnection();
    $sql = "UPDATE toDoList_usuarios SET nome='$usuario->nome', email='$usuario->email', login='$usuario->login', senha='$usuario->senha' WHERE id=$id";
    $result  = mysqli_query($connection, $sql);

    $usuarioAtualizado = UsuarioDAO::getUsuarioByID($id);
    return $usuarioAtualizado;
  }


  public static function deleteUsuario($id) {
    $connection = Connection::getConnection();
    $sql = "DELETE FROM toDoList_usuarios WHERE id=$id";
    $result  = mysqli_query($connection, $sql);

    if ($result === FALSE) {
      return false;
    } else {
      return true;
    }
  }


  public static function addUsuario($usuario) {
    $connection = Connection::getConnection();
    $sql = "INSERT INTO toDoList_usuarios ( nome, email, login, senha) VALUES ('$usuario->nome', '$usuario->email', '$usuario->login', '$usuario->senha' )";
    $result  = mysqli_query($connection, $sql);
    
    $sql = "SELECT * FROM `toDoList_usuarios` WHERE email = '$usuario->email' ";
    $result  = mysqli_query($connection, $sql);
    $usuario = mysqli_fetch_object($result);

    $novoUsuario = UsuarioDAO::getUsuarioByID($usuario->id);
    return $novoUsuario;
  }
}
