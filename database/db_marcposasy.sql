-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-01-2020 a las 03:22:07
-- Versión del servidor: 10.4.10-MariaDB
-- Versión de PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_marcposasy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL,
  `nombres` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `direccion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `nombres`, `apellidos`, `direccion`, `telefono`, `email`, `created_at`, `updated_at`) VALUES
(31862723, 'Evelyn', 'Rodriguez Obando', 'Cra 96a # 45 - 106', '0323966446', '', '2020-01-03 13:27:09', '2020-01-03 13:27:40'),
(31862755, 'Jennifer', 'Ruiz Rodriguez', 'cra 97 # 45 - 57', '3024605529', 'jennifer1985@gmail.com', '2020-01-05 15:10:35', '2020-01-05 15:10:49'),
(1144198853, 'Miguel Angel', 'Cerquera Rodriguez', 'Cra 96a # 45 - 106', '3137030828', 'mcerquera@programarte.com.co', '2019-12-29 14:43:32', '2019-12-29 14:43:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

DROP TABLE IF EXISTS `factura`;
CREATE TABLE IF NOT EXISTS `factura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `iva` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `neto` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `created_at` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `updated_at` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

DROP TABLE IF EXISTS `marca`;
CREATE TABLE IF NOT EXISTS `marca` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'gretsch', '2019-08-19 23:22:49', '2019-08-19 23:22:50'),
(2, 'casio', '2019-08-20 12:19:11', '2019-08-20 12:19:11'),
(3, 'yamaha', '2020-01-18 17:41:25', '2020-01-18 17:41:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientofactura`
--

DROP TABLE IF EXISTS `movimientofactura`;
CREATE TABLE IF NOT EXISTS `movimientofactura` (
  `factura_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`factura_id`,`producto_id`),
  KEY `producto_id` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foto` blob DEFAULT NULL,
  `descripcion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `marca_id` int(11) NOT NULL,
  `entradas` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `salidas` int(11) DEFAULT NULL,
  `costo` int(20) DEFAULT NULL,
  `precio` int(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marca_id` (`marca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `foto`, `descripcion`, `marca_id`, `entradas`, `stock`, `salidas`, `costo`, `precio`, `created_at`, `updated_at`) VALUES
(1, 0x67756974617272615f677265747363682e706e67, 'Guitarra Electrica', 1, 5, 20, 0, 1500000, 2000000, '2019-08-19 23:23:29', '2020-01-05 20:24:50'),
(8, 0x7069616e6f5f636173696f2e706e67, 'Piano organeta', 2, 5, 20, 0, 260000, 510000, '2019-08-21 02:43:21', '2020-01-06 17:55:59'),
(19, 0x626174657269612d79616d6168612d6769676d616b65722e6a7067, 'Bateria gigmaker', 3, 5, 20, 0, 12000000, 13900000, '2020-01-03 13:18:59', '2020-01-05 20:25:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `permisos` text COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `descripcion`, `permisos`, `created_at`, `updated_at`) VALUES
(1, 'administrador', 'ventas,facturas,ventaspormes,productospormes,usuarios,clientes,productos,marcas,roles', '2020-01-04 21:26:31', '2020-01-04 21:26:32'),
(2, 'vendedor', 'ventas,facturas', '2020-01-05 16:41:01', '2020-01-19 21:28:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombres` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `apellidos` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `foto` blob DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `contraseña` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rol_id` (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombres`, `apellidos`, `email`, `foto`, `estado`, `contraseña`, `rol_id`, `created_at`, `updated_at`) VALUES
(1, 'Miguel Angel', 'Cerquera Rodriguez', 'mcerquera@programarte.com.co', 0x6d696775656c5f616e67656c2e706e67, 1, '$2y$12$Uakwg.iyEiRH/edHpad/U.6dOqETNMLrnOF5zuNz1QLz9wmw18MwC', 1, NULL, '2020-01-19 21:29:54');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_factura-cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  ADD CONSTRAINT `fk_factura-usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `movimientofactura`
--
ALTER TABLE `movimientofactura`
  ADD CONSTRAINT `fk_movimientofactura-factura` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_movimientofactura-producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `fk_producto-marca` FOREIGN KEY (`marca_id`) REFERENCES `marca` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario-rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
