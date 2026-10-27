-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ebim
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `asignaturas`
--

DROP TABLE IF EXISTS `asignaturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asignaturas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asignaturas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaturas`
--

LOCK TABLES `asignaturas` WRITE;
/*!40000 ALTER TABLE `asignaturas` DISABLE KEYS */;
INSERT INTO `asignaturas` VALUES (1,'Instrumento de Especialidad','2026-10-26 17:26:51','2026-10-26 17:26:51'),(2,'Práctica Colectiva Instrumental','2026-10-26 17:26:51','2026-10-26 17:26:51'),(3,'Sonido y Acústica','2026-10-26 17:26:51','2026-10-26 17:26:51'),(4,'Lenguaje Musical','2026-10-26 17:26:51','2026-10-26 17:26:51'),(5,'Músicas y Culturas','2026-10-26 17:26:51','2026-10-26 17:26:51'),(6,'Taller de Lenguaje','2026-10-26 17:26:51','2026-10-26 17:26:51'),(7,'Idioma Originario','2026-10-26 17:26:51','2026-10-26 17:26:51'),(8,'Organología y Lutería','2026-10-26 17:26:51','2026-10-26 17:26:51'),(9,'Metodología de la Investigación','2026-10-26 17:26:51','2026-10-26 17:26:51'),(10,'Psicopedagogía de la Música','2026-10-26 17:26:51','2026-10-26 17:26:51'),(11,'Armonía y Teclado','2026-10-26 17:26:51','2026-10-26 17:26:51'),(12,'Formas e Identidades Musicales Bolivianas','2026-10-26 17:26:51','2026-10-26 17:26:51'),(13,'Gestión Cultural','2026-10-26 17:26:51','2026-10-26 17:26:51'),(14,'Nuevas Tecnologías e Inglés Técnico','2026-10-26 17:26:51','2026-10-26 17:26:51'),(15,'Composición, Análisis y Arreglos','2026-10-26 17:26:51','2026-10-26 17:26:51'),(16,'Literatura, Artes Escénicas y Audiovisuales','2026-10-26 17:26:51','2026-10-26 17:26:51'),(17,'Seminario','2026-10-26 17:26:51','2026-10-26 17:26:51'),(18,'Instrumento principal','2026-10-26 17:26:51','2026-10-26 17:26:51'),(20,'Práctica de conjunto','2026-10-26 17:26:51','2026-10-26 17:26:51'),(21,'Historia de la música boliviana','2026-10-26 17:26:51','2026-10-26 17:26:51'),(22,'Instrumento Complementario','2026-10-26 20:45:42','2026-10-26 20:45:42'),(23,'Lenguaje Musical 2','2026-10-26 20:45:45','2026-10-26 20:45:45'),(24,'Informática aplicada a la música','2026-10-26 20:45:47','2026-10-26 20:45:47'),(25,'Práctica Colectiva','2026-10-26 20:45:49','2026-10-26 20:45:49'),(26,'Historia de la música','2026-10-26 20:46:06','2026-10-26 20:46:06'),(27,'Práctica Coral','2026-10-26 20:46:09','2026-10-26 20:46:09');
/*!40000 ALTER TABLE `asignaturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contenidos`
--

DROP TABLE IF EXISTS `contenidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contenidos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `cuerpo` longtext NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contenidos_clave_unique` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contenidos`
--

LOCK TABLES `contenidos` WRITE;
/*!40000 ALTER TABLE `contenidos` DISABLE KEYS */;
INSERT INTO `contenidos` VALUES (1,'historia','Nuestra historia','La Escuela Boliviana Intercultural de Música (EBIM) fue creada mediante Decreto Supremo N.º 0009 del 14 de febrero de 2009, durante el gobierno del presidente Evo Morales Ayma, e inaugurada el 8 de octubre de 2009. Nació a solicitud de la Asociación de Bandas de Músicos de Oruro y es la primera institución de su género en Latinoamérica.\r\n\r\nLa escuela tiene como finalidad revalorizar y recuperar los saberes musicales de las 36 nacionalidades y pueblos del Estado Plurinacional de Bolivia, formando músicos con identidad intercultural. Se encuentra bajo tuición del Ministerio de Educación, en coordinación con el Ministerio de Culturas.\r\n\r\nInició sus actividades con 80 estudiantes y 15 docentes en instalaciones del centro educativo Marcelo Quiroga Santa Cruz, y actualmente funciona en su sede de la Zona de Capachos, Carretera Oruro – Cochabamba Km 13.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15'),(2,'historia_resumen','Nuestra historia','La EBIM nació a solicitud de la Asociación de Bandas de Músicos Profesionales de Oruro (ABMPO), que agrupa a más de 5.000 músicos y necesitaba una formación académica con grados y bases curriculares para su desempeño profesional.\r\n\r\nEl 14 de febrero de 2009, en el Acto Central del VII Festival de Bandas en la Av. Cívica de Oruro, se determinó mediante D.S. 0009 su creación, para formar músicos profesionales a partir de la música originaria, tradicional, popular y de tradición escrita.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15'),(3,'cronologia','Nuestra historia','2009 | Creación | La EBIM fue creada el 14 de febrero mediante Decreto Supremo N.º 0009, a solicitud de la Asociación de Bandas de Músicos Profesionales de Oruro (ABMPO).\r\n2009 | Inauguración | El 8 de octubre se inaugura como la primera escuela de formación musical intercultural de Latinoamérica.\r\n2009 | Primeras clases | Inició sus actividades con 80 estudiantes y 15 docentes en el centro educativo Marcelo Quiroga Santa Cruz.\r\n2011 | Terreno propio | La Ley N.º 085 autoriza la transferencia gratuita de 40.000 m² en Moxuma Capachos para construir la infraestructura de la escuela.\r\n2013 | Resolución Ministerial | La Resolución Ministerial N.º 606/2013 consolida la EBIM dentro de las Escuelas Bolivianas Interculturales del Ministerio de Educación.\r\n2016 | Título de Licenciatura | Se valida la formación en Técnico Medio, Técnico Superior y Licenciatura en Música, con las menciones de Intérprete Instrumentista e Investigación Musical.\r\n2026 | Nueva etapa | Tras 17 años de actividad ininterrumpida, la escuela inicia una nueva administración con la designación del rector Arturo César Herrera Camargo.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15'),(4,'sobre_intro','Sobre EBIM','La primera institución de formación musical intercultural de Latinoamérica: conoce su historia, su misión y los valores que la sostienen.',NULL,'2026-10-20 17:11:10','2026-10-25 20:27:00'),(5,'rector','Arturo César Herrera Camargo','Músico, compositor y antropólogo orureño. Charanguista desde los 8 años, especializado en el charango k\'alanpeador y en el charango estándar.\r\n\r\nDesde 1982 es charanguista y Director Artístico del Grupo Andino, con el que grabó 20 discos y realizó giras por Europa, Estados Unidos y Latinoamérica. Obtuvo la medalla Charango de Bronce en Aiquile y en 2016 la Cámara de Diputados le otorgó la distinción Honor al Mérito Cultural.\r\n\r\nEs docente de la especialidad de Charango y de Gestión Cultural en la EBIM, y miembro de SOBODAYCOM, ABAIEM y la Sociedad Boliviana del Charango.','/assets/ebim/rectores/arturo-herrera.jpg','2026-10-20 17:11:10','2026-10-25 21:41:15'),(6,'mision','Misión','Formar profesionales de la música con identidad intercultural, revalorizando y recuperando los saberes musicales de las naciones y pueblos indígena originario campesinos del Estado Plurinacional de Bolivia, contribuyendo a la descolonización y a la integración cultural del país.',NULL,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(7,'vision','Visión','Consolidarnos como la principal institución de formación musical intercultural de Bolivia, reconocida nacional e internacionalmente por la excelencia académica y por la valoración de la diversidad cultural y musical boliviana.',NULL,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(8,'valores','Valores','Interculturalidad y respeto por la diversidad cultural.\r\nIdentidad y valoración de los saberes musicales bolivianos.\r\nExcelencia académica y disciplina artística.\r\nCompromiso social con las bandas y comunidades de Oruro y de Bolivia.\r\nTrabajo en equipo y práctica de conjunto.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15'),(9,'requisitos','Requisitos de inscripción','Solicitud de inscripción dirigida a la dirección de la escuela.\r\nCertificado de nacimiento original y copia.\r\nCédula de identidad original y copia.\r\nDos fotografías tamaño 4x4 (fondo rojo).\r\nCertificado de estudios del nivel anterior (según el nivel al que postula).\r\nFormulario de inscripción completo.\r\nComprobante original del depósito bancario a la cuenta de la EBIM.\r\nAprobar la evaluación de aptitud musical.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15'),(10,'pasos_inscripcion','Pasos del proceso de inscripción','Completa la solicitud y el formulario | Imprime los dos documentos, llénalos a mano con letra legible y fírmalos.\r\nRealiza el depósito bancario | Deposita en la cuenta de la EBIM y guarda el comprobante original: se entrega junto con el resto de los documentos.\r\nPrepara la evaluación de aptitud musical | Repasa el material teórico y práctico de tu instrumento antes de la audición.\r\nPresenta tu carpeta en secretaría | Entrega en la sede de Capachos la solicitud, el formulario, los documentos de la lista y el comprobante de depósito, en horario de oficina.\r\nRinde la evaluación de aptitud musical | La audición se coordina con secretaría y con su resultado se define tu admisión.\r\nConfirma tu inscripción | Con la documentación completa y la aptitud aprobada, secretaría confirma tu inscripción y te asigna curso y horario.',NULL,'2026-10-20 17:11:10','2026-10-25 21:41:15');
/*!40000 ALTER TABLE `contenidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cursos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `orden` int(10) unsigned NOT NULL DEFAULT 0,
  `estado_horario` varchar(255) NOT NULL DEFAULT 'borrador',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `horario_enviado_en` timestamp NULL DEFAULT NULL,
  `horario_enviado_por` bigint(20) unsigned DEFAULT NULL,
  `horario_revisado_en` timestamp NULL DEFAULT NULL,
  `horario_revisado_por` bigint(20) unsigned DEFAULT NULL,
  `horario_observacion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cursos_nombre_unique` (`nombre`),
  KEY `cursos_horario_enviado_por_foreign` (`horario_enviado_por`),
  KEY `cursos_horario_revisado_por_foreign` (`horario_revisado_por`),
  KEY `cursos_estado_horario_index` (`estado_horario`),
  CONSTRAINT `cursos_horario_enviado_por_foreign` FOREIGN KEY (`horario_enviado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cursos_horario_revisado_por_foreign` FOREIGN KEY (`horario_revisado_por`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (1,'1 \"A\"',1,'aprobado','2026-10-20 17:11:10','2026-10-25 21:43:07','2026-10-25 21:36:25',NULL,'2026-10-25 21:43:07',NULL,NULL),(2,'2 \"A\"',3,'aprobado','2026-10-20 17:11:10','2026-10-26 20:46:21','2026-10-26 20:46:15',NULL,'2026-10-26 20:46:21',NULL,NULL),(3,'3 \"A\"',5,'borrador','2026-10-20 17:11:10','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL),(4,'1 \"B\"',2,'borrador','2026-10-25 21:29:29','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL),(5,'2 \"B\"',4,'borrador','2026-10-25 21:29:29','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL),(6,'3 \"B\"',6,'borrador','2026-10-25 21:29:29','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL),(7,'4 \"A\"',7,'borrador','2026-10-25 21:29:29','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL),(8,'5 \"A\"',8,'borrador','2026-10-25 21:29:29','2026-10-25 21:29:29',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docentes`
--

DROP TABLE IF EXISTS `docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docentes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `cargo` varchar(255) NOT NULL,
  `especialidad` varchar(255) NOT NULL,
  `formacion` text NOT NULL,
  `experiencia` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `encuadre` tinyint(3) unsigned NOT NULL DEFAULT 50,
  `orden` int(10) unsigned NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `docentes_activo_orden_index` (`activo`,`orden`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docentes`
--

LOCK TABLES `docentes` WRITE;
/*!40000 ALTER TABLE `docentes` DISABLE KEYS */;
INSERT INTO `docentes` VALUES (1,'Lic. Denis Pérez Garnica','Director Académico','Dirección académica','Formación profesional a completar por la dirección de la escuela.','Director Académico de la EBIM. Coordina el desarrollo académico de la escuela y sus especialidades instrumentales.','/assets/ebim/docentes/dennis.jpg',50,1,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(2,'Lic. Jesús Elías Lucero','Docente de Práctica Coral','Práctica coral y dirección de coro','Licenciado en Música.','Docente de práctica coral en la EBIM y director de su Orquesta de Cámara. Ha dirigido presentaciones de la escuela y de agrupaciones musicales de Oruro.','/assets/ebim/docentes/jesus-elias.jpg',50,2,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(3,'Mstro. Juan Carlos Machicado','Docente de Guitarra','Guitarra clásica','Maestro guitarrista y catedrático de música.','Reconocido guitarrista y formador de varias generaciones de guitarristas bolivianos. Dirigió la Orquesta Juvenil de Guitarras y ha participado en festivales internacionales de guitarra.','/assets/ebim/docentes/juan-carlos-machicado.jpg',0,3,1,'2026-10-20 17:11:10','2026-10-25 20:18:27'),(4,'Lic. Karen Villca','Docente de Lenguaje Musical','Lenguaje musical y formación auditiva','Licenciada en Música.','Docente de lenguaje musical en la EBIM, a cargo de la formación teórica y auditiva de los estudiantes.','/assets/ebim/docentes/karen-villca.jpg',0,4,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(5,'Lic. Ricardo Choque','Docente de Práctica Colectiva','Charango','Licenciado en Música.','Docente de práctica colectiva en la EBIM, dedicado al trabajo de conjunto y al repertorio de las bandas y agrupaciones de Oruro. Intérprete de charango.','/assets/ebim/docentes/ricardo-choque.jpg',40,5,1,'2026-10-20 17:11:10','2026-10-25 20:18:27'),(6,'Lic. Ramiro Gutiérrez Condori','Antropólogo · Docente','Antropología de la música e investigación cultural','Licenciado en Antropología, músico e investigador de la música boliviana.','Docente de la EBIM, intérprete de instrumentos de viento nativo y autor de investigaciones sobre la música andina y boliviana.','/assets/ebim/docentes/ramiro-gutierrez.jpg',50,6,1,'2026-10-20 17:11:10','2026-10-20 17:11:10');
/*!40000 ALTER TABLE `docentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidades`
--

DROP TABLE IF EXISTS `especialidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `especialidades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `orden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `especialidades_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidades`
--

LOCK TABLES `especialidades` WRITE;
/*!40000 ALTER TABLE `especialidades` DISABLE KEYS */;
INSERT INTO `especialidades` VALUES (1,'Charango',1,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(2,'Guitarra',2,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(3,'Piano',3,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(4,'Clarinete',4,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(5,'Saxofón',5,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(6,'Trompeta',6,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(7,'Trombón',7,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(8,'Barítono-Bajo',8,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(9,'Tuba',9,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(10,'Aerófonos andinos',10,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(11,'Batería y Percusión',11,'2026-10-26 17:26:51','2026-10-26 17:26:51'),(12,'Canto',12,'2026-10-26 17:26:51','2026-10-26 17:26:51');
/*!40000 ALTER TABLE `especialidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios`
--

DROP TABLE IF EXISTS `horarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `curso_id` bigint(20) unsigned NOT NULL,
  `asignatura_id` bigint(20) unsigned NOT NULL,
  `dia` varchar(255) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `docente` varchar(255) NOT NULL,
  `ambiente` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `horarios_curso_dia_inicio_unique` (`curso_id`,`dia`,`hora_inicio`),
  KEY `horarios_asignatura_id_foreign` (`asignatura_id`),
  CONSTRAINT `horarios_asignatura_id_foreign` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  CONSTRAINT `horarios_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=226 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios`
--

LOCK TABLES `horarios` WRITE;
/*!40000 ALTER TABLE `horarios` DISABLE KEYS */;
INSERT INTO `horarios` VALUES (1,1,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(2,1,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(3,1,3,'Lunes','11:30:00','13:00:00','Docente de Sonido y Acústica',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(4,1,4,'Martes','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(5,1,5,'Martes','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(6,1,6,'Martes','11:30:00','13:00:00','Docente de Taller de Lenguaje',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(7,1,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(8,1,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(9,1,3,'Miércoles','11:30:00','13:00:00','Docente de Sonido y Acústica',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(10,1,4,'Jueves','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(11,1,5,'Jueves','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(12,1,6,'Jueves','11:30:00','13:00:00','Docente de Taller de Lenguaje',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(13,1,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(14,1,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(15,1,7,'Viernes','11:30:00','13:00:00','Docente de Idioma Originario',NULL,'2026-10-25 21:29:30','2026-10-26 16:09:49'),(121,4,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(122,4,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(123,4,3,'Lunes','11:30:00','13:00:00','Docente de Sonido y Acústica',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(124,4,4,'Martes','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(125,4,5,'Martes','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(126,4,6,'Martes','11:30:00','13:00:00','Docente de Taller de Lenguaje',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(127,4,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(128,4,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(129,4,3,'Miércoles','11:30:00','13:00:00','Docente de Sonido y Acústica',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(130,4,4,'Jueves','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(131,4,5,'Jueves','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(132,4,6,'Jueves','11:30:00','13:00:00','Docente de Taller de Lenguaje',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(133,4,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(134,4,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(135,4,7,'Viernes','11:30:00','13:00:00','Docente de Idioma Originario',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(136,2,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:40'),(137,2,22,'Lunes','10:00:00','11:30:00','Docente de Instrumento Complementario',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:42'),(138,2,23,'Lunes','11:30:00','13:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:45'),(139,2,24,'Martes','08:30:00','10:00:00','Docente de Informática',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:47'),(140,2,25,'Martes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:49'),(141,2,8,'Martes','11:30:00','13:00:00','Docente de Organología y Lutería',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:52'),(142,2,8,'Miércoles','08:30:00','10:00:00','Docente de Organología y Lutería',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:54'),(143,2,1,'Miércoles','10:00:00','11:30:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:57'),(144,2,23,'Miércoles','11:30:00','13:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 20:45:59'),(145,2,24,'Jueves','08:30:00','10:00:00','Docente de Informática',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:02'),(146,2,25,'Jueves','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:04'),(147,2,26,'Jueves','11:30:00','13:00:00','Docente de Historia de la Música',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:07'),(148,2,27,'Viernes','08:30:00','10:00:00','Docente de Práctica Coral',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:09'),(149,2,1,'Viernes','10:00:00','11:30:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:11'),(150,2,4,'Viernes','11:30:00','13:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 20:46:13'),(151,5,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(152,5,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(153,5,8,'Lunes','11:30:00','13:00:00','Docente de Organología y Lutería',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(154,5,4,'Martes','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(155,5,5,'Martes','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(156,5,9,'Martes','11:30:00','13:00:00','Docente de Metodología',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(157,5,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(158,5,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(159,5,8,'Miércoles','11:30:00','13:00:00','Docente de Organología y Lutería',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(160,5,4,'Jueves','08:30:00','10:00:00','Docente de Lenguaje Musical',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(161,5,5,'Jueves','10:00:00','11:30:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(162,5,9,'Jueves','11:30:00','13:00:00','Docente de Metodología',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(163,5,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(164,5,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(165,5,7,'Viernes','11:30:00','13:00:00','Docente de Idioma Originario',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(166,3,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(167,3,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(168,3,10,'Lunes','11:30:00','13:00:00','Docente de Psicopedagogía',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(169,3,5,'Martes','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(170,3,11,'Martes','10:00:00','11:30:00','Docente de Armonía y Teclado',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(171,3,12,'Martes','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(172,3,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(173,3,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(174,3,10,'Miércoles','11:30:00','13:00:00','Docente de Psicopedagogía',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(175,3,5,'Jueves','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(176,3,11,'Jueves','10:00:00','11:30:00','Docente de Armonía y Teclado',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(177,3,12,'Jueves','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(178,3,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(179,3,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(180,3,13,'Viernes','11:30:00','13:00:00','Docente de Gestión Cultural',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(181,6,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(182,6,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(183,6,10,'Lunes','11:30:00','13:00:00','Docente de Psicopedagogía',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(184,6,5,'Martes','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(185,6,11,'Martes','10:00:00','11:30:00','Docente de Armonía y Teclado',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(186,6,12,'Martes','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(187,6,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(188,6,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(189,6,10,'Miércoles','11:30:00','13:00:00','Docente de Psicopedagogía',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(190,6,5,'Jueves','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(191,6,11,'Jueves','10:00:00','11:30:00','Docente de Armonía y Teclado',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(192,6,12,'Jueves','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(193,6,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(194,6,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(195,6,13,'Viernes','11:30:00','13:00:00','Docente de Gestión Cultural',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(196,7,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(197,7,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(198,7,12,'Lunes','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(199,7,5,'Martes','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(200,7,14,'Martes','10:00:00','11:30:00','Docente de Nuevas Tecnologías',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(201,7,15,'Martes','11:30:00','13:00:00','Docente de Composición',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(202,7,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(203,7,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(204,7,12,'Miércoles','11:30:00','13:00:00','Docente de Formas Musicales',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(205,7,5,'Jueves','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(206,7,14,'Jueves','10:00:00','11:30:00','Docente de Nuevas Tecnologías',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(207,7,15,'Jueves','11:30:00','13:00:00','Docente de Composición',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(208,7,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(209,7,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(210,7,13,'Viernes','11:30:00','13:00:00','Docente de Gestión Cultural',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(211,8,1,'Lunes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(212,8,2,'Lunes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(213,8,14,'Lunes','11:30:00','13:00:00','Docente de Nuevas Tecnologías',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(214,8,5,'Martes','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(215,8,15,'Martes','10:00:00','11:30:00','Docente de Composición',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(216,8,16,'Martes','11:30:00','13:00:00','Docente de Literatura y Artes',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(217,8,1,'Miércoles','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(218,8,2,'Miércoles','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(219,8,14,'Miércoles','11:30:00','13:00:00','Docente de Nuevas Tecnologías',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(220,8,5,'Jueves','08:30:00','10:00:00','Docente de Músicas y Culturas',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(221,8,15,'Jueves','10:00:00','11:30:00','Docente de Composición',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(222,8,16,'Jueves','11:30:00','13:00:00','Docente de Literatura y Artes',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(223,8,1,'Viernes','08:30:00','10:00:00','Docente de la especialidad',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(224,8,2,'Viernes','10:00:00','11:30:00','Docente de Práctica Colectiva',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37'),(225,8,17,'Viernes','11:30:00','13:00:00','Docente de Seminario',NULL,'2026-10-26 16:09:37','2026-10-26 16:09:37');
/*!40000 ALTER TABLE `horarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_10_16_000001_add_role_to_users_table',1),(5,'2026_10_16_000002_create_docentes_table',1),(6,'2026_10_16_000003_create_contenidos_table',1),(7,'2026_10_16_000004_create_notas_table',1),(8,'2026_10_16_000005_create_horarios_table',1),(9,'2026_10_16_000006_create_noticias_table',1),(10,'2026_10_16_000007_add_foto_to_contenidos_table',1),(11,'2026_10_19_000007_create_cursos_table',1),(12,'2026_10_19_000008_replace_user_with_curso_in_horarios_table',1),(13,'2026_10_19_000009_add_curso_id_to_users_table',1),(14,'2026_10_19_000010_add_estado_horario_to_cursos_table',1),(15,'2026_10_19_000020_add_multimedia_to_noticias_table',1),(16,'2026_10_20_000030_add_encuadre_to_docentes_table',1),(17,'2026_10_26_000040_add_especialidad_to_users_table',1),(18,'2026_10_26_000050_normalize_to_third_normal_form',1),(19,'2026_10_26_000060_add_carnet_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `asignatura_id` bigint(20) unsigned NOT NULL,
  `gestion` varchar(255) NOT NULL DEFAULT '2026',
  `parcial` tinyint(3) unsigned NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notas_estudiante_asignatura_gestion_parcial_unique` (`user_id`,`asignatura_id`,`gestion`,`parcial`),
  KEY `notas_asignatura_id_foreign` (`asignatura_id`),
  KEY `notas_estudiante_gestion_index` (`user_id`,`gestion`),
  KEY `notas_gestion_index` (`gestion`),
  CONSTRAINT `notas_asignatura_id_foreign` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  CONSTRAINT `notas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas`
--

LOCK TABLES `notas` WRITE;
/*!40000 ALTER TABLE `notas` DISABLE KEYS */;
/*!40000 ALTER TABLE `notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `noticias`
--

DROP TABLE IF EXISTS `noticias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `noticias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'noticia',
  `resumen` text DEFAULT NULL,
  `cuerpo` text DEFAULT NULL,
  `enlace` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL,
  `orden` int(10) unsigned NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `noticias_activo_fecha_index` (`activo`,`fecha`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `noticias`
--

LOCK TABLES `noticias` WRITE;
/*!40000 ALTER TABLE `noticias` DISABLE KEYS */;
INSERT INTO `noticias` VALUES (1,'La música de la EBIM en EXPOTECO 2026','noticia','El poder de la música de la EBIM en el pabellón \"Supay\" de EXPOTECO 2026 y en el pabellón Pariguana, donde está el stand de nuestra institución.','Invitamos a la comunidad orureña a visitar nuestro espacio y acompañar a los estudiantes y docentes durante la feria.','https://www.facebook.com/100063251977207/posts/el-poder-de-la-m%C3%BAsica-de-la-ebim-en-el-pabell%C3%B3n-supay-expoteco-2026-y-en-el-pabe/1380524360732617/',NULL,'2026-10-15',1,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(2,'17 años formando talento, cultura y tradición','noticia','La Escuela Boliviana Intercultural de Música celebra con orgullo 17 años de trayectoria, dedicados a la formación artística y a la preservación de nuestra música.','Un aniversario que reconoce el trabajo de estudiantes, docentes y de toda la comunidad que acompaña a la escuela desde su creación.','https://www.facebook.com/100063251977207/posts/-17-a%C3%B1os-formando-talento-cultura-y-tradici%C3%B3n-la-escuela-boliviana-intercultural/1375630397888680/',NULL,'2026-10-09',2,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(3,'Concierto por el 17.º aniversario','noticia','La EBIM invita a la comunidad orureña a participar del Concierto por el 17.º aniversario, una celebración de la música, la formación y la interculturalidad.','El concierto reúne a las especialidades de la escuela y muestra el trabajo de sus estudiantes y docentes.','https://www.facebook.com/100063251977207/posts/-celebramos-17-a%C3%B1os-de-m%C3%BAsica-formaci%C3%B3n-e-interculturalidadla-escuela-boliviana-/1373917964726590/',NULL,'2026-10-08',3,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(4,'Jornadas académicas EBIM: ingreso libre','noticia','Invitamos a toda la población orureña a compartir una jornada dedicada a la música, la investigación cultural, las nuevas tecnologías y los derechos culturales.','Las jornadas académicas están abiertas al público en general, con ingreso libre.','https://www.facebook.com/100063251977207/posts/-jornadas-acad%C3%A9micas-ebim-ingreso-libreinvitamos-a-toda-la-poblaci%C3%B3n-orure%C3%B1a-a-c/1372261478225572/',NULL,'2026-10-05',4,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(5,'Comunicado: Acto Académico por el 17.º aniversario','comunicado','La Escuela Boliviana Intercultural de Música invita a la comunidad orureña a participar del Acto Académico por su 17.º aniversario.','Se convoca a estudiantes, docentes, personal administrativo y a toda la comunidad a acompañar este acto institucional.','https://www.facebook.com/100063251977207/posts/-celebramos-17-a%C3%B1os-de-m%C3%BAsica-formaci%C3%B3n-e-interculturalidadla-escuela-boliviana-/1373436024774784/',NULL,'2026-10-08',1,1,'2026-10-20 17:11:10','2026-10-20 17:11:10'),(6,'Comunicado: firma de convenio con el Campo Ferial 3 de Julio','comunicado','El Campo Ferial 3 de Julio firmó un convenio con la Escuela Boliviana Intercultural de Música-EBIM, fortaleciendo la presencia de la escuela en EXPOTECO 2026.','La firma del convenio refuerza el trabajo conjunto entre la escuela y el campo ferial de la ciudad de Oruro.','https://www.facebook.com/campoferial3dejuliooficial/posts/convenio-ebim-la-m%C3%BAsica-tambi%C3%A9n-se-vive-en-expoteco-2026el-campo-ferial-3-de-jul/1125382059827359/',NULL,'2026-10-14',2,1,'2026-10-20 17:11:10','2026-10-20 17:11:10');
/*!40000 ALTER TABLE `noticias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'estudiante',
  `codigo` varchar(255) DEFAULT NULL,
  `carnet` varchar(20) DEFAULT NULL,
  `curso_id` bigint(20) unsigned DEFAULT NULL,
  `especialidad_id` bigint(20) unsigned DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_codigo_unique` (`codigo`),
  UNIQUE KEY `users_carnet_unique` (`carnet`),
  KEY `users_curso_id_foreign` (`curso_id`),
  KEY `users_especialidad_id_foreign` (`especialidad_id`),
  KEY `users_role_index` (`role`),
  CONSTRAINT `users_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,'Cristian Quiuchaca Chipana',NULL,NULL,'$2y$12$ZeYuSF4.ws14t4Kfyy1KVubU8rg3UQuNKMjOVgSx3.9GZTxeJL/dq','estudiante','8024','8573205',2,2,NULL,'2026-10-26 18:10:37','2026-10-26 21:02:36'),(7,'Joel Daniel Ururi Choquetilla',NULL,NULL,'$2y$12$E95laKQNnt5lM.ig6ljV6urNWpbFWu461xmIpVhI5pJSHQ4537MIq','estudiante','1299','8800918',2,2,NULL,'2026-10-26 18:10:40','2026-10-26 21:02:38'),(8,'José Raúl Quia Calizaya',NULL,NULL,'$2y$12$DRlpyVK.KE2bHwp1y73y9uRsG8q6EsgXaJSd.ZEYwjLB4ID.lqlze','estudiante','3282','9765199',2,2,NULL,'2026-10-26 18:10:43','2026-10-26 21:02:40'),(9,'Julio Wilfredo Roque Copa',NULL,NULL,'$2y$12$V.9q5jkCPteD89SQGSr9iuKv2KLVlkgBLRKkDsBBDDIt1D6MkTL86','estudiante','9164','9282368',2,2,NULL,'2026-10-26 18:10:46','2026-10-26 21:02:43'),(10,'Nayra Pérez',NULL,NULL,'$2y$12$gwoQVjohSwKw/MpuCzbycOPSMt5LpxfjXs9ZAsquYDKgK8BoFk9QO','estudiante','4010','7469428',NULL,2,NULL,'2026-10-26 18:10:49','2026-10-26 21:02:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'ebim'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-26 23:04:00
