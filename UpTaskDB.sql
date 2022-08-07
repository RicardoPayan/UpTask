-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema UpTask_model
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema UpTask_model
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `UpTask_model` DEFAULT CHARACTER SET utf8 ;
USE `UpTask_model` ;

-- -----------------------------------------------------
-- Table `UpTask_model`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UpTask_model`.`usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(30) NULL,
    `email` VARCHAR(30) NULL,
    `password` VARCHAR(60) NULL,
    `token` VARCHAR(15) NULL,
    `confirmado` TINYINT(1) NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UpTask_model`.`proyectos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UpTask_model`.`proyectos` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `proyecto` VARCHAR(60) NULL,
    `url` VARCHAR(32) NULL,
    `propietarioId` INT(11) NULL,
    PRIMARY KEY (`id`),
    INDEX `propietarioId_idx` (`propietarioId` ASC),
    CONSTRAINT `propietarioId`
    FOREIGN KEY (`propietarioId`)
    REFERENCES `UpTask_model`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `UpTask_model`.`tareas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UpTask_model`.`tareas` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(60) NULL,
    `estado` TINYINT(1) NULL,
    `proyectoId` INT(11) NULL,
    PRIMARY KEY (`id`),
    INDEX `proyectoId_idx` (`proyectoId` ASC),
    CONSTRAINT `proyectoId`
    FOREIGN KEY (`proyectoId`)
    REFERENCES `UpTask_model`.`proyectos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
    ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
