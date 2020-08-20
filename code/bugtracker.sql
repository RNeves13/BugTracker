-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 20-Ago-2020 às 22:21
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bugtracker`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `bug`
--

CREATE SCHEMA IF NOT EXISTS `bugTracker` DEFAULT CHARACTER SET utf8 ;
USE `bugTracker` ;

DROP TABLE IF EXISTS `bug`;
CREATE TABLE IF NOT EXISTS `bug` (
  `idBug` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(256) NOT NULL,
  `dateFound` date NOT NULL,
  `solved` tinyint(4) NOT NULL,
  `project` int(11) NOT NULL,
  `finder` int(11) NOT NULL,
  PRIMARY KEY (`idBug`),
  KEY `fk_Bug_Project1_idx` (`project`),
  KEY `fk_Bug_User1_idx` (`finder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `project`
--

DROP TABLE IF EXISTS `project`;
CREATE TABLE IF NOT EXISTS `project` (
  `idProject` int(11) NOT NULL AUTO_INCREMENT,
  `projectName` varchar(45) NOT NULL,
  `description` varchar(256) NOT NULL,
  `dateCreation` date NOT NULL,
  `owner` int(11) NOT NULL,
  PRIMARY KEY (`idProject`),
  KEY `fk_Project_User_idx` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `projectworker`
--

DROP TABLE IF EXISTS `projectworker`;
CREATE TABLE IF NOT EXISTS `projectworker` (
  `idUser` int(11) NOT NULL,
  `idProject` int(11) NOT NULL,
  `type` enum('admin','coder','tester') NOT NULL,
  PRIMARY KEY (`idUser`,`idProject`),
  KEY `fk_User_has_Project_Project1_idx` (`idProject`),
  KEY `fk_User_has_Project_User1_idx` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `idUser` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` enum('admin','basic','develpor') NOT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE KEY `idUser_UNIQUE` (`idUser`),
  UNIQUE KEY `userName_UNIQUE` (`userName`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`idUser`, `userName`, `password`, `email`, `name`, `type`) VALUES
(1, 'Dark', 'aa787bc9cc97ba5d27cc042ecffe1489', 'ruijsneves@gmail.com', 'Rui', 'admin');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `bug`
--
ALTER TABLE `bug`
  ADD CONSTRAINT `fk_Bug_Project1` FOREIGN KEY (`project`) REFERENCES `project` (`idProject`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Bug_User1` FOREIGN KEY (`finder`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_Project_User` FOREIGN KEY (`owner`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `projectworker`
--
ALTER TABLE `projectworker`
  ADD CONSTRAINT `fk_User_has_Project_Project1` FOREIGN KEY (`idProject`) REFERENCES `project` (`idProject`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_User_has_Project_User1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
