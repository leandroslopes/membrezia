

CREATE TABLE `congregacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `rua` varchar(70) DEFAULT NULL,
  `bairro` varchar(70) DEFAULT NULL,
  `complemento` varchar(70) DEFAULT NULL,
  `cidade` varchar(70) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `fone` varchar(15) DEFAULT NULL,
  `data_fundacao` varchar(10) DEFAULT NULL,
  `is_padrao` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=PADRAO 0=NAO PADRAO',
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


INSERT INTO congregacao(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_fundacao, is_padrao, id_usuario_cadastro, data_cadastro) VALUES ('1', 'SEDE', 'RUA 01', 'LA BELLE PARK', 'QD 01', 'PACO DO LUMIAR', '10', '98 44444-4444', '01/01/2020', '1', '1', '2020-03-18 00:23:03'); 

INSERT INTO congregacao(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_fundacao, is_padrao, id_usuario_cadastro, data_cadastro) VALUES ('2', 'CONGREGACAO PARANA', 'AVENIDA 01', 'PARANA', 'QD 01', 'PACO DO LUMIAR', '10', '98 33333-3333', '01/01/2000', '0', '1', '2020-03-18 00:23:03'); 

INSERT INTO congregacao(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_fundacao, is_padrao, id_usuario_cadastro, data_cadastro) VALUES ('3', 'CONGREGACAO MAIOBA', 'AVENIDA PRINCIPAL', 'MAIOBA', 'QD 1', 'PACO DO LUMIAR', '10', '98 22222-2222', '01/01/2020', '0', '1', '2020-03-18 00:22:25'); 

INSERT INTO congregacao(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_fundacao, is_padrao, id_usuario_cadastro, data_cadastro) VALUES ('4', 'CONGREGACAO VILA SAO JOSE', '', '', '', '', '10', '', '', '0', '1', '2020-03-18 22:09:33'); 


CREATE TABLE `congregacao_culto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_congregacao` int(11) NOT NULL,
  `id_culto` int(11) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO congregacao_culto(id, id_congregacao, id_culto, id_usuario_cadastro, data_cadastro) VALUES ('2', '1', '1', '1', '2020-03-15 20:53:19'); 

INSERT INTO congregacao_culto(id, id_congregacao, id_culto, id_usuario_cadastro, data_cadastro) VALUES ('3', '1', '2', '1', '2020-03-18 02:15:50'); 


CREATE TABLE `culto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO culto(id, nome, id_usuario_cadastro, data_cadastro) VALUES ('1', 'CULTO DA FAMILIA', '1', '2020-03-14 19:56:38'); 

INSERT INTO culto(id, nome, id_usuario_cadastro, data_cadastro) VALUES ('2', 'DIA DA DEDICACAO', '1', '2020-03-18 02:13:58'); 


CREATE TABLE `despesa_tipo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


INSERT INTO despesa_tipo(id, nome, id_usuario_cadastro, data_cadastro) VALUES ('5', 'MIX MATEUS MAIOBAO', '1', '2020-03-18 22:21:50'); 

INSERT INTO despesa_tipo(id, nome, id_usuario_cadastro, data_cadastro) VALUES ('6', 'EQUATORIAL ENERGIA', '1', '2020-03-17 11:29:13'); 

INSERT INTO despesa_tipo(id, nome, id_usuario_cadastro, data_cadastro) VALUES ('7', 'ESTRELA NET', '1', '2020-03-17 14:55:16'); 


CREATE TABLE `estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sigla` varchar(2) NOT NULL,
  `nome` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;


INSERT INTO estado(id, sigla, nome) VALUES ('1', 'AC', 'ACRE'); 

INSERT INTO estado(id, sigla, nome) VALUES ('2', 'AL', 'ALAGOAS'); 

INSERT INTO estado(id, sigla, nome) VALUES ('3', 'AP', 'AMAPA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('4', 'AM', 'AMAZONAS'); 

INSERT INTO estado(id, sigla, nome) VALUES ('5', 'BA', 'BAHIA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('6', 'CE', 'CEARA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('7', 'DF', 'DISTRITO FEDERAL'); 

INSERT INTO estado(id, sigla, nome) VALUES ('8', 'ES', 'ESPIRITO SANTO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('9', 'GO', 'GOIAS'); 

INSERT INTO estado(id, sigla, nome) VALUES ('10', 'MA', 'MARANHAO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('11', 'MT', 'MATO GROSSO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('12', 'MS', 'MATO GROSSO DO SUL'); 

INSERT INTO estado(id, sigla, nome) VALUES ('13', 'MG', 'MINAS GERAIS'); 

INSERT INTO estado(id, sigla, nome) VALUES ('14', 'PA', 'PARA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('15', 'PB', 'PARAIBA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('16', 'PR', 'PARANA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('17', 'PE', 'PERNAMBUCO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('18', 'PI', 'PIAUI'); 

INSERT INTO estado(id, sigla, nome) VALUES ('19', 'RJ', 'RIO DE JANEIRO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('20', 'RN', 'RIO GRANDE DO NORTE'); 

INSERT INTO estado(id, sigla, nome) VALUES ('21', 'RS', 'RIO GRANDE DO SUL'); 

INSERT INTO estado(id, sigla, nome) VALUES ('22', 'RO', 'RONDONIA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('23', 'RR', 'RORAIMA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('24', 'SC', 'SANTA CATARINA'); 

INSERT INTO estado(id, sigla, nome) VALUES ('25', 'SP', 'SAO PAULO'); 

INSERT INTO estado(id, sigla, nome) VALUES ('26', '', 'SERGIPE'); 

INSERT INTO estado(id, sigla, nome) VALUES ('27', '', 'TOCANTINS'); 


CREATE TABLE `funcao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sigla` varchar(4) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


INSERT INTO funcao(id, sigla, nome, id_usuario_cadastro, data_cadastro) VALUES ('1', 'MEM', 'MEMBRO', '1', '2020-03-14 18:06:28'); 

INSERT INTO funcao(id, sigla, nome, id_usuario_cadastro, data_cadastro) VALUES ('3', 'PR', 'PASTOR', '1', '2020-03-22 11:52:57'); 


CREATE TABLE `membro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `rua` varchar(70) NOT NULL,
  `bairro` varchar(70) NOT NULL,
  `complemento` varchar(70) NOT NULL,
  `cidade` varchar(70) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fone` varchar(15) NOT NULL,
  `data_nascimento` varchar(10) NOT NULL,
  `sexo` enum('F','M') NOT NULL,
  `estado_civil` enum('C','S','V','D') NOT NULL,
  `naturalidade` varchar(70) NOT NULL,
  `rg` varchar(11) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `id_congregacao` int(11) NOT NULL COMMENT 'Se tiver congregacao, eh membro',
  `id_funcao` int(11) NOT NULL COMMENT 'Funcao eclesiastica',
  `id_cargo` int(11) DEFAULT NULL COMMENT 'Defini acesso aos modulos',
  `data_batismo` varchar(10) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `situacao` enum('L','D') DEFAULT NULL COMMENT 'Refere-se a situacao do membro no ministerio. L=LIGADO D=DESLIGADO NULL=NAO MEMBRO',
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


INSERT INTO membro(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, id_cargo, data_batismo, senha, situacao, id_usuario_cadastro, data_cadastro) VALUES ('1', 'ADMINISTRATOR', '', '', '', '', '0', '', '00/00/0000', '', '', '', '', '11133355577', '0', '0', '1', '', 'e09728957dbb28e0e843c360016eb312', '', '1', '2020-03-17 09:12:29'); 

INSERT INTO membro(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, id_cargo, data_batismo, senha, situacao, id_usuario_cadastro, data_cadastro) VALUES ('3', 'TESTE TESTE', 'TESTE TESTE', 'TESTE TESTE', 'TESTE TESTE', 'TESTE TESTE', '1', '11 11111-1111', '01/01/2020', 'M', 'S', 'TESTE/MA', '22222222222', '22222222222', '1', '1', '', '01/01/2020', '', 'L', '1', '2020-03-14 20:35:08'); 

INSERT INTO membro(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, id_cargo, data_batismo, senha, situacao, id_usuario_cadastro, data_cadastro) VALUES ('4', 'MARIA COSTA', '', '', '', '', '10', '', '01/01/1990', '', '', '', '', '', '1', '1', '', '', '', 'L', '1', '2020-03-18 03:11:18'); 

INSERT INTO membro(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, id_cargo, data_batismo, senha, situacao, id_usuario_cadastro, data_cadastro) VALUES ('5', 'JOSE SILVA', 'AVENIDA 01', 'MAIOBAO', 'QD 01', 'PACO DO LUMIAR', '10', '98 11111-1111', '01/01/2000', 'M', 'S', 'SAO LUIS/MA', '', '', '1', '3', '', '', '', 'L', '1', '2020-03-22 11:55:53'); 

INSERT INTO membro(id, nome, rua, bairro, complemento, cidade, id_estado, fone, data_nascimento, sexo, estado_civil, naturalidade, rg, cpf, id_congregacao, id_funcao, id_cargo, data_batismo, senha, situacao, id_usuario_cadastro, data_cadastro) VALUES ('6', 'JOAO', '', '', '', '', '10', '', '01/01/1995', '', '', '', '', '', '0', '0', '', '', '', '', '1', '2020-03-18 05:03:16'); 


CREATE TABLE `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  `diretorio` varchar(15) NOT NULL,
  `icone` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('1', 'CONFIGURAÇÕES', 'configuracoes', 'imgAdmin'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('2', 'CADASTRO', 'cadastro', 'imgCadastro'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('3', 'CONGREGAÇÃO', 'congregacao', 'imgCongregacao'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('4', 'CULTO', 'culto', 'imgCulto'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('5', 'PERFIL', 'perfil', 'imgPerfil'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('6', 'RELATÓRIO', 'relatorio', 'imgRelatorio'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('7', 'MOVIMENTAÇÃO', 'movimentacao', 'imgMovimentacao'); 

INSERT INTO modulo(id, nome, diretorio, icone) VALUES ('8', 'FUNÇÃO', 'funcao', 'imgFuncao'); 


CREATE TABLE `modulo_acesso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modulo` int(11) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;


INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('1', '1', '1', '1', '2020-03-05 19:38:10'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('2', '2', '1', '1', '2020-03-05 19:38:10'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('3', '3', '1', '1', '2020-03-05 19:38:33'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('4', '4', '1', '1', '2020-03-05 19:38:33'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('5', '5', '1', '1', '2020-03-05 19:38:55'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('6', '6', '1', '1', '2020-03-05 19:38:55'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('7', '7', '1', '1', '2020-03-05 19:39:06'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('13', '8', '1', '1', '2020-03-06 01:45:07'); 

INSERT INTO modulo_acesso(id, id_modulo, id_cargo, id_usuario_cadastro, data_cadastro) VALUES ('16', '2', '2', '1', '2020-03-22 12:01:25'); 


CREATE TABLE `movimentacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` double NOT NULL,
  `tipo` enum('DE','DI','OF','RE') NOT NULL COMMENT 'DE=DESPESA DI=DIZIMO OF=OFERTA RE=RECEITA',
  `id_despesa_tipo` int(11) DEFAULT NULL COMMENT 'REFERENTE A DESPESA',
  `id_congregacao` int(11) DEFAULT NULL COMMENT 'REFERENTE A CONGREGACAO (PARA DESPESA)',
  `id_congregacao_culto` int(11) DEFAULT NULL COMMENT 'REFERENTE A OFERTA DO CULTO (CONGREGACAO_CULTO)',
  `id_contribuinte` int(11) DEFAULT NULL,
  `data_movimentacao` varchar(10) NOT NULL,
  `id_usuario_cadastro` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;


INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('6', '104.5', 'DI', '', '0', '', '3', '2020-03-15', '1', '2020-03-15 19:47:53'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('13', '10', 'OF', '', '0', '2', '', '2020-03-01', '1', '2020-03-15 22:34:23'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('15', '104.55', 'DE', '6', '2', '', '', '2020-03-02', '1', '2020-03-17 11:32:39'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('17', '39.99', 'DE', '5', '1', '', '', '2020-03-01', '1', '2020-03-18 04:14:45'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('18', '100.5', 'OF', '', '', '3', '', '2020-03-10', '1', '2020-03-18 02:48:50'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('19', '55', 'OF', '', '', '2', '', '2020-03-15', '1', '2020-03-18 02:49:21'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('20', '54.5', 'DI', '', '', '', '4', '2020-03-10', '1', '2020-03-18 03:35:01'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('21', '200.99', 'DE', '7', '1', '', '', '2020-03-17', '1', '2020-03-18 04:12:51'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('23', '20', 'OF', '', '3', '', '4', '2020-03-04', '1', '2020-03-18 14:26:43'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('24', '100', 'OF', '', '2', '', '6', '2020-03-07', '1', '2020-03-18 14:26:03'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('25', '250', 'OF', '', '2', '', '5', '2020-03-10', '1', '2020-03-18 14:20:38'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('26', '111.11', 'OF', '', '3', '', '3', '2020-03-04', '1', '2020-03-18 14:19:34'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('27', '222.22', 'OF', '', '1', '', '3', '2020-03-04', '1', '2020-03-18 13:55:04'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('31', '1805.74', 'RE', '', '1', '', '', '2020-04-01', '1', '2020-03-18 21:39:18'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('32', '100', 'RE', '', '1', '', '', '2020-03-01', '1', '2020-03-21 02:52:40'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('33', '100', 'DI', '', '', '', '5', '2020-03-22', '1', '2020-03-22 11:57:53'); 

INSERT INTO movimentacao(id, valor, tipo, id_despesa_tipo, id_congregacao, id_congregacao_culto, id_contribuinte, data_movimentacao, id_usuario_cadastro, data_cadastro) VALUES ('34', '100', 'OF', '', '3', '', '5', '2020-03-22', '1', '2020-03-22 11:59:43'); 
