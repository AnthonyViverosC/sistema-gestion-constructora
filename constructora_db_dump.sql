SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8mb4;
DROP TABLE IF EXISTS `auditorias`;
CREATE TABLE `auditorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `contrato_id` bigint unsigned DEFAULT NULL,
  `accion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registro_id` bigint unsigned DEFAULT NULL,
  `detalle` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `auditorias_user_id_foreign` (`user_id`),
  KEY `auditorias_contrato_id_foreign` (`contrato_id`),
  CONSTRAINT `auditorias_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `auditorias_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('1', '1', '10', 'actualizar', 'contratos', '10', 'Contrato actualizado: Mario', '2026-04-10 18:50:10', '2026-04-10 18:50:10');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('2', '1', '7', 'crear', 'tareas', '1', 'Tarea creada: Revisar acta', '2026-04-10 18:51:51', '2026-04-10 18:51:51');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('3', '1', '7', 'completar', 'tareas', '1', 'Tarea completada: Revisar acta', '2026-04-10 18:52:08', '2026-04-10 18:52:08');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('4', '1', NULL, 'actualizar', 'perfil', '1', 'Perfil actualizado.', '2026-04-10 20:13:16', '2026-04-10 20:13:16');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('5', '1', NULL, 'solicitar', 'seguridad', '1', 'Solicitud de enlace de recuperacion de contrasena.', '2026-04-10 20:23:15', '2026-04-10 20:23:15');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('6', '1', NULL, 'solicitar', 'seguridad', '1', 'Solicitud de enlace de recuperacion de contrasena.', '2026-04-10 20:23:53', '2026-04-10 20:23:53');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('7', '1', NULL, 'solicitar', 'seguridad', '1', 'Solicitud de enlace de recuperacion de contrasena.', '2026-04-10 20:24:01', '2026-04-10 20:24:01');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('8', '2', NULL, 'solicitar', 'seguridad', '2', 'Solicitud de enlace de recuperacion de contrasena.', '2026-04-10 20:24:09', '2026-04-10 20:24:09');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('9', '1', '10', 'crear', 'tareas', '2', 'Tarea creada: subir seguro', '2026-04-14 16:06:36', '2026-04-14 16:06:36');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('10', '1', '10', 'crear', 'documentos', '6', 'Documento cargado: 12.9.1 David Pasaje- Anthony Viveros.pdf', '2026-04-23 14:01:08', '2026-04-23 14:01:08');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('11', '1', '10', 'actualizar', 'contratos', '10', 'Contrato actualizado: Mario', '2026-04-23 14:02:38', '2026-04-23 14:02:38');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('12', '1', '10', 'actualizar', 'contratos', '10', 'Contrato actualizado: Mario', '2026-04-23 14:04:17', '2026-04-23 14:04:17');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('13', '1', '10', 'completar', 'tareas', '2', 'Tarea completada: subir seguro', '2026-04-23 14:07:51', '2026-04-23 14:07:51');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('14', '1', '10', 'actualizar', 'documentos', '6', 'Documento actualizado: ACTA DE INICIO', '2026-04-23 14:08:29', '2026-04-23 14:08:29');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('15', '1', '7', 'actualizar', 'contratos', '7', 'Contrato actualizado: LP-001-2026', '2026-04-23 14:43:41', '2026-04-23 14:43:41');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('16', '1', '10', 'actualizar', 'contratos', '10', 'Contrato actualizado: Mario', '2026-04-23 16:19:10', '2026-04-23 16:19:10');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('17', '1', '10', 'crear', 'tareas', '3', 'Tarea creada: Acta de inicio', '2026-04-23 16:20:45', '2026-04-23 16:20:45');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('18', '2', '7', 'actualizar', 'documentos', '5', 'Documento actualizado: juanfer', '2026-04-23 16:35:43', '2026-04-23 16:35:43');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('19', '2', '7', 'actualizar', 'documentos', '3', 'Documento actualizado: ACTA DE INICIO', '2026-04-23 16:36:16', '2026-04-23 16:36:16');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('20', '1', NULL, 'actualizar', 'perfil', '1', 'Perfil actualizado.', '2026-04-23 17:20:03', '2026-04-23 17:20:03');
INSERT INTO `auditorias` (`id`, `user_id`, `contrato_id`, `accion`, `modulo`, `registro_id`, `detalle`, `created_at`, `updated_at`) VALUES ('21', '1', '10', 'crear', 'tareas', '4', 'Tarea creada: Verificar pólizas', '2026-04-23 17:39:43', '2026-04-23 17:39:43');
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-3db3236e9e16ad2b3b7da02b0eb0ddcc', 'i:2;', '1777928636');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-3db3236e9e16ad2b3b7da02b0eb0ddcc:timer', 'i:1777928636;', '1777928636');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-96048908c7ee329decbe3d5be343210c', 'i:1;', '1777930065');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-96048908c7ee329decbe3d5be343210c:timer', 'i:1777930065;', '1777930065');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-bd60cfb709400721cab9919e7aea173d', 'i:1;', '1776961440');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-bd60cfb709400721cab9919e7aea173d:timer', 'i:1776961440;', '1776961440');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-dashboard_kpis', 'a:15:{s:14:\"totalContratos\";i:2;s:16:\"contratosActivos\";i:2;s:19:\"contratosPendientes\";i:0;s:20:\"contratosFinalizados\";i:0;s:19:\"contratosCancelados\";i:0;s:30:\"contratosDocumentacionCompleta\";i:0;s:32:\"contratosDocumentacionIncompleta\";i:2;s:15:\"totalDocumentos\";i:3;s:20:\"documentosPendientes\";i:1;s:19:\"documentosAprobados\";i:2;s:20:\"documentosObservados\";i:0;s:20:\"documentosRechazados\";i:0;s:17:\"contratosVencidos\";i:1;s:18:\"contratosPorVencer\";i:1;s:17:\"contratosVigentes\";i:0;}', '1777930306');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-e1581aca17767f8b0db1667d91dc3e08', 'i:2;', '1777928096');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-e1581aca17767f8b0db1667d91dc3e08:timer', 'i:1777928096;', '1777928096');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-f30fd9b8191c65ebe984d43c21b5c962', 'i:5;', '1776961409');
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('laravel-cache-f30fd9b8191c65ebe984d43c21b5c962:timer', 'i:1776961409;', '1776961409');
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `contratos`;
CREATE TABLE `contratos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint unsigned DEFAULT NULL,
  `numero_contrato` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_contrato` date NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `cedula_contratista` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_contratista` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `etiqueta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contratos_numero_contrato_unique` (`numero_contrato`),
  KEY `contratos_created_by_foreign` (`created_by`),
  CONSTRAINT `contratos_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `contratos` (`id`, `created_by`, `numero_contrato`, `fecha_contrato`, `fecha_inicio`, `fecha_fin`, `cedula_contratista`, `nombre_contratista`, `estado`, `etiqueta`, `descripcion`, `created_at`, `updated_at`) VALUES ('7', NULL, 'LP-001-2026', '2026-04-09', '2026-04-08', '2026-04-30', '10181592444', 'CONSTRUCTORA', 'Activo', 'Pendiente', 'CONSTRUCCION', '2026-04-09 12:35:41', '2026-04-23 14:43:41');
INSERT INTO `contratos` (`id`, `created_by`, `numero_contrato`, `fecha_contrato`, `fecha_inicio`, `fecha_fin`, `cedula_contratista`, `nombre_contratista`, `estado`, `etiqueta`, `descripcion`, `created_at`, `updated_at`) VALUES ('10', NULL, 'Mario', '2026-04-21', '2026-04-23', '2026-05-09', '108077465', 'David Pasaje', 'Activo', 'Completo', 'mairtooo', '2026-04-10 14:08:56', '2026-04-23 16:19:10');
DROP TABLE IF EXISTS `documento_observaciones`;
CREATE TABLE `documento_observaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documento_observaciones_documento_id_foreign` (`documento_id`),
  KEY `documento_observaciones_user_id_foreign` (`user_id`),
  CONSTRAINT `documento_observaciones_documento_id_foreign` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documento_observaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `documento_requeridos`;
CREATE TABLE `documento_requeridos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contrato_id` bigint unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `obligatorio` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int unsigned NOT NULL DEFAULT '0',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documento_requeridos_contrato_id_foreign` (`contrato_id`),
  CONSTRAINT `documento_requeridos_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('1', '7', 'Contrato firmado', 'Contrato', '1', '1', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('2', '7', 'Acta de inicio', 'Actos Administrativos', '1', '2', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('3', '7', 'Seguridad social', 'Seguridad Social', '1', '3', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('4', '7', 'Soporte de pago', 'Pagos', '1', '4', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('5', '10', 'Contrato firmado', 'Contrato', '1', '1', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('6', '10', 'Acta de inicio', 'Actos Administrativos', '1', '2', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('7', '10', 'Seguridad social', 'Seguridad Social', '1', '3', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
INSERT INTO `documento_requeridos` (`id`, `contrato_id`, `nombre`, `categoria`, `obligatorio`, `orden`, `descripcion`, `created_at`, `updated_at`) VALUES ('8', '10', 'Soporte de pago', 'Pagos', '1', '4', NULL, '2026-04-10 18:56:23', '2026-04-10 18:56:23');
DROP TABLE IF EXISTS `documento_versiones`;
CREATE TABLE `documento_versiones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `documento_id` bigint unsigned NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `numero_version` int unsigned NOT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tamano` bigint unsigned DEFAULT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documento_versiones_documento_id_numero_version_unique` (`documento_id`,`numero_version`),
  KEY `documento_versiones_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `documento_versiones_documento_id_foreign` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documento_versiones_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `documento_versiones` (`id`, `documento_id`, `uploaded_by`, `numero_version`, `archivo`, `nombre_original`, `extension`, `tamano`, `observacion`, `created_at`, `updated_at`) VALUES ('1', '3', NULL, '1', 'documentos/NdA4fOuoiZx8mIHVLenE1s5q24LzqE7CHEzWLhYR.pdf', NULL, 'pdf', NULL, 'Version inicial migrada desde el documento actual.', '2026-04-09 12:38:31', '2026-04-09 20:38:43');
INSERT INTO `documento_versiones` (`id`, `documento_id`, `uploaded_by`, `numero_version`, `archivo`, `nombre_original`, `extension`, `tamano`, `observacion`, `created_at`, `updated_at`) VALUES ('2', '5', NULL, '1', 'documentos/Cr4q5f8bPPdFRJYjecsrqUk6Qe2LnxnOpMsHpDZB.png', NULL, 'png', NULL, 'Version inicial migrada desde el documento actual.', '2026-04-10 13:47:06', '2026-04-10 13:47:06');
INSERT INTO `documento_versiones` (`id`, `documento_id`, `uploaded_by`, `numero_version`, `archivo`, `nombre_original`, `extension`, `tamano`, `observacion`, `created_at`, `updated_at`) VALUES ('3', '6', '1', '1', 'documentos/2F6ckyJPBPt4x82t50SFTW3jNjmPBOZgxAGXE9hA.pdf', '12.9.1 David Pasaje- Anthony Viveros.pdf', 'pdf', '128061', 'Versión inicial del documento.', '2026-04-23 14:01:08', '2026-04-23 14:01:08');
INSERT INTO `documento_versiones` (`id`, `documento_id`, `uploaded_by`, `numero_version`, `archivo`, `nombre_original`, `extension`, `tamano`, `observacion`, `created_at`, `updated_at`) VALUES ('4', '5', '2', '2', 'documentos/QRfm2DiqdRWRrEE4zACPioQOYlyOGo9Zja3uvs7d.jpg', 'WhatsApp Image 2026-04-21 at 10.26.59 AM.jpeg', 'jpg', '183177', 'Archivo reemplazado desde edición del documento.', '2026-04-23 16:35:43', '2026-04-23 16:35:43');
INSERT INTO `documento_versiones` (`id`, `documento_id`, `uploaded_by`, `numero_version`, `archivo`, `nombre_original`, `extension`, `tamano`, `observacion`, `created_at`, `updated_at`) VALUES ('5', '3', '2', '2', 'documentos/gfO0XTt5pIteUSmePnl05sRZUaOHpEGbXc85sKpl.pdf', '13.3.1- DavidP-AnthonyV.pdf', 'pdf', '148212', 'Archivo reemplazado desde edición del documento.', '2026-04-23 16:36:16', '2026-04-23 16:36:16');
DROP TABLE IF EXISTS `documentos`;
CREATE TABLE `documentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contrato_id` bigint unsigned NOT NULL,
  `uploaded_by` bigint unsigned DEFAULT NULL,
  `nombre_documento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_carga` date DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `etiqueta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documentos_contrato_id_foreign` (`contrato_id`),
  KEY `documentos_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `documentos_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documentos_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `documentos` (`id`, `contrato_id`, `uploaded_by`, `nombre_documento`, `nombre_original`, `archivo`, `categoria`, `fecha_carga`, `estado`, `etiqueta`, `descripcion`, `created_at`, `updated_at`) VALUES ('3', '7', '2', 'ACTA DE INICIO', '13.3.1- DavidP-AnthonyV.pdf', 'documentos/gfO0XTt5pIteUSmePnl05sRZUaOHpEGbXc85sKpl.pdf', 'Contrato', '2026-04-09', 'Pendiente', NULL, NULL, '2026-04-09 12:38:31', '2026-04-23 16:36:16');
INSERT INTO `documentos` (`id`, `contrato_id`, `uploaded_by`, `nombre_documento`, `nombre_original`, `archivo`, `categoria`, `fecha_carga`, `estado`, `etiqueta`, `descripcion`, `created_at`, `updated_at`) VALUES ('5', '7', '2', 'juanfer', 'WhatsApp Image 2026-04-21 at 10.26.59 AM.jpeg', 'documentos/QRfm2DiqdRWRrEE4zACPioQOYlyOGo9Zja3uvs7d.jpg', 'Contrato', '2026-04-24', 'Aprobado', NULL, 'prueba', '2026-04-10 13:47:06', '2026-04-23 16:35:43');
INSERT INTO `documentos` (`id`, `contrato_id`, `uploaded_by`, `nombre_documento`, `nombre_original`, `archivo`, `categoria`, `fecha_carga`, `estado`, `etiqueta`, `descripcion`, `created_at`, `updated_at`) VALUES ('6', '10', '1', 'ACTA DE INICIO', '12.9.1 David Pasaje- Anthony Viveros.pdf', 'documentos/2F6ckyJPBPt4x82t50SFTW3jNjmPBOZgxAGXE9hA.pdf', 'Seguridad Social', '2026-04-24', 'Aprobado', 'Pendiente', 'Seguro social', '2026-04-23 14:01:08', '2026-04-23 14:08:29');
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2026_03_13_020758_create_contratos_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2026_04_06_234829_create_documentos_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2026_04_09_171004_add_nombre_original_to_documentos_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2026_04_09_184112_add_fechas_vigencia_to_contratos_table', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2026_04_09_211744_add_rol_to_users_table', '5');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2026_04_10_091700_add_uploaded_by_to_documentos_table', '6');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_04_10_093000_add_created_by_to_contratos_table', '7');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_04_10_094000_add_etiqueta_to_contratos_and_documentos', '8');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_04_10_094100_create_tareas_table', '8');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_04_10_094200_create_auditorias_table', '8');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2026_04_10_100500_add_notified_at_to_tareas_table', '9');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2026_04_10_101500_add_contrato_id_to_auditorias_table', '10');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2026_04_10_110000_create_documento_versiones_table', '11');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('17', '2026_04_10_111000_create_documento_observaciones_table', '12');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('18', '2026_04_10_112000_create_documento_requeridos_table', '13');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('19', '2026_04_10_151000_create_password_reset_tokens_table', '14');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('20', '2026_04_22_100000_create_notificaciones_table', '15');
DROP TABLE IF EXISTS `notificaciones`;
CREATE TABLE `notificaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `tarea_id` bigint unsigned DEFAULT NULL,
  `tipo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `canal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sistema',
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `fecha_evento` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notificaciones_user_id_foreign` (`user_id`),
  KEY `notificaciones_tarea_id_foreign` (`tarea_id`),
  CONSTRAINT `notificaciones_tarea_id_foreign` FOREIGN KEY (`tarea_id`) REFERENCES `tareas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notificaciones_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `notificaciones` (`id`, `user_id`, `tarea_id`, `tipo`, `canal`, `titulo`, `mensaje`, `estado`, `fecha_evento`, `sent_at`, `created_at`, `updated_at`) VALUES ('1', '5', '2', 'tarea_vencida', 'correo', 'Tarea vencida', 'La tarea \"subir seguro\" del contrato Mario tiene fecha limite 14/04/2026.', 'enviada', '2026-04-14 00:00:00', '2026-04-23 00:21:52', '2026-04-23 00:21:52', '2026-04-23 00:21:52');
INSERT INTO `notificaciones` (`id`, `user_id`, `tarea_id`, `tipo`, `canal`, `titulo`, `mensaje`, `estado`, `fecha_evento`, `sent_at`, `created_at`, `updated_at`) VALUES ('2', '1', '4', 'tarea_vencida', 'correo', 'Tarea vencida', 'La tarea \"Verificar pólizas\" del contrato Mario tiene fecha limite 02/05/2026.', 'enviada', '2026-05-02 00:00:00', '2026-05-04 21:03:40', '2026-05-04 21:03:40', '2026-05-04 21:03:40');
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES ('admin@salazardiaz.com', '$2y$12$tdfHOn.UknmB.RSGrAUQ7udBOb8e.j6/KX7FFER0kcKDk6KFdJHzi', '2026-04-10 20:23:15');
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES ('gestor@empresa.com', '$2y$12$8N5z26kDSiA7oOAI6JLeHucBHQbflEECGSS9YkbCKMz27CVMB2U72', '2026-04-10 20:24:09');
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('BkpzVV4XoMn71v0zThAh77K17hUH46HBiXeIjP4o', '7', '127.0.0.1', 'curl/8.19.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDc4bnZ3RjFBV3l5MmZNTmdMQ1NSRVlCdWViZXg2NXdWZ084SGlIZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODc2NS9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Nzt9', '1777929114');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('BoyN1cr0YVnGOB89lnbTcDupLWVtM76Bioy7MoqS', '6', '127.0.0.1', 'curl/8.19.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWVBJbUc2N1AwRVd3alZqd3oweUxITU1UMnh2dmNQb0RlTjlEZ1dRaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODc2NS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjc6InN1Y2Nlc3MiO31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjY7czo3OiJzdWNjZXNzIjtzOjIyOiJCaWVudmVuaWRvIGFsIHNpc3RlbWEuIjt9', '1777928577');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('HSCmv1H7Byatsm7KKFsxudzilL646B06C6Z48S7U', NULL, '127.0.0.1', 'curl/8.19.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiemE5UWZCVk9HVlROUmpYaWpBM3Vlc3dGQTZwMm12UGE4ZzdrVThDSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODc2Ni9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1777929981');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('IjxhmVutZqAcR09z9ZpZb0x3Pb7YAaPDfvjPBCsF', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibGp0c1p4U1hIQkY0M2dCdk5qOElYbUdTcTZ4V2ZwWDFRWkp2QXdCQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ub3RpZmljYWNpb25lcyI7czo1OiJyb3V0ZSI7czoyMDoibm90aWZpY2FjaW9uZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', '1777929241');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('LiFL4LJQX2MfwOF3mwtZQNYXKMct5TMedkbFiUv6', NULL, '127.0.0.1', 'curl/8.19.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicFdrRlZKSFlyNnYxemNadjBSN1lTb1BjZWpuOGdBRTVYRWJIaHB1eiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODc2NS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1777929100');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('lt2tuSObUWEvC2UdmQYMi0SvMdTeTRa04RRiRqmv', NULL, '127.0.0.1', 'curl/8.19.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibTAwemhxYVR0bW53VWNVTWgwS1lQa0VqOW8yaEFER1V3VTJEajNCSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4NzY2L2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjg3NjYvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1777930010');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('M0A6U1sPRRb1Lw5ZAnO0LhmQqGsGpVc5Tv1Krulg', '1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMFR3MFN0dHdoeDRGb0t1UDBFZEZmaGRSaUl1Y2VIbU94dnBtVnM3aiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZXJmaWwiO3M6NToicm91dGUiO3M6MTE6InBlcmZpbC5zaG93Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', '1776997756');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('qJ4CH9lJio2gL1junTB5aWJj9WEJ4HzINgf67XZX', NULL, '127.0.0.1', 'curl/8.19.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWhQT0FJUHdMQWFOVGFYSzBwRWhUNkhBeGNHMUE4Z1RCaXV4eHlteCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODc2NS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1777928554');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('ZbdR3gTj6rjmBXmw9QpIgy02802nNhP7S16pelVA', NULL, '127.0.0.1', 'curl/8.19.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaWJVbGx5MG94QkJXQVJjVzg0c1V6WWhmb05YYlM2V3F5TUxGREx2VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4NzY1L2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vMTI3LjAuMC4xOjg3NjUvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fX0=', '1777928634');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('zir1KcZSiMOSWOlKNx49VUtVQEVMcyPZjHIeTWde', NULL, '127.0.0.1', 'curl/8.19.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXJteHUwcENLSjlNd3EySklVRXZRT3FSWXZrZTEwNWVBdThlaDVMMiI7czo3OiJzdWNjZXNzIjtzOjMwOiJTZXNpw7NuIGNlcnJhZGEgY29ycmVjdGFtZW50ZS4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6MzoibmV3IjthOjA6e31zOjM6Im9sZCI7YToxOntpOjA7czo3OiJzdWNjZXNzIjt9fX0=', '1777930010');
DROP TABLE IF EXISTS `tareas`;
CREATE TABLE `tareas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contrato_id` bigint unsigned NOT NULL,
  `documento_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `assigned_to` bigint unsigned DEFAULT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `fecha_limite` date NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `completed_at` timestamp NULL DEFAULT NULL,
  `notified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tareas_contrato_id_foreign` (`contrato_id`),
  KEY `tareas_documento_id_foreign` (`documento_id`),
  KEY `tareas_created_by_foreign` (`created_by`),
  KEY `tareas_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `tareas_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tareas_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tareas_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tareas_documento_id_foreign` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `tareas` (`id`, `contrato_id`, `documento_id`, `created_by`, `assigned_to`, `titulo`, `descripcion`, `fecha_limite`, `estado`, `completed_at`, `notified_at`, `created_at`, `updated_at`) VALUES ('1', '7', '3', '1', '5', 'Revisar acta', 'Revisar  acta de inicio del proyecto', '2026-04-10', 'Completada', '2026-04-10 18:52:08', NULL, '2026-04-10 18:51:51', '2026-04-10 18:52:08');
INSERT INTO `tareas` (`id`, `contrato_id`, `documento_id`, `created_by`, `assigned_to`, `titulo`, `descripcion`, `fecha_limite`, `estado`, `completed_at`, `notified_at`, `created_at`, `updated_at`) VALUES ('2', '10', NULL, '1', '5', 'subir seguro', 'social', '2026-04-14', 'Completada', '2026-04-23 14:07:51', '2026-04-23 00:21:52', '2026-04-14 16:06:36', '2026-04-23 14:07:51');
INSERT INTO `tareas` (`id`, `contrato_id`, `documento_id`, `created_by`, `assigned_to`, `titulo`, `descripcion`, `fecha_limite`, `estado`, `completed_at`, `notified_at`, `created_at`, `updated_at`) VALUES ('3', '10', '6', '1', '5', 'Acta de inicio', 'subir la acta de inicio', '2026-05-09', 'Pendiente', NULL, NULL, '2026-04-23 16:20:45', '2026-04-23 16:20:45');
INSERT INTO `tareas` (`id`, `contrato_id`, `documento_id`, `created_by`, `assigned_to`, `titulo`, `descripcion`, `fecha_limite`, `estado`, `completed_at`, `notified_at`, `created_at`, `updated_at`) VALUES ('4', '10', '6', '1', '1', 'Verificar pólizas', NULL, '2026-05-02', 'Pendiente', NULL, '2026-05-04 21:03:40', '2026-04-23 17:39:43', '2026-05-04 21:03:40');
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `rol`) VALUES ('1', 'Administrador', 'admin@salazardiaz.com', NULL, '$2y$12$SChNGgXR7q50HQOrFTRXEeFrEjazURlW3GQ4WbxxdfNc5KbMIMAWS', 'j8S3JFCPlI5PKvaclay17DG6g14UVaWh78hK3cxBy63aOPcWxuHjvWRxCpr6', '2026-04-09 21:02:29', '2026-04-23 17:20:03', 'admin');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `rol`) VALUES ('2', 'Usuario Gestor', 'gestor@empresa.com', NULL, '$2y$12$JpqMNz7tNZLYgWjXsRmbf.a8bOurjV6irVNIqADW0DkwdASPsTQMy', NULL, '2026-04-09 21:37:13', '2026-04-10 13:16:42', 'gestor');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `rol`) VALUES ('3', 'Usuario Consulta', 'consulta@empresa.com', NULL, '$2y$12$hrVeT/rvQqvj8WjOpssSluhx9uWUCmFtZpURSUcfiDAxeXxpRQLTS', NULL, '2026-04-09 21:37:39', '2026-04-10 13:18:51', 'consulta');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `rol`) VALUES ('5', 'Juanfer', 'juanfer@gmail.com', NULL, '$2y$12$gGFTuaBVGykwEtnqBplcs.2dPGeH1j/xiPQsJH.U7oKZVb0mFGqcy', NULL, '2026-04-10 14:11:51', '2026-04-10 14:11:51', 'gestor');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `rol`) VALUES ('7', 'Administrador Local', 'admin@local.test', '2026-05-04 21:11:03', '$2y$12$otUaY3gRAL5mkCArieVKCuRNa6Y/G3j.F8TvlrYuH0IhyvIwHzdxW', NULL, '2026-05-04 21:11:03', '2026-05-04 21:11:03', 'admin');
SET FOREIGN_KEY_CHECKS=1;
