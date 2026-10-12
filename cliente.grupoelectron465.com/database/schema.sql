-- Tabla para usuarios del portal
CREATE TABLE IF NOT EXISTS `usuarios_portal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documento_id` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `codigo_verificacion` varchar(255) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `email_verificado` tinyint(1) DEFAULT 0,
  `ultimo_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_id` (`documento_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

