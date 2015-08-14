SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Person`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Person` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Person` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Namn` VARCHAR(255) NULL ,
  `Telefonnummer` VARCHAR(255) NULL ,
  `Adress` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci
PACK_KEYS = DEFAULT;


-- -----------------------------------------------------
-- Table `mydb`.`Bokning_betalperson`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Bokning_betalperson` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Bokning_betalperson` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Person_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Betalperson_Person1_idx` (`Person_id` ASC) ,
  CONSTRAINT `fk_Betalperson_Person1`
    FOREIGN KEY (`Person_id` )
    REFERENCES `mydb`.`Person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Bokning_faktura`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Bokning_faktura` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Bokning_faktura` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Betalperson_id` INT NOT NULL ,
  `Bokning_fakturaBetald` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Faktura_Betalperson1_idx` (`Betalperson_id` ASC) ,
  CONSTRAINT `fk_Faktura_Betalperson1`
    FOREIGN KEY (`Betalperson_id` )
    REFERENCES `mydb`.`Bokning_betalperson` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Kal_period`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Kal_period` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Kal_period` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Vecka_start` INT NULL ,
  `Vecka_slut` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Kal_prislista`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Kal_prislista` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Kal_prislista` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Beskrivning` VARCHAR(255) NOT NULL COMMENT '\n' ,
  `Pris_stuga` DECIMAL NOT NULL ,
  `Pris_cykel` DECIMAL NOT NULL ,
  `Pris_skidor` DECIMAL NOT NULL ,
  `Aktiv` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Bokning_typ`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Bokning_typ` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Bokning_typ` (
  `id` INT NOT NULL ,
  `Beskrivning` VARCHAR(255) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Bokning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Bokning` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Bokning` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Kal_period_id` INT NOT NULL ,
  `Kal_prislista_id` INT NOT NULL ,
  `Bokning_faktura_id` INT NOT NULL ,
  `Bokning_typ_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Bokning_Kal_period1_idx` (`Kal_period_id` ASC) ,
  INDEX `fk_Bokning_Kal_prislista1_idx` (`Kal_prislista_id` ASC) ,
  INDEX `fk_Bokning_Bokning_faktura1_idx` (`Bokning_faktura_id` ASC) ,
  INDEX `fk_Bokning_Bokning_typ1_idx` (`Bokning_typ_id` ASC) ,
  CONSTRAINT `fk_Bokning_Kal_period1`
    FOREIGN KEY (`Kal_period_id` )
    REFERENCES `mydb`.`Kal_period` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bokning_Kal_prislista1`
    FOREIGN KEY (`Kal_prislista_id` )
    REFERENCES `mydb`.`Kal_prislista` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bokning_Bokning_faktura1`
    FOREIGN KEY (`Bokning_faktura_id` )
    REFERENCES `mydb`.`Bokning_faktura` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Bokning_Bokning_typ1`
    FOREIGN KEY (`Bokning_typ_id` )
    REFERENCES `mydb`.`Bokning_typ` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Stuga_utrustning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Stuga_utrustning` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Stuga_utrustning` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Stuga_utrustningStr` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Stuga_köksstandard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Stuga_köksstandard` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Stuga_köksstandard` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Stuga_köksstandardStr` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Stuga`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Stuga` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Stuga` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Adress` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_swedish_ci' NULL ,
  `Bäddar` INT NULL ,
  `Rum` INT NULL ,
  `Stuga_utrustning_id` INT NOT NULL ,
  `Stuga_köksstandard_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Stuga_Stuga_utrustning1_idx` (`Stuga_utrustning_id` ASC) ,
  INDEX `fk_Stuga_Stuga_köksstandard1_idx` (`Stuga_köksstandard_id` ASC) ,
  CONSTRAINT `fk_Stuga_Stuga_utrustning1`
    FOREIGN KEY (`Stuga_utrustning_id` )
    REFERENCES `mydb`.`Stuga_utrustning` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Stuga_Stuga_köksstandard1`
    FOREIGN KEY (`Stuga_köksstandard_id` )
    REFERENCES `mydb`.`Stuga_köksstandard` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_swedish_ci;


-- -----------------------------------------------------
-- Table `mydb`.`Stuga_bokning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Stuga_bokning` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Stuga_bokning` (
  `Bokning_id` INT NOT NULL ,
  `Stuga_id` INT NULL ,
  `Person01` INT NULL DEFAULT NULL ,
  `Person02` INT NULL DEFAULT NULL ,
  `Person03` INT NULL DEFAULT NULL ,
  `Person04` INT NULL DEFAULT NULL ,
  `Person05` INT NULL DEFAULT NULL ,
  `Person06` INT NULL DEFAULT NULL ,
  `Person07` INT NULL DEFAULT NULL ,
  `Person08` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`Bokning_id`) ,
  INDEX `fk_Stug_bokning_Stuga1_idx` (`Stuga_id` ASC) ,
  CONSTRAINT `fk_Stug_reservation_Reservation1`
    FOREIGN KEY (`Bokning_id` )
    REFERENCES `mydb`.`Bokning` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Stug_bokning_Stuga1`
    FOREIGN KEY (`Stuga_id` )
    REFERENCES `mydb`.`Stuga` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Cykel_hjälm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Cykel_hjälm` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Cykel_hjälm` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Storlek` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Cykel_typ`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Cykel_typ` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Cykel_typ` (
  `id` INT NOT NULL ,
  `Beskrivning` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Cykel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Cykel` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Cykel` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Cykel_typ_id` INT NOT NULL ,
  `Storlek` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Cykel_Cykel_typ1_idx` (`Cykel_typ_id` ASC) ,
  CONSTRAINT `fk_Cykel_Cykel_typ1`
    FOREIGN KEY (`Cykel_typ_id` )
    REFERENCES `mydb`.`Cykel_typ` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Cykel_bokning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Cykel_bokning` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Cykel_bokning` (
  `Bokning_id` INT NOT NULL ,
  `Person_id` INT NULL ,
  `Cykel_hjälm_id` INT NULL ,
  `Cykel_id` INT NULL ,
  `Benlängd` INT NULL ,
  `Hjälmstorlek` INT NULL ,
  PRIMARY KEY (`Bokning_id`) ,
  INDEX `fk_Cykel_reservation_Cykel_hjälm1_idx` (`Cykel_hjälm_id` ASC) ,
  INDEX `fk_Cykel_reservation_Cykel1_idx` (`Cykel_id` ASC) ,
  INDEX `fk_Cykel_reservation_Person1_idx` (`Person_id` ASC) ,
  CONSTRAINT `fk_Cykel_reservation_Reservation1`
    FOREIGN KEY (`Bokning_id` )
    REFERENCES `mydb`.`Bokning` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cykel_reservation_Cykel_hjälm1`
    FOREIGN KEY (`Cykel_hjälm_id` )
    REFERENCES `mydb`.`Cykel_hjälm` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cykel_reservation_Cykel1`
    FOREIGN KEY (`Cykel_id` )
    REFERENCES `mydb`.`Cykel` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cykel_reservation_Person1`
    FOREIGN KEY (`Person_id` )
    REFERENCES `mydb`.`Person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Skid_hjälm`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Skid_hjälm` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Skid_hjälm` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Storlek` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Skid_stav`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Skid_stav` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Skid_stav` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Storlek` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Skid_typ`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Skid_typ` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Skid_typ` (
  `id` INT NOT NULL ,
  `Beskrivning` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Skidor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Skidor` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Skidor` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `Skid_typ_id` INT NOT NULL ,
  `Storlek` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Skid_par_Skid_typ1_idx` (`Skid_typ_id` ASC) ,
  CONSTRAINT `fk_Skid_par_Skid_typ1`
    FOREIGN KEY (`Skid_typ_id` )
    REFERENCES `mydb`.`Skid_typ` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Skid_bokning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Skid_bokning` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Skid_bokning` (
  `Bokning_id` INT NOT NULL ,
  `Person_id` INT NULL ,
  `Skid_hjälm_id` INT NULL ,
  `Skid_stav_id` INT NULL ,
  `Skidor_id` INT NULL ,
  `Skidlängd` INT NULL ,
  `Skonummer` INT NULL ,
  `Hjälmstorlek` INT NULL ,
  PRIMARY KEY (`Bokning_id`) ,
  INDEX `fk_Skid_reservation_Hjälm_skid1_idx` (`Skid_hjälm_id` ASC) ,
  INDEX `fk_Skid_reservation_Stavpar1_idx` (`Skid_stav_id` ASC) ,
  INDEX `fk_Skid_reservation_Skidpar1_idx` (`Skidor_id` ASC) ,
  INDEX `fk_Skid_reservation_Person1_idx` (`Person_id` ASC) ,
  CONSTRAINT `fk_Skid_reservation_Hjälm_skid1`
    FOREIGN KEY (`Skid_hjälm_id` )
    REFERENCES `mydb`.`Skid_hjälm` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Skid_reservation_Stavpar1`
    FOREIGN KEY (`Skid_stav_id` )
    REFERENCES `mydb`.`Skid_stav` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Skid_reservation_Skidpar1`
    FOREIGN KEY (`Skidor_id` )
    REFERENCES `mydb`.`Skidor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Skid_reservation_Reservation1`
    FOREIGN KEY (`Bokning_id` )
    REFERENCES `mydb`.`Bokning` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Skid_reservation_Person1`
    FOREIGN KEY (`Person_id` )
    REFERENCES `mydb`.`Person` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`Kal_vecka`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`Kal_vecka` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`Kal_vecka` (
  `id` INT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

USE `mydb` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
