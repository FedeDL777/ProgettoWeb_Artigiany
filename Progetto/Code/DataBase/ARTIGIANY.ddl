-- *********************************************
-- * SQL MySQL generation                      
-- *--------------------------------------------
-- * DB-MAIN version: 11.0.2              
-- * Generator date: Sep 14 2021              
-- * Generation date: Sun Jan 26 22:12:24 2025 
-- * LUN file: C:\Users\feded\Desktop\Università\Terzo_Anno\Tecnologie_web\Progetto_Magico\ProgettoWeb_Artigiany\Progetto\Code\DataBase\ARTIGIANY.lun 
-- * Schema: Nouvo con collegamento tra ordini e Carta di credito/1 
-- ********************************************* 


-- Database Section
-- ________________ 

DROP DATABASE IF EXISTS db_artigiany;
CREATE DATABASE db_artigiany;
USE db_artigiany;



-- Tables Section
-- _____________ 

create table CARRELLO (
	cartID INT not null AUTO_INCREMENT,
	Ora date not null,
	Email VARCHAR(100),
	Used boolean not null,
     constraint ID_CARRELLO_ID primary key (cartID),
     constraint SID_CARRELLO unique (cartID));

create table CARTA_DI_CREDITO (
	Email VARCHAR(100) not null,
	Nome VARCHAR(25) not null,
	Cognome VARCHAR(25) not null,
	Numero CHAR(16) not null,
	Scadenza date not null,
     constraint ID_CARTA_DI_CREDITO_ID primary key (Email, Numero));

create table CATEGORIE (
	categoryID INT not null AUTO_INCREMENT,
	Nome VARCHAR(25) not null,
     constraint ID_CATEGORIE_ID primary key (categoryID));

create table COMPOSIZIONE_CARRELLO (
    cartID INT NOT NULL,
    productID INT NOT NULL,
    Quantity INT NOT NULL CHECK (Quantity > 0),
     constraint ID_COMPOSIZIONE_CARRELLO_ID primary key (cartID, productID));

create table DETTAGLIO_ORDINE (
	orderID INT not null,
	productID INT not null,
     constraint ID_DETTAGLIO_ORDINE_ID primary key (orderID, productID));

create table MATERIALE (
	Nome VARCHAR(25) not null,
	CostoXquadretto decimal(8,2),
     constraint ID_MATERIALE_ID primary key (Nome));

create table NOTIFICHE (
	NotifyID INT not null AUTO_INCREMENT,	
	Email VARCHAR(100) not null,
	Testo VARCHAR(1000) not null,
	Data_ date not null,
	Letto boolean not null,
	orderID INT,
     constraint ID_NOTIFICHE_ID primary key (NotifyID));

create table ORDINI (
    orderID INT not null AUTO_INCREMENT,
    cartID INT not null,
    Data_ date not null,
    Luogo VARCHAR(25) not null,
    Numero CHAR(16) not null, -- Riferimento alla carta di credito
	Email VARCHAR(100) not null,
	Totale decimal(8,2) not null,
     constraint ID_ORDINI_ID primary key (orderID),
     constraint FKGENERARE_ID unique (cartID));

create table PRODOTTO (
	Costo decimal(8,2) not null,
	Nome VARCHAR(25) not null,
	Descrizione VARCHAR(1000) not null,
	PathImmagine VARCHAR(255) not null,
	productID INT not null AUTO_INCREMENT,
	categoryID INT not null,
	Email VARCHAR(100) not null,
     constraint ID_PRODOTTO_ID primary key (productID),
     constraint SID_PRODOTTO_ID unique (categoryID, productID));

create table PRODOTTOMATERIALE (
	Nome VARCHAR(25) not null,
	productID INT not null,
     constraint ID_PRODOTTOMATERIALE_ID primary key (productID, Nome));

create table USERS (
	Email VARCHAR(100) not null,
	Pw VARCHAR(255) not null,
	AdminClient boolean not null,
	luogo VARCHAR(255) NULL,
     constraint ID_USERS_ID primary key (Email));


-- Constraints Section
-- ___________________ 

-- Not implemented
-- alter table CARRELLO add constraint ID_CARRELLO_CHK
--     check(exists(select * from ORDINI
--                  where ORDINI.cartID = cartID)); 

alter table CARRELLO add constraint FKRIEMPIRE_FK
     foreign key (Email)
     references USERS (Email);

alter table CARTA_DI_CREDITO add constraint FKR
     foreign key (Email)
     references USERS (Email);

alter table COMPOSIZIONE_CARRELLO add constraint FKCOM_PRO_FK
     foreign key (productID)
     references PRODOTTO (productID);

alter table COMPOSIZIONE_CARRELLO add constraint FKCOM_CAR
     foreign key (cartID)
     references CARRELLO (cartID);

alter table DETTAGLIO_ORDINE add constraint FKDET_PRO_FK
     foreign key (productID)
     references PRODOTTO (productID);

alter table DETTAGLIO_ORDINE add constraint FKDET_ORD
     foreign key (orderID)
     references ORDINI (orderID);

alter table NOTIFICHE add constraint FKRiferito_FK
     foreign key (orderID)
     references ORDINI (orderID);

alter table NOTIFICHE add constraint FKA
     foreign key (Email)
     references USERS (Email);

alter table ORDINI add constraint FKGENERARE_FK
     foreign key (cartID)
     references CARRELLO (cartID);


-- Not implemented
-- alter table PRODOTTO add constraint ID_PRODOTTO_CHK
--     check(exists(select * from PRODOTTOMATERIALE
--                  where PRODOTTOMATERIALE.productID = productID)); 

alter table PRODOTTO add constraint FKINSERIRE_FK
     foreign key (Email)
     references USERS (Email);

alter table PRODOTTO add constraint FKAPPARTENENZA_1
     foreign key (categoryID)
     references CATEGORIE (categoryID);

alter table PRODOTTOMATERIALE add constraint FKPRO_PRO
     foreign key (productID)
     references PRODOTTO (productID);

alter table PRODOTTOMATERIALE add constraint FKPRO_MAT_FK
     foreign key (Nome)
     references MATERIALE (Nome);


-- Index Section
-- _____________ 

create unique index ID_CARRELLO_IND
     on CARRELLO (cartID);

create index FKRIEMPIRE_IND
     on CARRELLO (Email);

create unique index ID_CARTA_DI_CREDITO_IND
     on CARTA_DI_CREDITO (Email, Numero);

create unique index ID_CATEGORIE_IND
     on CATEGORIE (categoryID);

create unique index ID_COMPOSIZIONE_CARRELLO_IND
     on COMPOSIZIONE_CARRELLO (cartID, productID);

create index FKCOM_PRO_IND
     on COMPOSIZIONE_CARRELLO (productID);

create unique index ID_DETTAGLIO_ORDINE_IND
     on DETTAGLIO_ORDINE (orderID, productID);

create index FKDET_PRO_IND
     on DETTAGLIO_ORDINE (productID);

create unique index ID_MATERIALE_IND
     on MATERIALE (Nome);

create unique index ID_NOTIFICHE_IND
     on NOTIFICHE (Email, Data_);

create index FKRiferito_IND
     on NOTIFICHE (orderID);

create unique index ID_ORDINI_IND
     on ORDINI (orderID);

create unique index SID_ORDINI_IND
     on ORDINI (Email, Numero, Luogo);

create unique index FKGENERARE_IND
     on ORDINI (cartID);

create unique index ID_PRODOTTO_IND
     on PRODOTTO (productID);

create unique index SID_PRODOTTO_IND
     on PRODOTTO (categoryID, productID);

create index FKINSERIRE_IND
     on PRODOTTO (Email);

create unique index ID_PRODOTTOMATERIALE_IND
     on PRODOTTOMATERIALE (productID, Nome);

create index FKPRO_MAT_IND
     on PRODOTTOMATERIALE (Nome);

create unique index ID_USERS_IND
     on USERS (Email);


-- Popolamento del database db_artigiany

-- Popolamento tabella USERS
INSERT INTO USERS (Email, Pw, AdminClient) VALUES
('utente1@example.com', 'password1', false),
('utente2@example.com', 'password2', false),
('admin@example.com', 'adminpass', true);

-- Popolamento tabella CATEGORIE
INSERT INTO CATEGORIE (Nome) VALUES
('Gioielli'),
('Abbigliamento'),
('Decorazioni');

-- Popolamento tabella PRODOTTO
INSERT INTO PRODOTTO (Costo, Nome, Descrizione, PathImmagine, categoryID, Email) VALUES
(29.99, 'Anello di argento', 'Un bellissimo anello fatto a mano.', '../pages/images/anello.jpg', 1, 'utente1@example.com'),
(15.50, 'Sciarpa di lana', 'Sciarpa calda realizzata a maglia.', '../pages/images/sciarpa.jpg', 2, 'utente2@example.com'),
(45.00, 'Quadretto dipinto a mano', 'Quadretto decorativo per la casa.', '../pages/images/quadretto.jpg', 3, 'utente1@example.com');


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
