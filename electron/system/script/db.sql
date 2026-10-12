-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2012 at 10:29 AM
-- Server version: 5.1.63
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `electron`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) NOT NULL DEFAULT '',
  `to` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1610 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_artefactos`
--

DROP TABLE IF EXISTS `t_artefactos`;
CREATE TABLE IF NOT EXISTS `t_artefactos` (
  `artefacto_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`artefacto_id`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_asociacion_usuarios`
--

DROP TABLE IF EXISTS `t_asociacion_usuarios`;
CREATE TABLE IF NOT EXISTS `t_asociacion_usuarios` (
  `usuario_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date DEFAULT NULL,
  `banco` varchar(255) NOT NULL,
  `tipo` int(11) NOT NULL,
  `cuenta` varchar(20) NOT NULL,
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_banco`
--

DROP TABLE IF EXISTS `t_banco`;
CREATE TABLE IF NOT EXISTS `t_banco` (
  `banco_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `cuenta` varchar(255) NOT NULL,
  PRIMARY KEY (`banco_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_clientes`
--

DROP TABLE IF EXISTS `t_clientes`;
CREATE TABLE IF NOT EXISTS `t_clientes` (
  `documento_id` int(11) NOT NULL,
  `credito_id` int(11) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_inicio_cobro` date NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `cantidad` decimal(15,3) NOT NULL,
  `monto_total` decimal(15,3) NOT NULL,
  `monto_vacaciones` decimal(10,0) NOT NULL,
  `mes_vacaciones` varchar(32) NOT NULL,
  `monto_aguinaldo` decimal(10,0) NOT NULL,
  `numerocuotas` int(11) NOT NULL,
  `periocidad` int(11) NOT NULL,
  `monto_cuota` decimal(10,0) NOT NULL,
  KEY `documento_id` (`documento_id`),
  KEY `credito_id` (`credito_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_clientes_creditos`
--

DROP TABLE IF EXISTS `t_clientes_creditos`;
CREATE TABLE IF NOT EXISTS `t_clientes_creditos` (
  `credito_id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` int(11) NOT NULL,
  `contrato_id` varchar(32) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_inicio_cobro` date NOT NULL,
  `motivo` tinyint(1) NOT NULL,
  `cantidad` decimal(15,3) NOT NULL,
  `monto_total` decimal(15,3) NOT NULL,
  `numero_cuotas` tinyint(1) NOT NULL,
  `periocidad` tinyint(1) NOT NULL,
  `nomina_procedencia` text NOT NULL,
  `monto_cuota` decimal(10,0) NOT NULL,
  `condicion` tinyint(1) NOT NULL,
  `num_operacion` char(32) NOT NULL,
  `fecha_operacion` date NOT NULL,
  `forma_contrato` tinyint(1) NOT NULL,
  `empresa` tinyint(1) NOT NULL,
  `cobrado_en` varchar(256) NOT NULL,
  `observaciones` varchar(256) NOT NULL,
  `numero_factura` char(16) NOT NULL,
  `monto_operacion` decimal(10,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `serial` text NOT NULL,
  `estado_verificado` tinyint(1) NOT NULL,
  `fecha_verificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `codigo_n` varchar(32) NOT NULL,
  `codigo_n_a` varchar(32) NOT NULL,
  `expediente_c` varchar(32) NOT NULL,
  `marca_consulta` tinyint(1) NOT NULL,
  PRIMARY KEY (`contrato_id`),
  KEY `credito_id` (`credito_id`),
  KEY `documento_id` (`documento_id`),
  KEY `contrato_id` (`contrato_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26631 ;

--
-- Triggers `t_clientes_creditos`
--
DROP TRIGGER IF EXISTS `d_estadoejecucion`;
DELIMITER //
CREATE TRIGGER `d_estadoejecucion` BEFORE INSERT ON `t_clientes_creditos`
 FOR EACH ROW BEGIN
   INSERT INTO t_estadoejecucion (oidc,oide,observacion,estatus) VALUES (NEW.contrato_id,1,'Creado Inicialmente',1);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `d_clientes_creditosu`;
DELIMITER //
CREATE TRIGGER `d_clientes_creditosu` BEFORE UPDATE ON `t_clientes_creditos`
 FOR EACH ROW BEGIN
 	INSERT INTO _th_clientes_creditos (documento_id, contrato_id, fecha_solicitud, fecha_inicio_cobro, motivo, cantidad, 
 	monto_total, numero_cuotas, periocidad, nomina_procedencia, monto_cuota, condicion, num_operacion, fecha_operacion, 
 	forma_contrato, empresa, cobrado_en, observaciones, numero_factura, monto_operacion, estatus, serial, estado_verificado, 
 	fecha_verificado, codigo_n, codigo_n_a, expediente_c, evento) VALUES 
 	(OLD.documento_id, OLD.contrato_id, OLD.fecha_solicitud, OLD.fecha_inicio_cobro, OLD.motivo, OLD.cantidad, 
 	OLD.monto_total, OLD.numero_cuotas, OLD.periocidad, OLD.nomina_procedencia, OLD.monto_cuota, OLD.condicion, OLD.num_operacion, OLD.fecha_operacion, 
 	OLD.forma_contrato, OLD.empresa, OLD.cobrado_en, OLD.observaciones, OLD.numero_factura, OLD.monto_operacion, OLD.estatus, OLD.serial, OLD.estado_verificado, 
 	OLD.fecha_verificado, OLD.codigo_n, OLD.codigo_n_a, OLD.expediente_c, '0');
 END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `d_clientes_creditosd`;
DELIMITER //
CREATE TRIGGER `d_clientes_creditosd` BEFORE DELETE ON `t_clientes_creditos`
 FOR EACH ROW BEGIN	
 	INSERT INTO _th_clientes_creditos (documento_id, contrato_id, fecha_solicitud, fecha_inicio_cobro, motivo, cantidad, 
 	monto_total, numero_cuotas, periocidad, nomina_procedencia, monto_cuota, condicion, num_operacion, fecha_operacion, 
 	forma_contrato, empresa, cobrado_en, observaciones, numero_factura, monto_operacion, estatus, serial, estado_verificado, 
 	fecha_verificado, codigo_n, codigo_n_a, expediente_c, evento) VALUES 
 	(OLD.documento_id, OLD.contrato_id, OLD.fecha_solicitud, OLD.fecha_inicio_cobro, OLD.motivo, OLD.cantidad, 
 	OLD.monto_total, OLD.numero_cuotas, OLD.periocidad, OLD.nomina_procedencia, OLD.monto_cuota, OLD.condicion, OLD.num_operacion, OLD.fecha_operacion, 
 	OLD.forma_contrato, OLD.empresa, OLD.cobrado_en, OLD.observaciones, OLD.numero_factura, OLD.monto_operacion, OLD.estatus, OLD.serial, OLD.estado_verificado, 
 	OLD.fecha_verificado, OLD.codigo_n, OLD.codigo_n_a, OLD.expediente_c, '1');
 	DELETE FROM t_lista_cobros WHERE t_lista_cobros.credito_id=OLD.contrato_id;
	DELETE FROM t_estadoejecucion WHERE t_estadoejecucion.oidc=OLD.contrato_id;
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_creditos`
--

DROP TABLE IF EXISTS `t_creditos`;
CREATE TABLE IF NOT EXISTS `t_creditos` (
  `credito_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `monto_minimo` decimal(15,3) NOT NULL,
  `monto_maximo` decimal(15,3) NOT NULL,
  PRIMARY KEY (`credito_id`),
  KEY `credito_id` (`credito_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_des_banco`
--

DROP TABLE IF EXISTS `t_des_banco`;
CREATE TABLE IF NOT EXISTS `t_des_banco` (
  `documento_id` int(11) NOT NULL,
  `banco_id` int(11) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `numero_contrato` int(11) NOT NULL,
  `cuenta` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_estadodocumento`
--

DROP TABLE IF EXISTS `t_estadodocumento`;
CREATE TABLE IF NOT EXISTS `t_estadodocumento` (
  `oid` int(2) NOT NULL AUTO_INCREMENT,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  `clase` varchar(64) NOT NULL,
  `denominacion` varchar(64) NOT NULL,
  `accion` char(32) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_estadoejecucion`
--

DROP TABLE IF EXISTS `t_estadoejecucion`;
CREATE TABLE IF NOT EXISTS `t_estadoejecucion` (
  `oidc` char(16) NOT NULL,
  `oide` int(2) NOT NULL,
  `observacion` varchar(255) NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oidc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Triggers `t_estadoejecucion`
--
DROP TRIGGER IF EXISTS `d_contratos_estadoejecucion`;
DELIMITER //
CREATE TRIGGER `d_contratos_estadoejecucion` BEFORE UPDATE ON `t_estadoejecucion`
 FOR EACH ROW BEGIN
   INSERT INTO _tr_contratos_estadoejecucion (oidc,oide,observacion,estatus,modificacion) VALUES (OLD.oidc,OLD.oide,OLD.observacion,OLD.estatus,OLD.modificacion);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_estados`
--

DROP TABLE IF EXISTS `t_estados`;
CREATE TABLE IF NOT EXISTS `t_estados` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) COLLATE latin1_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_his_cancelados`
--

DROP TABLE IF EXISTS `t_his_cancelados`;
CREATE TABLE IF NOT EXISTS `t_his_cancelados` (
  `documento_id` int(11) NOT NULL,
  `primer_nombre` varchar(128) NOT NULL,
  `segundo_nombre` varchar(128) NOT NULL,
  `primer_apellido` varchar(128) NOT NULL,
  `segundo_apellido` varchar(128) NOT NULL,
  PRIMARY KEY (`documento_id`),
  KEY `documento_id` (`documento_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_his_contratos`
--

DROP TABLE IF EXISTS `t_his_contratos`;
CREATE TABLE IF NOT EXISTS `t_his_contratos` (
  `documento_id` int(11) NOT NULL,
  `n_contrato` varchar(128) NOT NULL,
  `cobrado` varchar(128) NOT NULL,
  `nomina` varchar(128) NOT NULL,
  `ubicacion` varchar(128) NOT NULL,
  `monto` varchar(128) NOT NULL,
  `fecha_i` varchar(16) NOT NULL,
  `fecha_f` varchar(16) NOT NULL,
  `descripcion` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_inventario`
--

DROP TABLE IF EXISTS `t_inventario`;
CREATE TABLE IF NOT EXISTS `t_inventario` (
  `inventario_id` int(11) NOT NULL AUTO_INCREMENT,
  `proveedor` int(11) NOT NULL,
  `artefacto` int(11) NOT NULL,
  `marca` varchar(250) NOT NULL,
  `modelo` varchar(250) NOT NULL,
  `precio_compra` decimal(15,3) NOT NULL,
  `precio_venta` decimal(15,3) NOT NULL,
  `porcentaje` int(11) NOT NULL,
  PRIMARY KEY (`inventario_id`),
  KEY `proveedor` (`proveedor`,`artefacto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=228 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_linaje`
--

DROP TABLE IF EXISTS `t_linaje`;
CREATE TABLE IF NOT EXISTS `t_linaje` (
  `oid` int(2) NOT NULL AUTO_INCREMENT,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_lista_cobros`
--

DROP TABLE IF EXISTS `t_lista_cobros`;
CREATE TABLE IF NOT EXISTS `t_lista_cobros` (
  `documento_id` int(11) NOT NULL,
  `credito_id` varchar(32) NOT NULL,
  `mes` varchar(32) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `monto` decimal(15,3) NOT NULL,
  `modificado` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Triggers `t_lista_cobros`
--
DROP TRIGGER IF EXISTS `d_lista_cobros`;
DELIMITER //
CREATE TRIGGER `d_lista_cobros` BEFORE DELETE ON `t_lista_cobros`
 FOR EACH ROW BEGIN
 	INSERT INTO _th_lista_cobros (documento_id, credito_id, mes, descripcion, fecha, monto) VALUES 
 	(OLD.documento_id, OLD.credito_id, OLD.mes, OLD.descripcion, OLD.fecha, OLD.monto);
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `t_nominas`
--

DROP TABLE IF EXISTS `t_nominas`;
CREATE TABLE IF NOT EXISTS `t_nominas` (
  `codigo` int(3) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_perfil`
--

DROP TABLE IF EXISTS `t_perfil`;
CREATE TABLE IF NOT EXISTS `t_perfil` (
  `oid` int(2) NOT NULL AUTO_INCREMENT,
  `id` char(32) NOT NULL,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  `eactivo` tinyint(1) NOT NULL,
  `origen` int(1) NOT NULL,
  `verdadero` int(1) NOT NULL,
  `falso` int(1) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_personas`
--

DROP TABLE IF EXISTS `t_personas`;
CREATE TABLE IF NOT EXISTS `t_personas` (
  `documento_id` int(11) NOT NULL,
  `primer_nombre` varchar(128) NOT NULL,
  `segundo_nombre` varchar(128) NOT NULL,
  `primer_apellido` varchar(128) NOT NULL,
  `segundo_apellido` varchar(128) NOT NULL,
  `apellido_casada` varchar(128) NOT NULL,
  `nro_documento` varchar(32) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `nacionalidad` varchar(2) NOT NULL,
  `ciudad` varchar(128) NOT NULL,
  `cargo_actual` varchar(255) NOT NULL,
  `sexo` varchar(1) NOT NULL,
  `estado_civil` varchar(32) NOT NULL,
  `telefono` varchar(64) NOT NULL,
  `celular` varchar(64) NOT NULL,
  `direccion` text NOT NULL,
  `correo` varchar(128) NOT NULL,
  `vive` tinyint(1) NOT NULL,
  `observacion` varchar(255) NOT NULL,
  `codigo_nomina` varchar(32) NOT NULL,
  `codigo_nomina_aux` varchar(32) NOT NULL,
  `expediente_caja` varchar(32) NOT NULL,
  `codigo_gaceta` varchar(32) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `direccion_trabajo` varchar(255) NOT NULL,
  `copia_ci` tinyint(1) NOT NULL,
  `copia_ba` tinyint(1) NOT NULL,
  `titular` tinyint(1) NOT NULL,
  `fe_vida` tinyint(1) NOT NULL,
  `banco_1` varchar(32) NOT NULL,
  `cuenta_1` varchar(32) NOT NULL,
  `tipo_cuenta_1` varchar(32) NOT NULL,
  `banco_2` varchar(32) NOT NULL,
  `cuenta_2` varchar(32) NOT NULL,
  `tipo_cuenta_2` varchar(32) NOT NULL,
  `banco_3` varchar(32) NOT NULL,
  `cuenta_3` varchar(32) NOT NULL,
  `tipo_cuenta_3` varchar(32) NOT NULL,
  `disponibilidad` tinyint(1) NOT NULL,
  `rif` varchar(32) NOT NULL,
  `foto` int(11) NOT NULL,
  `gaceta` int(11) NOT NULL,
  `ente_procedencia` varchar(255) NOT NULL,
  `fecha_vacaciones` date NOT NULL,
  `copia_libreta` int(11) NOT NULL,
  `cargo_ocupaba` varchar(255) NOT NULL,
  `municipio` varchar(256) NOT NULL,
  `parroquia` varchar(256) NOT NULL,
  `sector` varchar(256) NOT NULL,
  `avenida` varchar(256) NOT NULL,
  `urbanizacion` varchar(256) NOT NULL,
  `pin` varchar(64) NOT NULL,
  `calle` varchar(256) NOT NULL,
  `monto_vacaciones` decimal(15,3) NOT NULL,
  `monto_aguinaldos` decimal(15,3) NOT NULL,
  PRIMARY KEY (`documento_id`),
  KEY `documento_id` (`documento_id`),
  KEY `primer_nombre` (`primer_nombre`),
  KEY `segundo_nombre` (`segundo_nombre`),
  KEY `primer_apellido` (`primer_apellido`),
  KEY `segundo_apellido` (`segundo_apellido`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_privilegios`
--

DROP TABLE IF EXISTS `t_privilegios`;
CREATE TABLE IF NOT EXISTS `t_privilegios` (
  `oid` int(3) NOT NULL AUTO_INCREMENT,
  `id` char(32) NOT NULL,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  `clase` char(32) NOT NULL,
  `metodo` char(32) NOT NULL,
  `accion` char(32) NOT NULL,
  `funcion` char(32) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_productos`
--

DROP TABLE IF EXISTS `t_productos`;
CREATE TABLE IF NOT EXISTS `t_productos` (
  `serial` varchar(250) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `inventario_id` int(11) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `estatus` int(11) NOT NULL,
  `compra` decimal(15,3) NOT NULL,
  `venta` decimal(15,3) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `cant_garantia` int(11) NOT NULL,
  `tipo_garantia` varchar(32) NOT NULL,
  PRIMARY KEY (`serial`),
  KEY `serial` (`serial`,`inventario_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_proveedores`
--

DROP TABLE IF EXISTS `t_proveedores`;
CREATE TABLE IF NOT EXISTS `t_proveedores` (
  `proveedor_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) NOT NULL,
  PRIMARY KEY (`proveedor_id`),
  KEY `nombre` (`nombre`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_respuestas`
--

DROP TABLE IF EXISTS `t_respuestas`;
CREATE TABLE IF NOT EXISTS `t_respuestas` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `id_sugerencia` int(4) NOT NULL,
  `respuesta` text NOT NULL,
  `respondio_usuario` varchar(30) NOT NULL,
  `fecha_modifica` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2970 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_rol`
--

DROP TABLE IF EXISTS `t_rol`;
CREATE TABLE IF NOT EXISTS `t_rol` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_sugerencias`
--

DROP TABLE IF EXISTS `t_sugerencias`;
CREATE TABLE IF NOT EXISTS `t_sugerencias` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` int(1) NOT NULL,
  `prioridad` int(1) NOT NULL,
  `de_usuario` varchar(32) NOT NULL,
  `para_usuario` varchar(32) NOT NULL,
  `nivel_usuario` int(1) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1431 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_ubicacion`
--

DROP TABLE IF EXISTS `t_ubicacion`;
CREATE TABLE IF NOT EXISTS `t_ubicacion` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` char(32) NOT NULL,
  `descripcion` char(64) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_usuario`
--

DROP TABLE IF EXISTS `t_usuario`;
CREATE TABLE IF NOT EXISTS `t_usuario` (
  `oid` int(2) NOT NULL,
  `documento_id` int(11) NOT NULL,
  `descripcion` char(64) NOT NULL,
  `seudonimo` char(32) NOT NULL,
  `clave` char(64) NOT NULL,
  `correo` char(64) NOT NULL,
  `fecha` datetime NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `conectado` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_usuarios`
--

DROP TABLE IF EXISTS `t_usuarios`;
CREATE TABLE IF NOT EXISTS `t_usuarios` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` int(11) DEFAULT NULL,
  `login` varchar(64) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `nivel_usuario` int(11) NOT NULL,
  `ubicacion` varchar(256) NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_usuarios_historial`
--

DROP TABLE IF EXISTS `t_usuarios_historial`;
CREATE TABLE IF NOT EXISTS `t_usuarios_historial` (
  `usuario_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `t_zona_postal`
--

DROP TABLE IF EXISTS `t_zona_postal`;
CREATE TABLE IF NOT EXISTS `t_zona_postal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` tinyint(2) NOT NULL,
  `zona_postal` varchar(40) COLLATE latin1_spanish_ci NOT NULL,
  `codigo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `_th_clientes_creditos`
--

DROP TABLE IF EXISTS `_th_clientes_creditos`;
CREATE TABLE IF NOT EXISTS `_th_clientes_creditos` (
  `documento_id` int(11) NOT NULL,
  `contrato_id` varchar(32) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_inicio_cobro` date NOT NULL,
  `motivo` tinyint(1) NOT NULL,
  `cantidad` decimal(15,3) NOT NULL,
  `monto_total` decimal(15,3) NOT NULL,
  `numero_cuotas` tinyint(1) NOT NULL,
  `periocidad` tinyint(1) NOT NULL,
  `nomina_procedencia` text NOT NULL,
  `monto_cuota` decimal(10,0) NOT NULL,
  `condicion` tinyint(1) NOT NULL,
  `num_operacion` char(32) NOT NULL,
  `fecha_operacion` date NOT NULL,
  `forma_contrato` tinyint(1) NOT NULL,
  `empresa` tinyint(1) NOT NULL,
  `cobrado_en` varchar(256) NOT NULL,
  `observaciones` varchar(256) NOT NULL,
  `numero_factura` char(16) NOT NULL,
  `monto_operacion` decimal(10,2) NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `serial` text NOT NULL,
  `estado_verificado` tinyint(1) NOT NULL,
  `fecha_verificado` datetime NOT NULL,
  `codigo_n` varchar(32) NOT NULL,
  `codigo_n_a` varchar(32) NOT NULL,
  `expediente_c` varchar(32) NOT NULL,
  `evento` char(1) NOT NULL,
  `evento_fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `documento_id` (`documento_id`),
  KEY `contrato_id` (`contrato_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_th_lista_cobros`
--

DROP TABLE IF EXISTS `_th_lista_cobros`;
CREATE TABLE IF NOT EXISTS `_th_lista_cobros` (
  `documento_id` int(11) NOT NULL,
  `credito_id` int(11) NOT NULL,
  `mes` varchar(32) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` date DEFAULT NULL,
  `monto` decimal(15,3) NOT NULL,
  `t_lista_cobros` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_th_sistema`
--

DROP TABLE IF EXISTS `_th_sistema`;
CREATE TABLE IF NOT EXISTS `_th_sistema` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(12) NOT NULL,
  `tipo_eliminacion` int(1) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `motivo` varchar(50) NOT NULL,
  `peticion` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_contratos_estadoejecucion`
--

DROP TABLE IF EXISTS `_tr_contratos_estadoejecucion`;
CREATE TABLE IF NOT EXISTS `_tr_contratos_estadoejecucion` (
  `oidc` varchar(32) NOT NULL,
  `oide` int(2) NOT NULL,
  `observacion` varchar(256) NOT NULL,
  `estatus` tinyint(1) NOT NULL,
  `modificacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_perfilprivilegios`
--

DROP TABLE IF EXISTS `_tr_perfilprivilegios`;
CREATE TABLE IF NOT EXISTS `_tr_perfilprivilegios` (
  `oidp` int(2) NOT NULL,
  `oidb` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_usuariolinaje`
--

DROP TABLE IF EXISTS `_tr_usuariolinaje`;
CREATE TABLE IF NOT EXISTS `_tr_usuariolinaje` (
  `oidu` int(2) NOT NULL,
  `oidl` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_usuarioperfil`
--

DROP TABLE IF EXISTS `_tr_usuarioperfil`;
CREATE TABLE IF NOT EXISTS `_tr_usuarioperfil` (
  `oidu` int(2) NOT NULL,
  `oidp` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_usuarioubicacion`
--

DROP TABLE IF EXISTS `_tr_usuarioubicacion`;
CREATE TABLE IF NOT EXISTS `_tr_usuarioubicacion` (
  `oidu` int(2) NOT NULL,
  `oidb` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_tr_usuariousuario`
--

DROP TABLE IF EXISTS `_tr_usuariousuario`;
CREATE TABLE IF NOT EXISTS `_tr_usuariousuario` (
  `oidu` int(2) NOT NULL,
  `oidb` int(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE `t_responsable` (
	`factura` CHAR(16) NOT NULL COLLATE 'latin1_spanish_ci',
	`cedula` INT(11) NOT NULL,
	`nomina` VARCHAR(255) NOT NULL COLLATE 'latin1_spanish_ci',
	`cobrado_en` VARCHAR(255) NOT NULL COLLATE 'latin1_spanish_ci',
	PRIMARY KEY (`factura`)
)
COLLATE='latin1_spanish_ci'
ENGINE=MyISAM DEFAULT CHARSET=latin1;
