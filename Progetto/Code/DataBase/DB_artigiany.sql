-- ***************************
-- * Standard SQL generation *
-- ***************************


-- Database Section
-- ________________

create database ErFinale copia;


-- TableSpace Section
-- __________________

-- Table Section
-- _____________

create table CARRELLO (
	cartID char(1) not null,
	Ora date not null,
	Email char(1),
	primary key (cartID),
	unique (cartID));

create table CATEGORIE (
	categoryID char(1) not null,
	Nome char(1) not null,
	primary key (categoryID));

create table NOTIFICHE (
	Email char(1) not null,
	Testo char(1) not null,
	Data date not null,
	Letto char not null,
	orderID char(1),
	primary key (Email, Data));

create table ORDINI (
	orderID char(1) not null,
	cartID char(1) not null,
	Data date not null,
	Luogo char(1) not null,
	primary key (orderID),
	unique (cartID));

create table PRODOTTO (
	Costo float(1) not null,
	Nome char(1) not null,
	Descrizione char(255) not null,
	Immagine char(1) not null,
	productID char(1) not null,
	categoryID char(1) not null,
	Email char(1) not null,
	primary key (productID),
	unique (categoryID, productID));

create table MATERIALE (
	Nome char(1) not null,
	CostoXquadretto char(1),
	Colore char(1),
	primary key (Nome));

create table PRODOTTOMATERIALE (
	Nome char(1) not null,
	productID char(1) not null,
	primary key (productID, Nome));

create table COMPOSIZIONE CARRELLO (
	cartID char(1) not null,
	productID char(1) not null,
	primary key (cartID, productID));

create table DETTAGLIO ORDINE (
	orderID char(1) not null,
	productID char(1) not null,
	primary key (orderID, productID));

create table USERS (
	Email char(1) not null,
	Password char(1) not null,
	Admin/Client char not null,
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

alter table PRODOTTOMATERIALE add constraint FKR_PRO
	foreign key (productID)
	references PRODOTTO;

alter table PRODOTTO add constraint 
	check(exist(select * from PRODOTTOMATERIALE
	            where PRODOTTOMATERIALE.productID = productID));

alter table PRODOTTOMATERIALE add constraint FKR_MAT
	foreign key (Nome)
	references MATERIALE;

alter table COMPOSIZIONE CARRELLO add constraint FKR_1_PRO
	foreign key (productID)
	references PRODOTTO;

alter table COMPOSIZIONE CARRELLO add constraint FKR_1_CAR
	foreign key (cartID)
	references CARRELLO;

alter table DETTAGLIO ORDINE add constraint FKR_3_PRO
	foreign key (productID)
	references PRODOTTO;

alter table DETTAGLIO ORDINE add constraint FKR_3_ORD
	foreign key (orderID)
	references ORDINI;


-- Index Section
-- _____________

create unique index ID_CARRELLO
	on CARRELLO(cartID);

create index FKRIEMPIRE
	on CARRELLO (Email);

create unique index ID_CATEGORIE
	on CATEGORIE(categoryID);

create unique index ID_NOTIFICHE
	on NOTIFICHE(Email, Data);

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

create unique index ID_R
	on PRODOTTOMATERIALE(productID, Nome);

create index FKR_MAT
	on PRODOTTOMATERIALE (Nome);

create unique index ID_R_1
	on COMPOSIZIONE CARRELLO(cartID, productID);

create index FKR_1_PRO
	on COMPOSIZIONE CARRELLO (productID);

create unique index ID_R_3
	on DETTAGLIO ORDINE(orderID, productID);

create index FKR_3_PRO
	on DETTAGLIO ORDINE (productID);

create unique index ID_USERS
	on USERS(Email);

