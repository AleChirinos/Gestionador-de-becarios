-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-01-2022 a las 04:08:04
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `1410inventory`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

CREATE TABLE `admin` (
  `id` int(3) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile1` varchar(15) NOT NULL,
  `mobile2` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `role` char(5) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_status` char(1) NOT NULL DEFAULT '1',
  `deleted` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `email`, `mobile1`, `mobile2`, `password`, `role`, `created_on`, `last_login`, `last_seen`, `last_edited`, `account_status`, `deleted`) VALUES
(1, 'Admin', 'Demo', 'demo@1410inc.xyz', '08021111111', '07032222222', '$2y$10$xv9I14OlR36kPCjlTv.wEOX/6Dl7VMuWCl4vCxAVWP1JwYIaw4J2C', 'Super', '2017-01-04 22:19:16', '2022-01-19 20:33:02', '2022-01-19 23:05:49', '2022-01-20 03:05:49', '1', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `asignId` bigint(20) UNSIGNED NOT NULL,
  `becarioName` varchar(50) NOT NULL,
  `becarioCode` varchar(50) NOT NULL,
  `becarioId` varchar(50) NOT NULL,
  `trabajo_name` varchar(100) NOT NULL,
  `trabajo_code` varchar(50) NOT NULL,
  `accomplished` char(1) NOT NULL DEFAULT '0',
  `assignDate` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `becarios`
--

CREATE TABLE `becarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `totalhours` int(6) NOT NULL,
  `checkedhours` int(6) NOT NULL,
  `assignedhours` int(6) NOT NULL,
  `missinghours` int(6) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `becarios`
--

INSERT INTO `becarios` (`id`, `name`, `code`, `totalhours`, `checkedhours`, `assignedhours`, `missinghours`, `dateAdded`, `lastUpdated`) VALUES
(2, 'Patricio Vargos', '51101', 8, 0, 0, 8, '2022-01-18 18:07:58', '2022-01-20 02:59:17'),
(5, 'Nicole Góngora', '51225', 29, 0, 29, 0, '2022-01-19 21:55:04', '2022-01-20 02:17:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client`
--

CREATE TABLE `client` (
  `id` int(3) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile1` varchar(15) NOT NULL,
  `mobile2` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `role` char(5) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_status` char(1) NOT NULL DEFAULT '1',
  `deleted` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `client`
--

INSERT INTO `client` (`id`, `first_name`, `last_name`, `email`, `mobile1`, `mobile2`, `password`, `role`, `created_on`, `last_login`, `last_seen`, `last_edited`, `account_status`, `deleted`) VALUES
(1, 'Rosario', 'Santa Cruz', 'rosasanc@gmail.com', '07032222222', '07032222222', 'qwerty123', 'Super', '2017-01-04 22:19:16', '2018-05-18 16:47:21', '2018-05-18 17:28:09', '2018-05-18 16:28:09', '1', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventlog`
--

CREATE TABLE `eventlog` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event` varchar(200) NOT NULL,
  `eventRowIdOrRef` varchar(20) DEFAULT NULL,
  `eventDesc` text DEFAULT NULL,
  `eventTable` varchar(20) DEFAULT NULL,
  `staffInCharge` bigint(20) UNSIGNED NOT NULL,
  `eventTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `eventlog`
--

INSERT INTO `eventlog` (`id`, `event`, `eventRowIdOrRef`, `eventDesc`, `eventTable`, `staffInCharge`, `eventTime`) VALUES
(1, 'Creación del trabajo', '1', 'Creación del trabajo Limpiar las paredes con horas requeridas 11', 'trabajos', 1, '2022-01-18 14:44:52'),
(2, 'Edición del trabajo', '1', 'El trabajo Limpiar las paredes fue editado', 'trabajos', 1, '2022-01-18 14:45:39'),
(3, 'Inscripción de becario', '1', 'Inscripción del becario Nicole Góngora de código UPB 51225', 'becarios', 1, '2022-01-18 16:52:31'),
(4, 'Modificado de horas de trabajo becario a cumplir', '1', '<p>20 horas a cumplir de trabajo becario para Nicole Góngora fueron establecidas</p>', 'becarios', 1, '2022-01-18 20:11:32'),
(5, 'Creación del trabajo', '2', 'Creación del trabajo Limpiar la mesa con horas requeridas 15', 'trabajos', 1, '2022-01-18 20:14:17'),
(6, 'Inscripción de becario', '2', 'Inscripción del becario Patricio Vargas de código UPB 51100', 'becarios', 1, '2022-01-18 22:07:58'),
(7, 'Edición de becario', '1', 'El becario 51222 fue editado', 'becarios', 1, '2022-01-18 22:08:22'),
(8, 'Modificado de horas de trabajo becario a cumplir', '2', '<p>17 horas a cumplir de trabajo becario para Patricio Vargas fueron establecidas</p>', 'becarios', 1, '2022-01-19 00:54:27'),
(9, 'Inscripción de becario', '3', 'Inscripción del becario Nicole Góngora de código UPB 51225', 'becarios', 1, '2022-01-19 02:35:44'),
(10, 'Modificado de horas de trabajo a cumplir', '1', '<p>22 horas de trabajo de Limpiar las paredes fueron establecidas</p>', 'trabajos', 1, '2022-01-19 02:36:25'),
(11, 'Modificado de horas de trabajo becario a cumplir', '3', '<p>13 horas a cumplir de trabajo becario para Nicole Góngora fueron establecidas</p>', 'becarios', 1, '2022-01-20 00:49:18'),
(12, 'Modificado de horas de trabajo becario a cumplir', '2', '<p>10 horas a cumplir de trabajo becario para Patricio Vargas fueron establecidas</p>', 'becarios', 1, '2022-01-20 00:49:24'),
(13, 'Modificado de horas de trabajo becario a cumplir', '2', '<p>17 horas a cumplir de trabajo becario para Patricio Vargas fueron establecidas</p>', 'becarios', 1, '2022-01-20 00:49:49'),
(14, 'Creación del trabajo', '3', 'Creación del trabajo Limpiar al Alexis con horas requeridas 18', 'trabajos', 1, '2022-01-20 01:13:09'),
(15, 'Inscripción de becario', '4', 'Inscripción del becario Nicole Góngora de código UPB 51225', 'becarios', 1, '2022-01-20 01:18:25'),
(16, 'Modificado de horas de trabajo becario a cumplir', '4', '<p>16 horas a cumplir de trabajo becario para Nicole Góngora fueron establecidas</p>', 'becarios', 1, '2022-01-20 01:18:30'),
(17, 'Modificado de horas de trabajo becario a cumplir', '2', '<p>8 horas a cumplir de trabajo becario para Patricio Vargas fueron establecidas</p>', 'becarios', 1, '2022-01-20 01:18:41'),
(18, 'Edición del trabajo', '2', 'El trabajo Limpiar la cocina fue editado', 'trabajos', 1, '2022-01-20 01:30:04'),
(19, 'Edición del trabajo', '2', 'El trabajo Limpiar la mesa fue editado', 'trabajos', 1, '2022-01-20 01:32:12'),
(20, 'Edición del trabajo', '2', 'El trabajo Limpiar la cocina fue editado', 'trabajos', 1, '2022-01-20 01:32:19'),
(21, 'Edición del trabajo', '2', 'El trabajo Limpiar la pija fue editado', 'trabajos', 1, '2022-01-20 01:32:27'),
(22, 'Edición de becario', '2', 'El becario 51101 fue editado', 'becarios', 1, '2022-01-20 01:53:48'),
(23, 'Inscripción de becario', '5', 'Inscripción del becario Nicole Góngora de código UPB 51225', 'becarios', 1, '2022-01-20 01:55:04'),
(24, 'Modificado de horas de trabajo becario a cumplir', '5', '<p>19 horas a cumplir de trabajo becario para Nicole Góngora fueron establecidas</p>', 'becarios', 1, '2022-01-20 02:16:50'),
(25, 'Modificado de horas de trabajo becario a cumplir', '5', '<p>14 horas a cumplir de trabajo becario para Nicole Góngora fueron establecidas</p>', 'becarios', 1, '2022-01-20 02:17:15'),
(26, 'Edición del trabajo', '2', 'El trabajo Limpiar la mesa fue editado', 'trabajos', 1, '2022-01-20 02:17:42'),
(27, 'Creación del trabajo', '4', 'Creación del trabajo Limpiar el baño con horas requeridas 27', 'trabajos', 1, '2022-01-20 02:47:01'),
(28, 'Creación del trabajo', '5', 'Creación del trabajo Limpiar el cuarto con horas requeridas 22', 'trabajos', 1, '2022-01-20 02:57:24'),
(29, 'Modificado de horas de trabajo becario a cumplir', '2', '<p>8 horas a cumplir de trabajo becario para Patricio Vargos fueron establecidas</p>', 'becarios', 1, '2022-01-20 02:58:16'),
(30, 'Creación del trabajo', '6', 'Creación del trabajo Limpiar la mesa con horas requeridas 5', 'trabajos', 1, '2022-01-20 03:00:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `quantity` int(6) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lk_sess`
--

CREATE TABLE `lk_sess` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `processes`
--

CREATE TABLE `processes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `quantity` int(6) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservations`
--

CREATE TABLE `reservations` (
  `resId` bigint(20) UNSIGNED NOT NULL,
  `ref` varchar(10) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `itemCode` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(6) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `totalMoneySpent` decimal(10,2) NOT NULL,
  `amountTendered` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(10,2) NOT NULL,
  `vatPercentage` decimal(10,2) NOT NULL,
  `vatAmount` decimal(10,2) NOT NULL,
  `changeDue` decimal(10,2) NOT NULL,
  `modeOfPayment` varchar(20) NOT NULL,
  `cust_name` varchar(20) DEFAULT NULL,
  `cust_phone` varchar(15) DEFAULT NULL,
  `cust_email` varchar(50) DEFAULT NULL,
  `resType` char(1) NOT NULL,
  `staffId` bigint(20) UNSIGNED NOT NULL,
  `resDate` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cancelled` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE `supplier` (
  `id` int(3) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile1` varchar(15) NOT NULL,
  `mobile2` varchar(15) NOT NULL,
  `password` char(60) NOT NULL,
  `role` char(20) NOT NULL,
  `created_on` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  `last_edited` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_status` char(1) NOT NULL DEFAULT '1',
  `deleted` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos`
--

CREATE TABLE `trabajos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `workhours` int(6) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transactions`
--

CREATE TABLE `transactions` (
  `transId` bigint(20) UNSIGNED NOT NULL,
  `ref` varchar(10) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `itemCode` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(6) NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `totalMoneySpent` decimal(10,2) NOT NULL,
  `amountTendered` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `discount_percentage` decimal(10,2) NOT NULL,
  `vatPercentage` decimal(10,2) NOT NULL,
  `vatAmount` decimal(10,2) NOT NULL,
  `changeDue` decimal(10,2) NOT NULL,
  `modeOfPayment` varchar(20) NOT NULL,
  `cust_name` varchar(20) DEFAULT NULL,
  `cust_phone` varchar(15) DEFAULT NULL,
  `cust_email` varchar(50) DEFAULT NULL,
  `transType` char(1) NOT NULL,
  `staffId` bigint(20) UNSIGNED NOT NULL,
  `transDate` datetime NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cancelled` char(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile1` (`mobile1`);

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`asignId`);

--
-- Indices de la tabla `becarios`
--
ALTER TABLE `becarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile1` (`mobile1`);

--
-- Indices de la tabla `eventlog`
--
ALTER TABLE `eventlog`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`resId`);

--
-- Indices de la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobile1` (`mobile1`);

--
-- Indices de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transId`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `asignId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `becarios`
--
ALTER TABLE `becarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `client`
--
ALTER TABLE `client`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `eventlog`
--
ALTER TABLE `eventlog`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `processes`
--
ALTER TABLE `processes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservations`
--
ALTER TABLE `reservations`
  MODIFY `resId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transId` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
