-- Server version	5.5.62

SET NAMES utf8;

--
-- Table structure for table `ci_sessions`
--
DROP TABLE IF EXISTS `ci_sessions`; 
CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL,
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `modulos_administracion`
--
DROP TABLE IF EXISTS `modulos_administracion`;
CREATE TABLE `modulos_administracion` (
  `mad_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mad_nombre` varchar(50) NOT NULL,
  `mad_url` varchar(50) NOT NULL,
  `mad_menu` varchar(50) NOT NULL,
  PRIMARY KEY (`mad_id`)
) ENGINE=InnoDB;

--
-- Table structure for table `usuarios_administracion`
--
DROP TABLE IF EXISTS `usuarios_administracion`;
CREATE TABLE `usuarios_administracion` (
  `adm_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `adm_nombre` varchar(150) NOT NULL,
  `adm_email` varchar(150) NOT NULL,
  `adm_password` varchar(150) NOT NULL,
  `adm_tipo` text,
  PRIMARY KEY (`adm_id`),
  UNIQUE KEY `adm_email` (`adm_email`)
) ENGINE=InnoDB;

--
-- Table structure for table `acceso_administracion`
--
DROP TABLE IF EXISTS `acceso_administracion`;
CREATE TABLE `acceso_administracion` (
  `aca_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `adm_id` bigint(20) unsigned NOT NULL,
  `mad_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`aca_id`),
  KEY `fk_acceso_administracion_modulos_administracion` (`mad_id`),
  KEY `fk_acceso_administracion_usuarios_administracion` (`adm_id`),
  CONSTRAINT `fk_acceso_administracion_modulos_administracion` FOREIGN KEY (`mad_id`) REFERENCES `modulos_administracion` (`mad_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_acceso_administracion_usuarios_administracion` FOREIGN KEY (`adm_id`) REFERENCES `usuarios_administracion` (`adm_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `articulos_revista`
--
DROP TABLE IF EXISTS `articulos_revista`;
CREATE TABLE `articulos_revista` (
  `art_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `adm_id` bigint(20) unsigned DEFAULT NULL,
  `art_titulo` varchar(150) NOT NULL,
  `art_url` varchar(150) NOT NULL,
  `art_abstracto` varchar(500) NOT NULL,
  `art_contenido` text NOT NULL,
  `art_etiquetas` text,
  `art_pdf` varchar(300) DEFAULT NULL,
  `art_estado` varchar(30) NOT NULL,
  `art_fecha` date NOT NULL,
  `art_portada` varchar(300) NOT NULL,
  `art_leido` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`art_id`),
  UNIQUE KEY `art_titulo` (`art_titulo`),
  UNIQUE KEY `art_url` (`art_url`),
  KEY `fk_articulos_revista_usuarios_administracion` (`adm_id`),
  CONSTRAINT `fk_articulos_revista_usuarios_administracion` FOREIGN KEY (`adm_id`) REFERENCES `usuarios_administracion` (`adm_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `usuarios_revista`
--
DROP TABLE IF EXISTS `usuarios_revista`;
CREATE TABLE `usuarios_revista` (
  `usr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usr_nombre` varchar(150) NOT NULL,
  `usr_email` varchar(150) NOT NULL,
  `usr_password` varchar(150) NOT NULL,
  `usr_imagen` varchar(300) DEFAULT NULL,
  `usr_activo` int(10) unsigned DEFAULT '0',
  `usr_codigo` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_email` (`usr_email`),
  UNIQUE KEY `usr_codigo` (`usr_codigo`)
) ENGINE=InnoDB;

--
-- Table structure for table `categorias_revista`
--
DROP TABLE IF EXISTS `categorias_revista`;
CREATE TABLE `categorias_revista` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_super_id` bigint(20) unsigned DEFAULT NULL,
  `cat_nombre` varchar(50) NOT NULL,
  `cat_url` varchar(50) NOT NULL,
  `cat_color` char(7) NOT NULL,
  PRIMARY KEY (`cat_id`),
  KEY `fk_categorias_revista_categorias_revista` (`cat_super_id`),
  CONSTRAINT `fk_categorias_revista_categorias_revista` FOREIGN KEY (`cat_super_id`) REFERENCES `categorias_revista` (`cat_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `autores_articulo`
--
DROP TABLE IF EXISTS `autores_articulo`;
CREATE TABLE `autores_articulo` (
  `aut_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usr_id` bigint(20) unsigned NOT NULL,
  `art_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`aut_id`),
  KEY `fk_autores_articulo_articulos_revista` (`art_id`),
  KEY `fk_autores_articulo_usuarios_revista` (`usr_id`),
  CONSTRAINT `fk_autores_articulo_articulos_revista` FOREIGN KEY (`art_id`) REFERENCES `articulos_revista` (`art_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_autores_articulo_usuarios_revista` FOREIGN KEY (`usr_id`) REFERENCES `usuarios_revista` (`usr_id`) ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `biblioteca_usuario`
--
DROP TABLE IF EXISTS `biblioteca_usuario`;
CREATE TABLE `biblioteca_usuario` (
  `bus_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usr_id` bigint(20) unsigned NOT NULL,
  `art_id` bigint(20) unsigned NOT NULL,
  `bus_fecha` date DEFAULT NULL,
  PRIMARY KEY (`bus_id`),
  KEY `fk_biblioteca_usuario_articulos_revista` (`art_id`),
  KEY `fk_biblioteca_usuario_usuarios_revista` (`usr_id`),
  CONSTRAINT `fk_biblioteca_usuario_articulos_revista` FOREIGN KEY (`art_id`) REFERENCES `articulos_revista` (`art_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_biblioteca_usuario_usuarios_revista` FOREIGN KEY (`usr_id`) REFERENCES `usuarios_revista` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `categorias_articulo`
--
DROP TABLE IF EXISTS `categorias_articulo`; 
CREATE TABLE `categorias_articulo` (
  `car_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `art_id` bigint(20) unsigned NOT NULL,
  `cat_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`car_id`),
  KEY `fk_categorias_articulo_articulos_revista` (`art_id`),
  KEY `fk_categorias_articulo_categorias_revista` (`cat_id`),
  CONSTRAINT `fk_categorias_articulo_articulos_revista` FOREIGN KEY (`art_id`) REFERENCES `articulos_revista` (`art_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categorias_articulo_categorias_revista` FOREIGN KEY (`cat_id`) REFERENCES `categorias_revista` (`cat_id`) ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `curriculum_usuarios`
--
DROP TABLE IF EXISTS `curriculum_usuarios`;
CREATE TABLE `curriculum_usuarios` (
  `cur_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usr_id` bigint(20) unsigned NOT NULL,
  `cur_url` varchar(300) DEFAULT NULL,
  `cur_abstract` text,
  `cur_pdf` varchar(300) DEFAULT NULL,
  `cur_email` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`cur_id`),
  KEY `fk_curriculum_usuarios_usuarios_revista` (`usr_id`),
  CONSTRAINT `fk_curriculum_usuarios_usuarios_revista` FOREIGN KEY (`usr_id`) REFERENCES `usuarios_revista` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

--
-- Table structure for table `portadas_revista`
--
DROP TABLE IF EXISTS `portadas_revista`;
CREATE TABLE `portadas_revista` (
  `por_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) unsigned DEFAULT NULL,
  `por_nombre` varchar(50) NOT NULL,
  `por_datos` text,
  `por_tipo` varchar(10) NOT NULL DEFAULT 'categoria',
  PRIMARY KEY (`por_id`),
  KEY `fk_portadas_revista_categorias_revista` (`cat_id`),
  CONSTRAINT `fk_portadas_revista_categorias_revista` FOREIGN KEY (`cat_id`) REFERENCES `categorias_revista` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
