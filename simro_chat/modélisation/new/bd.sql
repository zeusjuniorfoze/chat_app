/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     18/02/2024 16:51:19                          */
/*==============================================================*/

drop table if exists USER_DISCUSSION;
drop table if exists USER_GROUP;
drop table if exists MESSAGE;
drop table if exists USER;
drop table if exists DISCUSSION;
drop table if exists GROUPE;

/*==============================================================*/
/* Table: DISCUSSION                                            */
/*==============================================================*/
create table DISCUSSION
(
   IDD                  int not null auto_increment,
   NOM_DIS              varchar(100),
   DATE_CREATION        date,
   primary key (IDD)
);

/*==============================================================*/
/* Table: GROUPE                                                */
/*==============================================================*/
create table GROUPE
(
   IDG                  int not null auto_increment,
   NOM                  varchar(100),
   DESCRIPTION          varchar(100),
   DATE_DE_CREATION     date,
   primary key (IDG)
);

/*==============================================================*/
/* Table: USER                                                  */
/*==============================================================*/
create table USER
(
   ID                   int not null auto_increment,
   TYPE_                varchar(100),
   NOM                  varchar(100),
   EMAIL                varchar(100),
   STATUT_              varchar(100),
   DATE_DE_CREATION_    varchar(50),
   DATE_DE_CREATION     datetime,
   MOT_DE_PASS          varchar(50),
   primary key (ID)
);

/*==============================================================*/
/* Table: MESSAGE                                               */
/*==============================================================*/
create table MESSAGE
(
   IDM                  int not null auto_increment,
   IDG                  int not null,
   ID                   int not null,
   IDD                  int not null,
   IDR                  int,
   CONTENU              varchar(5000),
   STATUT               varchar(50),
   HEURE_ENVOI          time,
   primary key (IDM),
   foreign key (IDD) references DISCUSSION(IDD) on delete cascade on update cascade,
   foreign key (IDG) references GROUPE(IDG) on delete cascade on update cascade,
   foreign key (ID) references USER(ID) on delete cascade on update cascade
);

/*==============================================================*/
/* Table: USER_DISCUSSION                                       */
/*==============================================================*/
create table USER_DISCUSSION
(
   IDD                  int not null,
   ID                   int not null,
   IDR                  int,
   primary key (IDD, ID),
   foreign key (IDD) references DISCUSSION(IDD) on delete cascade on update cascade,
   foreign key (ID) references USER(ID) on delete cascade on update cascade
);

/*==============================================================*/
/* Table: USER_GROUP                                            */
/*==============================================================*/
create table USER_GROUP
(
   ID                   int not null,
   IDG                  int not null,
   TYPE_USER_GROUP      varchar(50),
   DATE_D_AJOUT         date,
   primary key (ID, IDG),
   foreign key (ID) references USER(ID) on delete cascade on update cascade,
   foreign key (IDG) references GROUPE(IDG) on delete cascade on update cascade
);
