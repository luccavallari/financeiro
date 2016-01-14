SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `financeiro` ;
CREATE SCHEMA IF NOT EXISTS `financeiro` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `financeiro` ;

-- -----------------------------------------------------
-- Table `financeiro`.`categoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`categoria` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`categoria` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `deleted_at` TINYINT(1) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Tabela que define os tipos de pessoas: [cliente, fornecedor, funcionario]';


-- -----------------------------------------------------
-- Table `financeiro`.`pessoa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`pessoa` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`pessoa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `telefone` VARCHAR(45) NOT NULL,
  `celular` VARCHAR(45) NOT NULL,
  `deleted_at` TINYINT(1) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `categoria_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pessoa_categoria`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `financeiro`.`categoria` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_pessoa_categoria_idx` ON `financeiro`.`pessoa` (`categoria_id` ASC);


-- -----------------------------------------------------
-- Table `financeiro`.`pessoa_fisica`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`pessoa_fisica` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`pessoa_fisica` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nascimento` DATETIME NOT NULL,
  `sexo` CHAR(1) NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `pessoa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pessoa_fisica_pessoa`
    FOREIGN KEY (`pessoa_id`)
    REFERENCES `financeiro`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_pessoa_fisica_pessoa1_idx` ON `financeiro`.`pessoa_fisica` (`pessoa_id` ASC);


-- -----------------------------------------------------
-- Table `financeiro`.`pessoa_juridica`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`pessoa_juridica` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`pessoa_juridica` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `razao_social` VARCHAR(45) NOT NULL,
  `cnpj` VARCHAR(45) NOT NULL,
  `inscricao_estadual` VARCHAR(45) NOT NULL,
  `pessoa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pessoa_juridica_pessoa`
    FOREIGN KEY (`pessoa_id`)
    REFERENCES `financeiro`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_pessoa_juridica_pessoa_idx` ON `financeiro`.`pessoa_juridica` (`pessoa_id` ASC);


-- -----------------------------------------------------
-- Table `financeiro`.`estado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`estado` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`estado` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(75) NOT NULL,
  `uf` CHAR(2) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `financeiro`.`cidade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`cidade` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`cidade` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `estado` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_estado`
    FOREIGN KEY (`estado`)
    REFERENCES `financeiro`.`estado` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_estado_idx` ON `financeiro`.`cidade` (`estado` ASC);


-- -----------------------------------------------------
-- Table `financeiro`.`endereco`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`endereco` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`endereco` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `endereco` VARCHAR(45) NULL,
  `complemento` VARCHAR(45) NULL,
  `bairro` VARCHAR(45) NULL,
  `cep` VARCHAR(45) NULL,
  `pessoa_id` INT NOT NULL,
  `cidade_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_endereco_pessoa1`
    FOREIGN KEY (`pessoa_id`)
    REFERENCES `financeiro`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_endereco_cidade`
    FOREIGN KEY (`cidade_id`)
    REFERENCES `financeiro`.`cidade` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_endereco_pessoa_idx` ON `financeiro`.`endereco` (`pessoa_id` ASC);

CREATE INDEX `fk_endereco_cidade_idx` ON `financeiro`.`endereco` (`cidade_id` ASC);


-- -----------------------------------------------------
-- Table `financeiro`.`forma_pagamento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`forma_pagamento` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`forma_pagamento` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `deleted_at` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'Define a forma de pagamento: [Dinheiro, Cartao, Cheque, Boleto]';


-- -----------------------------------------------------
-- Table `financeiro`.`conta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `financeiro`.`conta` ;

CREATE TABLE IF NOT EXISTS `financeiro`.`conta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tipo` CHAR(1) NOT NULL COMMENT 'Define se a conta é a pagar ou a receber.',
  `nome` VARCHAR(255) NOT NULL,
  `venci_em` DATE NOT NULL COMMENT 'Data de vencimento',
  `pago_em` DATE NOT NULL COMMENT 'Data de pagamento',
  `valor` DOUBLE(14,2) NOT NULL,
  `desconto` DOUBLE(14,2) NOT NULL,
  `pago` TINYINT(1) NOT NULL,
  `deleted_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `forma_pagamento_id` INT NOT NULL,
  `pessoa_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_conta_forma_pagamento`
    FOREIGN KEY (`forma_pagamento_id`)
    REFERENCES `financeiro`.`forma_pagamento` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_conta_pessoa`
    FOREIGN KEY (`pessoa_id`)
    REFERENCES `financeiro`.`pessoa` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_conta_forma_pagamento_idx` ON `financeiro`.`conta` (`forma_pagamento_id` ASC);

CREATE INDEX `fk_conta_pessoa_idx` ON `financeiro`.`conta` (`pessoa_id` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
