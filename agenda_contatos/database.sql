CREATE DATABASE IF NOT EXISTS agenda_contatos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agenda_contatos;

CREATE TABLE IF NOT EXISTS contatos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    telefone VARCHAR(30) NOT NULL,
    email VARCHAR(150),
    foto VARCHAR(255),
    endereco VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO contatos (nome, telefone, email, endereco) VALUES
('Ana Souza', '(11) 99999-1111', 'ana@email.com', 'São Paulo - SP'),
('Carlos Oliveira', '(11) 98888-2222', 'carlos@email.com', 'Campinas - SP'),
('Mariana Lima', '(21) 97777-3333', 'mariana@email.com', 'Rio de Janeiro - RJ');
