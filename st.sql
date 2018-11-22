-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 10 Feb 2018 la 18:40
-- Versiune server: 5.7.21-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stacktravel`
--

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `emails`
--

CREATE TABLE `emails` (
  `id_email` int(10) UNSIGNED NOT NULL,
  `code` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `emails_lang`
--

CREATE TABLE `emails_lang` (
  `id_email` int(10) UNSIGNED NOT NULL,
  `id_lang` int(10) UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_text` text COLLATE utf8_unicode_ci,
  `body_html` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `lang`
--

CREATE TABLE `lang` (
  `id_lang` int(10) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `iso_code` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `implicit` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `lang`
--

INSERT INTO `lang` (`id_lang`, `status`, `iso_code`, `name`, `implicit`) VALUES
(1, 1, 'en', 'English', 1),
(2, 1, 'ro', 'Română', 0);

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `msgs`
--

CREATE TABLE `msgs` (
  `id_msg` int(10) UNSIGNED NOT NULL,
  `code` int(10) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `msgs_lang`
--

CREATE TABLE `msgs_lang` (
  `id_msg` int(10) UNSIGNED NOT NULL,
  `id_lang` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `routes`
--

CREATE TABLE `routes` (
  `id_route` int(10) UNSIGNED NOT NULL,
  `code` int(10) UNSIGNED NOT NULL,
  `controller` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `methods` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `routes`
--

INSERT INTO `routes` (`id_route`, `code`, `controller`, `action`, `methods`) VALUES
(1, 100, 'BaseController', 'index', 'GET'),
(2, 101, 'BaseController', 'captcha', 'GET'),
(3, 102, 'BaseController', 'dns_test', 'GET'),
(4, 103, 'BaseController', 'verify_host', 'GET'),
(5, 105, 'BaseController', 'img', 'GET/HEAD'),
(9, 120, 'BaseController', 'help_page', 'GET'),
(10, 200, 'AccountController', 'index', 'GET'),
(11, 201, 'AccountController', 'login', 'GET/POST'),
(12, 202, 'AccountController', 'logout', 'GET'),
(13, 203, 'AccountController', 'register', 'GET/POST'),
(14, 204, 'AccountController', 'register_confirm', 'GET'),
(15, 205, 'AccountController', 'recovery', 'GET/POST'),
(16, 206, 'AccountController', 'recovery_confirm', 'GET/POST'),
(17, 121, 'BaseController', 'about_page', 'GET'),
(18, 122, 'BaseController', 'terms_page', 'GET'),
(19, 123, 'BaseController', 'conf_page', 'GET'),
(20, 124, 'BaseController', 'contact_page', 'GET');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `routes_lang`
--

CREATE TABLE `routes_lang` (
  `id_route` int(10) UNSIGNED NOT NULL,
  `id_lang` int(10) UNSIGNED NOT NULL,
  `pattern` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `routes_lang`
--

INSERT INTO `routes_lang` (`id_route`, `id_lang`, `pattern`) VALUES
(1, 1, '/'),
(1, 2, '/'),
(2, 1, '/captcha/'),
(2, 2, '/captcha/'),
(3, 1, '/dns-test/'),
(3, 2, '/dns-test/'),
(4, 1, '/verify-host/{P1}/'),
(4, 2, '/verificare-host/{P1}/'),
(5, 1, '/img/{P1}/{P2}/{P3}/{P4}/{P5}/'),
(5, 2, '/img/{P1}/{P2}/{P3}/{P4}/{P5}/'),
(9, 1, '/help/'),
(9, 2, '/ajutor/'),
(10, 1, '/admin/'),
(10, 2, '/admin/'),
(11, 1, '/login/'),
(11, 2, '/autentificare/'),
(12, 1, '/logout/'),
(12, 2, '/logout/'),
(13, 1, '/register/'),
(13, 2, '/inregistrare/'),
(14, 1, '/register/{P1}/{P2}/'),
(14, 2, '/inregistrare/{P1}/{P2}/'),
(15, 1, '/recovery/'),
(15, 2, '/recuperare/'),
(16, 1, '/recovery/{P1}/{P2}/'),
(16, 2, '/recuperare/{P1}/{P2}/'),
(17, 1, '/about/'),
(17, 2, '/despre/'),
(18, 1, '/terms-conditions/'),
(18, 2, '/termeni-conditii/'),
(19, 1, '/confidentiality/'),
(19, 2, '/confidentialitate/'),
(20, 1, '/contact/'),
(20, 2, '/contact/');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `texts`
--

CREATE TABLE `texts` (
  `id_text` int(10) UNSIGNED NOT NULL,
  `code` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `texts`
--

INSERT INTO `texts` (`id_text`, `code`) VALUES
(2, 1001),
(1, 1002),
(3, 1003),
(8, 1004),
(15, 1005),
(16, 1006),
(17, 1007),
(18, 1008),
(19, 1009),
(4, 1021),
(5, 1022),
(6, 2011),
(7, 2012),
(9, 2014),
(10, 2015),
(11, 2016),
(12, 2017),
(13, 2018),
(14, 2019),
(20, 2031),
(21, 2032),
(22, 2035),
(23, 2036),
(24, 2037),
(25, 2038),
(26, 2039),
(27, 20310),
(28, 20311),
(29, 20312);

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `texts_lang`
--

CREATE TABLE `texts_lang` (
  `id_text` int(10) UNSIGNED NOT NULL,
  `id_lang` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `texts_lang`
--

INSERT INTO `texts_lang` (`id_text`, `id_lang`, `text`) VALUES
(1, 1, 'Page not found'),
(1, 2, 'Pagina nu a fost găsită'),
(2, 1, '404'),
(2, 2, '404'),
(3, 1, 'Home'),
(3, 2, 'Acasă'),
(4, 1, 'DNS'),
(4, 2, 'DNS'),
(5, 1, '{{user_domain}} is connected to {{service_name}} server'),
(5, 2, 'Domeniul {{user_domain}} este conectat la serverul {{service_name}}'),
(6, 1, 'Login'),
(6, 2, 'Autentificare'),
(7, 1, 'Log in to your account'),
(7, 2, 'Pagina de autentificare'),
(8, 1, 'Home page'),
(8, 2, 'Pagina principală'),
(9, 1, 'or'),
(9, 2, 'sau'),
(10, 1, 'Email address'),
(10, 2, 'Adresa email'),
(11, 1, 'Password'),
(11, 2, 'Parola'),
(12, 1, 'Log in'),
(12, 2, 'Autentificare'),
(13, 1, 'Create new account'),
(13, 2, 'Înregistrați un cont nou'),
(14, 1, 'Recover lost password'),
(14, 2, 'Recuperați parola'),
(15, 1, 'About'),
(15, 2, 'Despre'),
(16, 1, 'Help'),
(16, 2, 'Ajutor'),
(17, 1, 'Terms and conditions'),
(17, 2, 'Termeni și condiții'),
(18, 1, 'Confidentiality'),
(18, 2, 'Confidențialitate'),
(19, 1, 'Contact'),
(19, 2, 'Contact'),
(20, 1, 'Register'),
(20, 2, 'Înregistrare cont'),
(21, 1, 'Register new account'),
(21, 2, 'Înregistrare cont nou'),
(22, 1, 'Email address'),
(22, 2, 'Adresa email'),
(23, 1, 'Password'),
(23, 2, 'Parola'),
(24, 1, 'Confirm password'),
(24, 2, 'Confirmare parolă'),
(25, 1, 'Regenerate code'),
(25, 2, 'Regenerare cod'),
(26, 1, 'Captcha code'),
(26, 2, 'Cod captcha'),
(27, 1, 'Register account'),
(27, 2, 'Înregistrare cont'),
(28, 1, 'Already have an account ?'),
(28, 2, 'Aveți deja cont ?'),
(29, 1, 'Log in'),
(29, 2, 'Autentificați-vă');

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - inactive; 1 - active; -1 - disabled; -2 - banned',
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_lang` int(10) UNSIGNED NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `token_recovery` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_recovery` datetime DEFAULT NULL,
  `newemail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_newemail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_newemail` datetime DEFAULT NULL,
  `token_disable` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_disable` datetime DEFAULT NULL,
  `ban_start` datetime DEFAULT NULL,
  `ban_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `users`
--

INSERT INTO `users` (`id_user`, `email`, `password`, `status`, `token`, `id_lang`, `timezone`, `date_created`, `date_modified`, `token_recovery`, `date_recovery`, `newemail`, `token_newemail`, `date_newemail`, `token_disable`, `date_disable`, `ban_start`, `ban_end`) VALUES
(1, 'mihai.tesu@gmail.com', '$1$ll7cdYOK$WR.oxQBXhWDPZgolTpVkT0', 1, NULL, 1, 'Europe/Bucharest', '2015-09-09 18:39:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structura de tabel pentru tabelul `users_sub_domains`
--

CREATE TABLE `users_sub_domains` (
  `id_sub_domain` int(10) UNSIGNED NOT NULL,
  `id_user` int(10) UNSIGNED NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Salvarea datelor din tabel `users_sub_domains`
--

INSERT INTO `users_sub_domains` (`id_sub_domain`, `id_user`, `domain`, `subdomain`, `date_created`, `date_modified`) VALUES
(1, 1, NULL, 'about', NULL, NULL),
(2, 1, NULL, 'access', NULL, NULL),
(3, 1, NULL, 'account', NULL, NULL),
(4, 1, NULL, 'accounts', NULL, NULL),
(5, 1, NULL, 'add', NULL, NULL),
(6, 1, NULL, 'address', NULL, NULL),
(7, 1, NULL, 'adm', NULL, NULL),
(8, 1, NULL, 'admin', NULL, NULL),
(9, 1, NULL, 'administration', NULL, NULL),
(10, 1, NULL, 'administrator', NULL, NULL),
(11, 1, NULL, 'adult', NULL, NULL),
(12, 1, NULL, 'advertising', NULL, NULL),
(13, 1, NULL, 'affiliate', NULL, NULL),
(14, 1, NULL, 'affiliates', NULL, NULL),
(15, 1, NULL, 'ajax', NULL, NULL),
(16, 1, NULL, 'analytics', NULL, NULL),
(17, 1, NULL, 'android', NULL, NULL),
(18, 1, NULL, 'anon', NULL, NULL),
(19, 1, NULL, 'anonymous', NULL, NULL),
(20, 1, NULL, 'api', NULL, NULL),
(21, 1, NULL, 'app', NULL, NULL),
(22, 1, NULL, 'apps', NULL, NULL),
(23, 1, NULL, 'archive', NULL, NULL),
(24, 1, NULL, 'atom', NULL, NULL),
(25, 1, NULL, 'auth', NULL, NULL),
(26, 1, NULL, 'authentication', NULL, NULL),
(27, 1, NULL, 'avatar', NULL, NULL),
(28, 1, NULL, 'backup', NULL, NULL),
(29, 1, NULL, 'banner', NULL, NULL),
(30, 1, NULL, 'banners', NULL, NULL),
(31, 1, NULL, 'billing', NULL, NULL),
(32, 1, NULL, 'bin', NULL, NULL),
(33, 1, NULL, 'blog', NULL, NULL),
(34, 1, NULL, 'blogs', NULL, NULL),
(35, 1, NULL, 'board', NULL, NULL),
(36, 1, NULL, 'bot', NULL, NULL),
(37, 1, NULL, 'bots', NULL, NULL),
(38, 1, NULL, 'business', NULL, NULL),
(39, 1, NULL, 'cache', NULL, NULL),
(40, 1, NULL, 'cadastro', NULL, NULL),
(41, 1, NULL, 'calendar', NULL, NULL),
(42, 1, NULL, 'campaign', NULL, NULL),
(43, 1, NULL, 'careers', NULL, NULL),
(44, 1, NULL, 'cgi', NULL, NULL),
(45, 1, NULL, 'chat', NULL, NULL),
(46, 1, NULL, 'client', NULL, NULL),
(47, 1, NULL, 'cliente', NULL, NULL),
(48, 1, NULL, 'code', NULL, NULL),
(49, 1, NULL, 'comercial', NULL, NULL),
(50, 1, NULL, 'compare', NULL, NULL),
(51, 1, NULL, 'compras', NULL, NULL),
(52, 1, NULL, 'config', NULL, NULL),
(53, 1, NULL, 'connect', NULL, NULL),
(54, 1, NULL, 'contact', NULL, NULL),
(55, 1, NULL, 'contest', NULL, NULL),
(56, 1, NULL, 'create', NULL, NULL),
(57, 1, NULL, 'css', NULL, NULL),
(58, 1, NULL, 'dashboard', NULL, NULL),
(59, 1, NULL, 'data', NULL, NULL),
(60, 1, NULL, 'db', NULL, NULL),
(61, 1, NULL, 'delete', NULL, NULL),
(62, 1, NULL, 'demo', NULL, NULL),
(63, 1, NULL, 'design', NULL, NULL),
(64, 1, NULL, 'designer', NULL, NULL),
(65, 1, NULL, 'dev', NULL, NULL),
(66, 1, NULL, 'devel', NULL, NULL),
(67, 1, NULL, 'dir', NULL, NULL),
(68, 1, NULL, 'directory', NULL, NULL),
(69, 1, NULL, 'doc', NULL, NULL),
(70, 1, NULL, 'docs', NULL, NULL),
(71, 1, NULL, 'domain', NULL, NULL),
(72, 1, NULL, 'download', NULL, NULL),
(73, 1, NULL, 'downloads', NULL, NULL),
(74, 1, NULL, 'ecommerce', NULL, NULL),
(75, 1, NULL, 'edit', NULL, NULL),
(76, 1, NULL, 'editor', NULL, NULL),
(77, 1, NULL, 'email', NULL, NULL),
(78, 1, NULL, 'faq', NULL, NULL),
(79, 1, NULL, 'favorite', NULL, NULL),
(80, 1, NULL, 'feed', NULL, NULL),
(81, 1, NULL, 'feedback', NULL, NULL),
(82, 1, NULL, 'file', NULL, NULL),
(83, 1, NULL, 'files', NULL, NULL),
(84, 1, NULL, 'flog', NULL, NULL),
(85, 1, NULL, 'follow', NULL, NULL),
(86, 1, NULL, 'forum', NULL, NULL),
(87, 1, NULL, 'forums', NULL, NULL),
(88, 1, NULL, 'free', NULL, NULL),
(89, 1, NULL, 'ftp', NULL, NULL),
(90, 1, NULL, 'gadget', NULL, NULL),
(91, 1, NULL, 'gadgets', NULL, NULL),
(92, 1, NULL, 'games', NULL, NULL),
(93, 1, NULL, 'group', NULL, NULL),
(94, 1, NULL, 'groups', NULL, NULL),
(95, 1, NULL, 'guest', NULL, NULL),
(96, 1, NULL, 'help', NULL, NULL),
(97, 1, NULL, 'home', NULL, NULL),
(98, 1, NULL, 'homepage', NULL, NULL),
(99, 1, NULL, 'host', NULL, NULL),
(100, 1, NULL, 'hosting', NULL, NULL),
(101, 1, NULL, 'hostname', NULL, NULL),
(102, 1, NULL, 'hpg', NULL, NULL),
(103, 1, NULL, 'html', NULL, NULL),
(104, 1, NULL, 'http', NULL, NULL),
(105, 1, NULL, 'httpd', NULL, NULL),
(106, 1, NULL, 'https', NULL, NULL),
(107, 1, NULL, 'image', NULL, NULL),
(108, 1, NULL, 'images', NULL, NULL),
(109, 1, NULL, 'imap', NULL, NULL),
(110, 1, NULL, 'img', NULL, NULL),
(111, 1, NULL, 'index', NULL, NULL),
(112, 1, NULL, 'indice', NULL, NULL),
(113, 1, NULL, 'info', NULL, NULL),
(114, 1, NULL, 'information', NULL, NULL),
(115, 1, NULL, 'intranet', NULL, NULL),
(116, 1, NULL, 'invite', NULL, NULL),
(117, 1, NULL, 'ipad', NULL, NULL),
(118, 1, NULL, 'iphone', NULL, NULL),
(119, 1, NULL, 'irc', NULL, NULL),
(120, 1, NULL, 'java', NULL, NULL),
(121, 1, NULL, 'javascript', NULL, NULL),
(122, 1, NULL, 'job', NULL, NULL),
(123, 1, NULL, 'jobs', NULL, NULL),
(124, 1, NULL, 'js', NULL, NULL),
(125, 1, NULL, 'knowledgebase', NULL, NULL),
(126, 1, NULL, 'list', NULL, NULL),
(127, 1, NULL, 'lists', NULL, NULL),
(128, 1, NULL, 'log', NULL, NULL),
(129, 1, NULL, 'login', NULL, NULL),
(130, 1, NULL, 'logout', NULL, NULL),
(131, 1, NULL, 'logs', NULL, NULL),
(132, 1, NULL, 'mail', NULL, NULL),
(133, 1, NULL, 'mail1', NULL, NULL),
(134, 1, NULL, 'mail2', NULL, NULL),
(135, 1, NULL, 'mail3', NULL, NULL),
(136, 1, NULL, 'mail4', NULL, NULL),
(137, 1, NULL, 'mail5', NULL, NULL),
(138, 1, NULL, 'mailer', NULL, NULL),
(139, 1, NULL, 'mailing', NULL, NULL),
(140, 1, NULL, 'manager', NULL, NULL),
(141, 1, NULL, 'marketing', NULL, NULL),
(142, 1, NULL, 'master', NULL, NULL),
(143, 1, NULL, 'me', NULL, NULL),
(144, 1, NULL, 'media', NULL, NULL),
(145, 1, NULL, 'message', NULL, NULL),
(146, 1, NULL, 'messenger', NULL, NULL),
(147, 1, NULL, 'microblog', NULL, NULL),
(148, 1, NULL, 'microblogs', NULL, NULL),
(149, 1, NULL, 'mine', NULL, NULL),
(150, 1, NULL, 'misu', NULL, NULL),
(151, 1, NULL, 'mob', NULL, NULL),
(152, 1, NULL, 'mobile', NULL, NULL),
(153, 1, NULL, 'movie', NULL, NULL),
(154, 1, NULL, 'movies', NULL, NULL),
(155, 1, NULL, 'mp3', NULL, NULL),
(156, 1, NULL, 'msg', NULL, NULL),
(157, 1, NULL, 'msn', NULL, NULL),
(158, 1, NULL, 'music', NULL, NULL),
(159, 1, NULL, 'musicas', NULL, NULL),
(160, 1, NULL, 'mx', NULL, NULL),
(161, 1, NULL, 'my', NULL, NULL),
(162, 1, NULL, 'mysql', NULL, NULL),
(163, 1, NULL, 'name', NULL, NULL),
(164, 1, NULL, 'named', NULL, NULL),
(165, 1, NULL, 'net', NULL, NULL),
(166, 1, NULL, 'network', NULL, NULL),
(167, 1, NULL, 'new', NULL, NULL),
(168, 1, NULL, 'news', NULL, NULL),
(169, 1, NULL, 'newsletter', NULL, NULL),
(170, 1, NULL, 'nick', NULL, NULL),
(171, 1, NULL, 'nickname', NULL, NULL),
(172, 1, NULL, 'notes', NULL, NULL),
(173, 1, NULL, 'noticias', NULL, NULL),
(174, 1, NULL, 'ns', NULL, NULL),
(175, 1, NULL, 'ns1', NULL, NULL),
(176, 1, NULL, 'ns2', NULL, NULL),
(177, 1, NULL, 'ns3', NULL, NULL),
(178, 1, NULL, 'ns4', NULL, NULL),
(179, 1, NULL, 'old', NULL, NULL),
(180, 1, NULL, 'online', NULL, NULL),
(181, 1, NULL, 'operator', NULL, NULL),
(182, 1, NULL, 'order', NULL, NULL),
(183, 1, NULL, 'orders', NULL, NULL),
(184, 1, NULL, 'page', NULL, NULL),
(185, 1, NULL, 'pager', NULL, NULL),
(186, 1, NULL, 'pages', NULL, NULL),
(187, 1, NULL, 'panel', NULL, NULL),
(188, 1, NULL, 'password', NULL, NULL),
(189, 1, NULL, 'perl', NULL, NULL),
(190, 1, NULL, 'photo', NULL, NULL),
(191, 1, NULL, 'photoalbum', NULL, NULL),
(192, 1, NULL, 'photos', NULL, NULL),
(193, 1, NULL, 'php', NULL, NULL),
(194, 1, NULL, 'pic', NULL, NULL),
(195, 1, NULL, 'pics', NULL, NULL),
(196, 1, NULL, 'plugin', NULL, NULL),
(197, 1, NULL, 'plugins', NULL, NULL),
(198, 1, NULL, 'pop', NULL, NULL),
(199, 1, NULL, 'pop3', NULL, NULL),
(200, 1, NULL, 'post', NULL, NULL),
(201, 1, NULL, 'postfix', NULL, NULL),
(202, 1, NULL, 'postmaster', NULL, NULL),
(203, 1, NULL, 'posts', NULL, NULL),
(204, 1, NULL, 'profile', NULL, NULL),
(205, 1, NULL, 'project', NULL, NULL),
(206, 1, NULL, 'projects', NULL, NULL),
(207, 1, NULL, 'promo', NULL, NULL),
(208, 1, NULL, 'pub', NULL, NULL),
(209, 1, NULL, 'public', NULL, NULL),
(210, 1, NULL, 'python', NULL, NULL),
(211, 1, NULL, 'random', NULL, NULL),
(212, 1, NULL, 'register', NULL, NULL),
(213, 1, NULL, 'registration', NULL, NULL),
(214, 1, NULL, 'root', NULL, NULL),
(215, 1, NULL, 'rss', NULL, NULL),
(216, 1, NULL, 'ruby', NULL, NULL),
(217, 1, NULL, 'sale', NULL, NULL),
(218, 1, NULL, 'sales', NULL, NULL),
(219, 1, NULL, 'sample', NULL, NULL),
(220, 1, NULL, 'samples', NULL, NULL),
(221, 1, NULL, 'script', NULL, NULL),
(222, 1, NULL, 'scripts', NULL, NULL),
(223, 1, NULL, 'search', NULL, NULL),
(224, 1, NULL, 'secure', NULL, NULL),
(225, 1, NULL, 'security', NULL, NULL),
(226, 1, NULL, 'send', NULL, NULL),
(227, 1, NULL, 'service', NULL, NULL),
(228, 1, NULL, 'setting', NULL, NULL),
(229, 1, NULL, 'settings', NULL, NULL),
(230, 1, NULL, 'setup', NULL, NULL),
(231, 1, NULL, 'shop', NULL, NULL),
(232, 1, NULL, 'signin', NULL, NULL),
(233, 1, NULL, 'signup', NULL, NULL),
(234, 1, NULL, 'site', NULL, NULL),
(235, 1, NULL, 'sitemap', NULL, NULL),
(236, 1, NULL, 'sites', NULL, NULL),
(237, 1, NULL, 'smtp', NULL, NULL),
(238, 1, NULL, 'soporte', NULL, NULL),
(239, 1, NULL, 'sql', NULL, NULL),
(240, 1, NULL, 'ssh', NULL, NULL),
(241, 1, NULL, 'stage', NULL, NULL),
(242, 1, NULL, 'staging', NULL, NULL),
(243, 1, NULL, 'start', NULL, NULL),
(244, 1, NULL, 'stat', NULL, NULL),
(245, 1, NULL, 'static', NULL, NULL),
(246, 1, NULL, 'stats', NULL, NULL),
(247, 1, NULL, 'status', NULL, NULL),
(248, 1, NULL, 'store', NULL, NULL),
(249, 1, NULL, 'stores', NULL, NULL),
(250, 1, NULL, 'subdomain', NULL, NULL),
(251, 1, NULL, 'subscribe', NULL, NULL),
(252, 1, NULL, 'suporte', NULL, NULL),
(253, 1, NULL, 'support', NULL, NULL),
(254, 1, NULL, 'system', NULL, NULL),
(255, 1, NULL, 'tablet', NULL, NULL),
(256, 1, NULL, 'tablets', NULL, NULL),
(257, 1, NULL, 'talk', NULL, NULL),
(258, 1, NULL, 'task', NULL, NULL),
(259, 1, NULL, 'tasks', NULL, NULL),
(260, 1, NULL, 'tech', NULL, NULL),
(261, 1, NULL, 'telnet', NULL, NULL),
(262, 1, NULL, 'test', NULL, NULL),
(263, 1, NULL, 'test1', NULL, NULL),
(264, 1, NULL, 'test2', NULL, NULL),
(265, 1, NULL, 'test3', NULL, NULL),
(266, 1, NULL, 'teste', NULL, NULL),
(267, 1, NULL, 'tests', NULL, NULL),
(268, 1, NULL, 'theme', NULL, NULL),
(269, 1, NULL, 'themes', NULL, NULL),
(270, 1, NULL, 'tmp', NULL, NULL),
(271, 1, NULL, 'todo', NULL, NULL),
(272, 1, NULL, 'tools', NULL, NULL),
(273, 1, NULL, 'tv', NULL, NULL),
(274, 1, NULL, 'update', NULL, NULL),
(275, 1, NULL, 'upload', NULL, NULL),
(276, 1, NULL, 'url', NULL, NULL),
(277, 1, NULL, 'usage', NULL, NULL),
(278, 1, NULL, 'user', NULL, NULL),
(279, 1, NULL, 'username', NULL, NULL),
(280, 1, NULL, 'usuario', NULL, NULL),
(281, 1, NULL, 'vendas', NULL, NULL),
(282, 1, NULL, 'video', NULL, NULL),
(283, 1, NULL, 'videos', NULL, NULL),
(284, 1, NULL, 'visitor', NULL, NULL),
(285, 1, NULL, 'web', NULL, NULL),
(286, 1, NULL, 'webmail', NULL, NULL),
(287, 1, NULL, 'webmaster', NULL, NULL),
(288, 1, NULL, 'website', NULL, NULL),
(289, 1, NULL, 'websites', NULL, NULL),
(290, 1, NULL, 'win', NULL, NULL),
(291, 1, NULL, 'workshop', NULL, NULL),
(292, 1, NULL, 'ww', NULL, NULL),
(293, 1, NULL, 'wws', NULL, NULL),
(294, 1, NULL, 'www', NULL, NULL),
(295, 1, NULL, 'www1', NULL, NULL),
(296, 1, NULL, 'www2', NULL, NULL),
(297, 1, NULL, 'www3', NULL, NULL),
(298, 1, NULL, 'www4', NULL, NULL),
(299, 1, NULL, 'www5', NULL, NULL),
(300, 1, NULL, 'www6', NULL, NULL),
(301, 1, NULL, 'www7', NULL, NULL),
(302, 1, NULL, 'wwws', NULL, NULL),
(303, 1, NULL, 'wwww', NULL, NULL),
(304, 1, NULL, 'xpg', NULL, NULL),
(305, 1, NULL, 'xxx', NULL, NULL),
(306, 1, NULL, 'you', NULL, NULL),
(307, 1, NULL, 'yourdomain', NULL, NULL),
(308, 1, NULL, 'yourname', NULL, NULL),
(309, 1, NULL, 'yoursite', NULL, NULL),
(310, 1, NULL, 'yourusername', NULL, NULL),
(311, 1, 'example.dev', 'example', '2018-01-06 00:00:00', '2018-01-06 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id_email`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `emails_lang`
--
ALTER TABLE `emails_lang`
  ADD PRIMARY KEY (`id_email`,`id_lang`),
  ADD KEY `id_email` (`id_email`),
  ADD KEY `id_lang` (`id_lang`);

--
-- Indexes for table `lang`
--
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id_lang`),
  ADD UNIQUE KEY `code` (`iso_code`),
  ADD KEY `name` (`name`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `msgs`
--
ALTER TABLE `msgs`
  ADD PRIMARY KEY (`id_msg`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `msgs_lang`
--
ALTER TABLE `msgs_lang`
  ADD PRIMARY KEY (`id_msg`,`id_lang`) USING BTREE,
  ADD KEY `id_msg` (`id_msg`),
  ADD KEY `id_lang` (`id_lang`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id_route`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `routes_lang`
--
ALTER TABLE `routes_lang`
  ADD PRIMARY KEY (`id_route`,`id_lang`),
  ADD KEY `id_route` (`id_route`),
  ADD KEY `id_lang` (`id_lang`);

--
-- Indexes for table `texts`
--
ALTER TABLE `texts`
  ADD PRIMARY KEY (`id_text`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `texts_lang`
--
ALTER TABLE `texts_lang`
  ADD PRIMARY KEY (`id_text`,`id_lang`),
  ADD KEY `id_text` (`id_text`),
  ADD KEY `id_lang` (`id_lang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_lang` (`id_lang`);

--
-- Indexes for table `users_sub_domains`
--
ALTER TABLE `users_sub_domains`
  ADD PRIMARY KEY (`id_sub_domain`),
  ADD UNIQUE KEY `subdomain` (`subdomain`),
  ADD UNIQUE KEY `domain` (`domain`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id_email` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lang`
--
ALTER TABLE `lang`
  MODIFY `id_lang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `msgs`
--
ALTER TABLE `msgs`
  MODIFY `id_msg` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id_route` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `texts`
--
ALTER TABLE `texts`
  MODIFY `id_text` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users_sub_domains`
--
ALTER TABLE `users_sub_domains`
  MODIFY `id_sub_domain` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;
--
-- Restrictii pentru tabele sterse
--

--
-- Restrictii pentru tabele `emails_lang`
--
ALTER TABLE `emails_lang`
  ADD CONSTRAINT `emails_lang_ibfk_1` FOREIGN KEY (`id_email`) REFERENCES `emails` (`id_email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `emails_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrictii pentru tabele `msgs_lang`
--
ALTER TABLE `msgs_lang`
  ADD CONSTRAINT `msgs_lang_ibfk_1` FOREIGN KEY (`id_msg`) REFERENCES `msgs` (`id_msg`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `msgs_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrictii pentru tabele `routes_lang`
--
ALTER TABLE `routes_lang`
  ADD CONSTRAINT `routes_lang_ibfk_1` FOREIGN KEY (`id_route`) REFERENCES `routes` (`id_route`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `routes_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrictii pentru tabele `texts_lang`
--
ALTER TABLE `texts_lang`
  ADD CONSTRAINT `texts_lang_ibfk_1` FOREIGN KEY (`id_text`) REFERENCES `texts` (`id_text`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `texts_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrictii pentru tabele `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id_lang`) ON UPDATE CASCADE;

--
-- Restrictii pentru tabele `users_sub_domains`
--
ALTER TABLE `users_sub_domains`
  ADD CONSTRAINT `users_sub_domains_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
