-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 02, 2015 at 01:52 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `daw-aluno2`
--

-- --------------------------------------------------------

--
-- Table structure for table `toDoList_categorias`
--

CREATE TABLE IF NOT EXISTS `toDoList_categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_toDoList_categoria_toDoList_usuarios` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=67 ;

--
-- Dumping data for table `toDoList_categorias`
--

INSERT INTO `toDoList_categorias` (`id`, `nome`, `usuario_id`) VALUES
(66, 'Trabalho', 1);

-- --------------------------------------------------------

--
-- Table structure for table `toDoList_tarefas`
--

CREATE TABLE IF NOT EXISTS `toDoList_tarefas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(200) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tarefa_categoria` (`categoria_id`),
  KEY `fk_tarefa_usuario` (`usuario_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `toDoList_tarefas`
--

INSERT INTO `toDoList_tarefas` (`id`, `descricao`, `usuario_id`, `categoria_id`) VALUES
(36, 'Ola, Wisney $&amp;', 1, 66),
(37, 'asdads', 1, 66);

-- --------------------------------------------------------

--
-- Table structure for table `toDoList_usuarios`
--

CREATE TABLE IF NOT EXISTS `toDoList_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `login` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `toDoList_usuarios`
--

INSERT INTO `toDoList_usuarios` (`id`, `nome`, `email`, `login`, `senha`) VALUES
(1, 'w', 'w@e.e', 'w', 'w'),
(6, 'w', 'ww@e.e', 'ww', 'w'),
(9, 'w', 'www@e.e', 'www', 'w'),
(10, 'w', 'wwww@e.e', 'wwww', 'w');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `toDoList_categorias`
--
ALTER TABLE `toDoList_categorias`
  ADD CONSTRAINT `fk_toDoList_categoria_toDoList_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `toDoList_usuarios` (`id`);

--
-- Constraints for table `toDoList_tarefas`
--
ALTER TABLE `toDoList_tarefas`
  ADD CONSTRAINT `fk_tarefa_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `toDoList_categorias` (`id`),
  ADD CONSTRAINT `fk_tarefa_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `toDoList_usuarios` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
