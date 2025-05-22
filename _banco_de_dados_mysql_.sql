-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2025 às 02:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `barbearia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adms`
--

CREATE TABLE `adms` (
  `adm_id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `adms`
--

INSERT INTO `adms` (`adm_id`, `user`, `senha`) VALUES
(1, 'admin', '$2y$10$ItkpI58CwIOnN/Y.WxRus.1Lr.CqLxF5gjY9fCIPhbLU8NyPdq/U.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `agendamento_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `servico_id` int(11) DEFAULT NULL,
  `funcionario_id` int(11) DEFAULT NULL,
  `data_agendamento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `agendamentos`
--

INSERT INTO `agendamentos` (`agendamento_id`, `cliente_id`, `servico_id`, `funcionario_id`, `data_agendamento`) VALUES
(3, 6, 2, 8, '2025-05-20 11:00:00'),
(5, 5, 2, 10, '2025-05-24 06:00:00'),
(6, 5, 1, 8, '2025-05-25 10:30:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `cliente_id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`cliente_id`, `nome`, `telefone`, `email`, `senha`, `foto`) VALUES
(5, 'Jonathan', '77777-7776', 'jonathan@teste.com', '$2y$10$d4nCUtKzeJp6wb.D6cfcFeHAx4VTZd2ZuZjhvnoNZNfu74FqD2Ck2', '68059e1a9bbf6_download (3).jpg'),
(6, 'Jhow', '99999-9788', 'jhow@teste.com', '$2y$10$wpep7UM1EA3/xI6seDHRYOHshSE7097shJNp3/KlDkg946NNEBZkW', '68059f02bce31_Foto.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `expediente_funcionario`
--

CREATE TABLE `expediente_funcionario` (
  `funcionario_id` int(11) NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `dias_folga` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `expediente_funcionario`
--

INSERT INTO `expediente_funcionario` (`funcionario_id`, `hora_inicio`, `hora_fim`, `dias_folga`) VALUES
(8, '10:00:00', '17:00:00', 'quinta'),
(10, '05:00:00', '13:00:00', 'sexta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `funcionario_id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`funcionario_id`, `nome`, `email`, `senha`, `foto`) VALUES
(8, 'Galadieu', 'galadieubarbeiro@empresa.com', '$2y$10$jjN2cN2DpHrTBj0EfMpDpOrU9/ijJNwp9w1LBkAOB6OG2P5xvkTvG', '6829f9ba2db20.jpg'),
(10, 'Fulana', 'fulana@email.com', '$2y$10$Sy42.U158.1yNGEK1azUmepdYh.No/8dw.1pRKZTXwPzW0bbj5exe', '682e70693917c.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario_servico`
--

CREATE TABLE `funcionario_servico` (
  `funcionario_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `funcionario_servico`
--

INSERT INTO `funcionario_servico` (`funcionario_id`, `servico_id`) VALUES
(8, 1),
(8, 2),
(8, 3),
(10, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios_indisponiveis`
--

CREATE TABLE `horarios_indisponiveis` (
  `id` int(11) NOT NULL,
  `funcionario_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `horarios_indisponiveis`
--

INSERT INTO `horarios_indisponiveis` (`id`, `funcionario_id`, `data`, `hora`) VALUES
(7, 8, '2025-05-20', '12:00:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `servicos`
--

CREATE TABLE `servicos` (
  `servico_id` int(11) NOT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `servicos`
--

INSERT INTO `servicos` (`servico_id`, `descricao`, `duracao`, `preco`) VALUES
(1, 'Corte de Cabelo', 30, 50.00),
(2, 'Barba', 30, 30.00),
(3, 'Corte de Cabelo e Barba', 60, 70.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adms`
--
ALTER TABLE `adms`
  ADD PRIMARY KEY (`adm_id`);

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`agendamento_id`),
  ADD KEY `cliente_id` (`cliente_id`),
  ADD KEY `servico_id` (`servico_id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cliente_id`);

--
-- Índices de tabela `expediente_funcionario`
--
ALTER TABLE `expediente_funcionario`
  ADD PRIMARY KEY (`funcionario_id`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`funcionario_id`);

--
-- Índices de tabela `funcionario_servico`
--
ALTER TABLE `funcionario_servico`
  ADD PRIMARY KEY (`funcionario_id`,`servico_id`),
  ADD KEY `servico_id` (`servico_id`);

--
-- Índices de tabela `horarios_indisponiveis`
--
ALTER TABLE `horarios_indisponiveis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `funcionario_id` (`funcionario_id`);

--
-- Índices de tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`servico_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adms`
--
ALTER TABLE `adms`
  MODIFY `adm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `agendamento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `cliente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `funcionario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `horarios_indisponiveis`
--
ALTER TABLE `horarios_indisponiveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `servico_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`servico_id`),
  ADD CONSTRAINT `agendamentos_ibfk_3` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`funcionario_id`);

--
-- Restrições para tabelas `expediente_funcionario`
--
ALTER TABLE `expediente_funcionario`
  ADD CONSTRAINT `expediente_funcionario_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`funcionario_id`);

--
-- Restrições para tabelas `funcionario_servico`
--
ALTER TABLE `funcionario_servico`
  ADD CONSTRAINT `funcionario_servico_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`funcionario_id`),
  ADD CONSTRAINT `funcionario_servico_ibfk_2` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`servico_id`);

--
-- Restrições para tabelas `horarios_indisponiveis`
--
ALTER TABLE `horarios_indisponiveis`
  ADD CONSTRAINT `horarios_indisponiveis_ibfk_1` FOREIGN KEY (`funcionario_id`) REFERENCES `funcionarios` (`funcionario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
