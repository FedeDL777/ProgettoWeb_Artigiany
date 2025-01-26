-- ***************************
-- * Standard SQL generation *
-- ***************************


-- Database Section
-- ________________
DROP DATABASE IF EXISTS db_artigiany;
CREATE DATABASE db_artigiany;
USE db_artigiany;


-- TableSpace Section
-- __________________

-- Table Section
-- _____________

create table CARRELLO (
	cartID INT not null AUTO_INCREMENT,
	Ora date not null,
	Email VARCHAR(100),
	Used boolean not null,
	primary key (cartID),
	unique (cartID));

create table CATEGORIE (
	categoryID INT not null AUTO_INCREMENT,
	Nome VARCHAR(25) not null,
	primary key (categoryID));

create table NOTIFICHE (
	NotifyID INT not null AUTO_INCREMENT,	
	Email VARCHAR(100) not null,
	Testo VARCHAR(1000) not null,
	Data_ date not null,
	Letto boolean not null,
	orderID INT,
	primary key (NotifyID));

create table ORDINI (
    orderID INT not null AUTO_INCREMENT,
    cartID INT not null,
    Data_ date not null,
    Luogo VARCHAR(25) not null,
    Numero CHAR(16) not null, -- Riferimento alla carta di credito
	Email VARCHAR(100) not null,
	Totale decimal(8,2) not null,
	primary key (orderID),
	unique (cartID));

create table PRODOTTO (
	Costo decimal(8,2) not null,
	Nome VARCHAR(25) not null,
	Descrizione VARCHAR(1000) not null,
	PathImmagine VARCHAR(255) not null,
	productID INT not null AUTO_INCREMENT,
	categoryID INT not null,
	Email VARCHAR(100) not null,
	primary key (productID),
	unique (categoryID, productID));

create table MATERIALE (
	Nome VARCHAR(25) not null,
	CostoXquadretto decimal(8,2),
	primary key (Nome));

create table CARTA_DI_CREDITO (
	Email VARCHAR(100) not null,
	Nome VARCHAR(25) not null,
	Cognome VARCHAR(25) not null,
	Numero CHAR(16) not null,
	Scadenza date not null,
	primary key (Email, Numero));

CREATE TABLE COMPOSIZIONE_CARRELLO (
    cartID INT NOT NULL,
    productID INT NOT NULL,
    Quantity INT NOT NULL CHECK (Quantity > 0), -- La quantità deve essere positiva
    PRIMARY KEY (cartID, productID)
);

create table DETTAGLIO_ORDINE (
	orderID INT not null,
	productID INT not null,
	primary key (orderID, productID));

create table PRODOTTOMATERIALE (
	Nome VARCHAR(25) not null,
	productID INT not null,
	primary key (productID, Nome));

create table USERS (
	Email VARCHAR(100) not null,
	Pw VARCHAR(255) not null,
	AdminClient boolean not null,
	luogo VARCHAR(255) NULL,
	primary key (Email));

CREATE TABLE CUSTOM_PRODUCT_GRID (
    gridID INT NOT NULL AUTO_INCREMENT,
    productID INT NOT NULL,
    row INT NOT NULL,
    col INT NOT NULL,
    material VARCHAR(25) NOT NULL,
    color VARCHAR(7) NOT NULL DEFAULT '#ffffff', -- Colonna per il colore del quadrato
    PRIMARY KEY (gridID),
    FOREIGN KEY (productID) REFERENCES PRODOTTO(productID),
    FOREIGN KEY (material) REFERENCES MATERIALE(Nome)
);


-- Constraints Section
-- ___________________

alter table CARRELLO add constraint FKRIEMPIRE
	foreign key (Email)
	references USERS;

alter table NOTIFICHE add constraint FKRiferito
	foreign key (orderID)
	references ORDINI;

alter table NOTIFICHE add constraint FKA
	foreign key (Email)
	references USERS;

alter table ORDINI add constraint FKGENERARE
	foreign key (cartID)
	references CARRELLO;


alter table CARRELLO add constraint 
	check(exist(select * from ORDINI
	            where ORDINI.cartID = cartID));

alter table PRODOTTO add constraint FKINSERIRE
	foreign key (Email)
	references USERS;

alter table PRODOTTO add constraint FKAPPARTENENZA_1
	foreign key (categoryID)
	references CATEGORIE;

alter table CARTA_DI_CREDITO add constraint FKR
	foreign key (Email)
	references USERS;

alter table COMPOSIZIONE_CARRELLO add constraint FKCOM_PRO
	foreign key (productID)
	references PRODOTTO;

alter table COMPOSIZIONE_CARRELLO add constraint FKCOM_CAR
	foreign key (cartID)
	references CARRELLO;

alter table DETTAGLIO_ORDINE add constraint FKDET_PRO
	foreign key (productID)
	references PRODOTTO;

alter table DETTAGLIO_ORDINE add constraint FKDET_ORD
	foreign key (orderID)
	references ORDINI;

alter table PRODOTTOMATERIALE add constraint FKPRO_PRO
	foreign key (productID)
	references PRODOTTO;

alter table PRODOTTO add constraint 
	check(exist(select * from PRODOTTOMATERIALE
	            where PRODOTTOMATERIALE.productID = productID));

alter table PRODOTTOMATERIALE add constraint FKPRO_MAT
	foreign key (Nome)
	references MATERIALE;


-- Index Section
-- _____________

create unique index ID_CARRELLO
	on CARRELLO(cartID);

create index FKRIEMPIRE
	on CARRELLO (Email);

create unique index ID_CATEGORIE
	on CATEGORIE(categoryID);

create unique index ID_NOTIFICHE
	on NOTIFICHE(NotifyID);

create index FKRiferito
	on NOTIFICHE (orderID);

create unique index ID_ORDINI
	on ORDINI(orderID);

create unique index FKGENERARE
	on ORDINI(cartID);

create unique index ID_PRODOTTO
	on PRODOTTO(productID);

create unique index SID_PRODOTTO
	on PRODOTTO(categoryID, productID);

create index FKINSERIRE
	on PRODOTTO (Email);

create unique index ID_MATERIALE
	on MATERIALE(Nome);

create unique index ID_CARTA_DI_CREDITO
	on CARTA_DI_CREDITO(Email, Numero);

create unique index ID_COMPOSIZIONE_CARRELLO
	on COMPOSIZIONE_CARRELLO(cartID, productID);

create index FKCOM_PRO
	on COMPOSIZIONE_CARRELLO (productID);

create unique index ID_DETTAGLIO_ORDINE
	on DETTAGLIO_ORDINE(orderID, productID);

create index FKDET_PRO
	on DETTAGLIO_ORDINE (productID);

create unique index ID_PRODOTTOMATERIALE
	on PRODOTTOMATERIALE(productID, Nome);

create index FKPRO_MAT
	on PRODOTTOMATERIALE (Nome);

create unique index ID_USERS
	on USERS(Email);

-- Popolamento del database db_artigiany

-- Popolamento tabella USERS
INSERT INTO USERS (Email, Pw, AdminClient) VALUES
('utente1@example.com', 'password1', false),
('utente2@example.com', 'password2', false),
('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', true);

-- Popolamento tabella CATEGORIE
INSERT INTO CATEGORIE (Nome) VALUES
('CUSTOM PRODUCT'),
('Gioielli'),
('Abbigliamento'),
('Decorazioni');

-- Popolamento tabella PRODOTTO
INSERT INTO PRODOTTO (Costo, Nome, Descrizione, PathImmagine, categoryID, Email) VALUES
(29.99, 'Anello di argento', 'Un bellissimo anello fatto a mano.', '../pages/images/anello.jpg', 2, 'utente1@example.com'),
(15.50, 'Sciarpa di lana', 'Sciarpa calda realizzata a maglia.', '../pages/images/sciarpa.jpg', 3, 'utente2@example.com'),
(45.00, 'Quadretto dipinto a mano', 'Quadretto decorativo per la casa.', '../pages/images/quadretto.jpg', 4, 'utente1@example.com');


-- Popolamento tabella MATERIALE
-- se un materiale costa zero non può essere utilizzato per la creazione di un prodotto custom
INSERT INTO MATERIALE (Nome, CostoXquadretto) VALUES
('Oro', 40.00),
('Argento', 10.00),
('Diamante', 1000.00),
('Legno di abete', 0.50),
('Legno di ebano', 1),
('Ferro', 2.50),
('Lana', 2),
('Acrilico', 0.00);


-- Popolamento tabella PRODOTTOMATERIALE
INSERT INTO PRODOTTOMATERIALE (Nome, productID) VALUES
('Argento', 1),
('Lana', 2),
('Acrilico', 3);

-- Popolamento tabella CARTA_DI_CREDITO
INSERT INTO CARTA_DI_CREDITO (Email, Nome, Cognome, Numero, Scadenza) VALUES
('utente1@example.com', 'Mario', 'Rossi', 1234567890123456, '2026-12-31'),
('utente2@example.com', 'Anna', 'Bianchi', 9876543210987654, '2025-11-30');

-- Popolamento tabella CARRELLO
INSERT INTO CARRELLO (Ora, Email, Used) VALUES
('2025-01-01', 'utente1@example.com', false),
('2025-01-02', 'utente2@example.com', false);

-- Popolamento tabella COMPOSIZIONE_CARRELLO
INSERT INTO COMPOSIZIONE_CARRELLO (cartID, productID, Quantity) VALUES
(1, 1, 3),
(1, 3, 1),
(2, 2, 9);

-- Popolamento tabella ORDINI
INSERT INTO ORDINI (cartID, Data_, Luogo) VALUES
(1, '2025-01-03', 'Bologna'),
(2, '2025-01-04', 'Imola');

-- Popolamento tabella DETTAGLIO_ORDINE
INSERT INTO DETTAGLIO_ORDINE (orderID, productID) VALUES
(1, 1),
(1, 3),
(2, 2);

-- Popolamento tabella NOTIFICHE
INSERT INTO NOTIFICHE (Email, Testo, Data_, Letto, orderID) VALUES
('utente1@example.com', 'Il tuo ordine è stato spedito.', '2025-01-03', true, 1),
('utente2@example.com', 'Il tuo ordine è stato ricevuto.', '2025-01-04', false, 2);
