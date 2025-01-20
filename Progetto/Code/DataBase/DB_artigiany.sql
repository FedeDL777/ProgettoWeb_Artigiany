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
	Email VARCHAR(25),
	primary key (cartID),
	unique (cartID));

create table CATEGORIE (
	categoryID INT not null AUTO_INCREMENT,
	Nome VARCHAR(25) not null,
	primary key (categoryID));

create table NOTIFICHE (
	Email VARCHAR(25) not null,
	Testo VARCHAR(1000) not null,
	Data_ date not null,
	Letto boolean not null,
	orderID INT,
	primary key (Email, Data_));

create table ORDINI (
	orderID INT not null AUTO_INCREMENT,
	cartID INT not null,
	Data_ date not null,
	Luogo VARCHAR(25) not null,
	primary key (orderID),
	unique (cartID));

create table PRODOTTO (
	Costo decimal(8,2) not null,
	Nome VARCHAR(25) not null,
	Descrizione VARCHAR(1000) not null,
	PathImmagine VARCHAR(255) not null,
	productID INT not null AUTO_INCREMENT,
	categoryID INT not null,
	Email VARCHAR(25) not null,
	primary key (productID),
	unique (categoryID, productID));

create table MATERIALE (
	Nome VARCHAR(25) not null,
	CostoXquadretto decimal(8,2),
	Colore VARCHAR(25),
	primary key (Nome));

create table CARTA_DI_CREDITO (
	Email VARCHAR(25) not null,
	Nome VARCHAR(25) not null,
	Cognome VARCHAR(25) not null,
	Numero INT not null,
	Scadenza date not null,
	primary key (Email, Numero));

create table COMPOSIZIONE_CARRELLO (
	cartID INT not null,
	productID INT not null,
	primary key (cartID, productID));

create table DETTAGLIO_ORDINE (
	orderID INT not null,
	productID INT not null,
	primary key (orderID, productID));

create table PRODOTTOMATERIALE (
	Nome VARCHAR(25) not null,
	productID INT not null,
	primary key (productID, Nome));

create table USERS (
	Email VARCHAR(25) not null,
	Password VARCHAR(25) not null,
	AdminClient boolean not null,
	primary key (Email));


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
	on NOTIFICHE(Email, Data_);

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

