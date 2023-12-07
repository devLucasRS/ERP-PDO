-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 06/12/2023 às 22:01
-- Versão do servidor: 5.7.44
-- Versão do PHP: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `nextsol1_erp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `backlog_projects`
--

CREATE TABLE `backlog_projects` (
  `backlog_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `project_desc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `project_desc`, `client_name`, `status`) VALUES
(1, 'Projeto de Teste', 'Alo alo', 'Teste Cliente', 'Em progresso');

-- --------------------------------------------------------

--
-- Estrutura para tabela `project_members`
--

CREATE TABLE `project_members` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `project_members`
--

INSERT INTO `project_members` (`project_id`, `user_id`) VALUES
(1, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas_tickets`
--

CREATE TABLE `respostas_tickets` (
  `id_resposta` int(11) NOT NULL,
  `id_ticket` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `resposta` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_resposta` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `respostas_tickets`
--

INSERT INTO `respostas_tickets` (`id_resposta`, `id_ticket`, `user_id`, `resposta`, `data_resposta`) VALUES
(13, 1, 7, 'teste 4', '2023-12-05 13:21:54'),
(12, 1, 1, 'teste3', '2023-12-05 13:21:47'),
(11, 1, 7, 'teste2', '2023-12-05 13:21:35'),
(10, 1, 1, 'Teste 1', '2023-12-05 13:21:11'),
(14, 1, 1, '', '2023-12-05 13:42:40'),
(15, 1, 1, '', '2023-12-05 13:47:05'),
(16, 3, 1, 'Alo som teste', '2023-12-05 13:47:27'),
(17, 3, 1, '', '2023-12-05 13:48:27'),
(18, 2, 1, 'Cancelado! ', '2023-12-05 13:53:11'),
(19, 2, 1, 'Re-aberto.', '2023-12-05 13:53:36'),
(20, 2, 1, 'Chamado finalizado', '2023-12-05 13:53:54'),
(21, 2, 1, '', '2023-12-05 13:54:17'),
(22, 3, 1, '', '2023-12-05 13:55:41'),
(23, 5, 1, '', '2023-12-05 14:53:38'),
(24, 4, 1, '', '2023-12-05 14:53:46'),
(25, 6, 1, '', '2023-12-05 14:53:55'),
(26, 4, 1, 'Teste chamado', '2023-12-05 19:36:41');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name_ticket` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assunto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_anexo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sla` datetime NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL,
  `data_finalizado` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `user_id`, `name_ticket`, `assunto`, `email`, `departamento`, `ticket_anexo`, `mensagem`, `sla`, `status`, `data_criacao`, `data_finalizado`) VALUES
(2, 7, 'INC4818', 'Teste Assunto', 'lucas1@lucas2.com', 'Suporte', '656e1739d7f04_1698435486591.jpeg', 'Teste ', '0000-00-00 00:00:00', 'Finalizado', '2023-12-04 15:15:21', NULL),
(5, 1, 'INC9954', 'Computador Travando', 'lucas@lucas.com', 'Suporte', '656f5f5262f45_', 'Meu PC travou e desligou', '0000-00-00 00:00:00', 'Em Atendimento', '2023-12-05 14:35:14', NULL),
(4, 1, 'INC5955', 'Erro de login e senha', 'lucas@lucas.com', 'Suporte', '656f5835f1fab_1698435486591.jpeg', 'Meu login esta dando erro 500! ', '0000-00-00 00:00:00', 'Em Pausa', '2023-12-05 14:04:53', NULL),
(6, 1, 'INC0042', 'Preciso de um contra-cheque', 'lucas@lucas.com', 'Financeiro', '656f61137ba11_', 'Preciso de um contra-cheque', '0000-00-00 00:00:00', 'Cancelado', '2023-12-05 14:42:43', NULL),
(7, 1, 'INC2698', 'Preciso de ferias', 'lucas@lucas.com', 'Administrativo', '656f611ca370c_', 'Preciso de ferias', '0000-00-00 00:00:00', 'Aberto', '2023-12-05 14:42:52', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default-avatar.png',
  `status` int(11) DEFAULT '0',
  `user_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`user_id`, `username`, `name`, `role`, `email`, `password`, `avatar`, `status`, `user_status`, `created_at`) VALUES
(1, 'lucas', 'Lucas Santos', 'Dev', 'lucas@lucas.com', '$2y$10$FlLB43ScglhKuRGwH2gkmuRL0cITh9uY7fgXJLKjmpaoiqQ3aTTJm', '../uploads/1698435486591.jpeg', 2, 'Online', '2023-11-28 17:31:23'),
(7, 'funalinho', 'Fulano da Silva', '', 'lucas1@lucas2.com', '$2y$10$0IcUT71LhaQRjqZN/n9X/O0F6C.7WqCXJxiDMy/EFSOZhDOdw.bY6', '../uploads/default-avatar.png', 2, 'Offline', '2023-12-04 21:14:33'),
(8, 'ana', 'Ana Carolina', '', 'ana@ana.com', '$2y$10$OBsZgV.DlFh/Jt097Vbdf.0gfilGqNCsM7ftdmrTAVd.d/Q.kbHWq', '../uploads/default-avatar.png', 2, 'Offline', '2023-12-05 12:18:24'),
(9, 'tiago', 'Tiago Zapata', '', 'tiago@tiago.com', '$2y$10$1MOtehW44Ynpr/6eYXZ7ZuqQquq8NQ0ckPbBQRCSswv5yNTBfQugC', '../uploads/default-avatar.png', 2, 'Offline', '2023-12-05 12:43:00');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `backlog_projects`
--
ALTER TABLE `backlog_projects`
  ADD PRIMARY KEY (`backlog_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Índices de tabela `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`);

--
-- Índices de tabela `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`project_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `respostas_tickets`
--
ALTER TABLE `respostas_tickets`
  ADD PRIMARY KEY (`id_resposta`),
  ADD KEY `id_ticket` (`id_ticket`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `user_id` (`user_id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `backlog_projects`
--
ALTER TABLE `backlog_projects`
  MODIFY `backlog_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `respostas_tickets`
--
ALTER TABLE `respostas_tickets`
  MODIFY `id_resposta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
