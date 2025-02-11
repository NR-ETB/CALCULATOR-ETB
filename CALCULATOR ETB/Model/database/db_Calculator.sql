
drop database calculator;
create database calculator;
use calculator;

create table incentivos(

	id_Inc INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    cla_Inc VARCHAR(45),
    pro_Inc VARCHAR(45),
	com_Inc VARCHAR(45)
    
)AUTO_INCREMENT = 1000;

create table retenciones(

	id_Ret INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	cant_Ret VARCHAR(45),
	com_Ret VARCHAR(45)
    
)AUTO_INCREMENT = 2000;

create table usuario(

	id_Usu INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_Inc INT NOT NULL, FOREIGN KEY (id_Inc) references incentivos(id_Inc),
	id_Ret INT NOT NULL, FOREIGN KEY (id_Ret) references retenciones(id_Ret),
    nom_Usu VARCHAR(45) NOT NULL,
    cel_Usu BIGINT NOT NULL,
	usu_Usu VARCHAR(45) NOT NULL,
	con_Usu VARCHAR(45) NOT NULL,
	cantI_Usu VARCHAR(45),
	cantR_Usu VARCHAR(45)
    
)AUTO_INCREMENT = 1;

create table calculadora(

	id_Cal INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	id_Usu INT NOT NULL, FOREIGN KEY (id_Usu) references usuario(id_Usu),
	cant_Cal BIGINT
    
)AUTO_INCREMENT = 3000;

INSERT INTO incentivos(cla_Inc, pro_Inc, com_Inc) VALUES
("LT DUO TRIO", "FTTH", 7000),
("UP GRANDES Y SVA", "SVA", 700),
("UP GRANDES Y SVA", "DGO", 500),
("MOVILES", "POSPAGO", 30000),
("MOVILES", "DGO", 400);

INSERT INTO retenciones(cant_Ret, com_Ret) VALUES
("1 a 90 EFECTIVAS", 500),
("91 a 145 EFECTIVAS", 3000),
("146 EN ADELANTE EFECTIVAS", 5000);

INSERT INTO usuario(id_Inc, id_Ret, nom_Usu, cel_Usu, usu_Usu, con_Usu, CantI_Usu, CantR_Usu) VALUES
(1000, 2000, "Nikolas", 3167468392, "DK", 1234, 0, 0);

INSERT INTO calculadora(id_Cal, id_Usu, cant_Cal) VALUES
(3000, 1, 0);