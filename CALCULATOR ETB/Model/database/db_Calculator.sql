
drop database calculator;
create database calculator;
use calculator;

create table incentivos(

	id_Inc INT PRIMARY KEY AUTO_INCREMENT,
    cla_Inc VARCHAR(45),
    pro_Inc VARCHAR(45),
	com_Inc VARCHAR(45),
	cant_Inc VARCHAR(45)
    
)AUTO_INCREMENT = 1000;

create table retenciones(

	id_Ret INT PRIMARY KEY AUTO_INCREMENT,
	cant_Ret VARCHAR(45),
	com_Ret VARCHAR(45),
	num_Ret VARCHAR(45)
    
)AUTO_INCREMENT = 2000;

create table usuario(

	id_Usu INT PRIMARY KEY AUTO_INCREMENT,
	id_Inc INT, FOREIGN KEY (id_Inc) references incentivos(id_Inc),
	id_Ret INT, FOREIGN KEY (id_Ret) references retenciones(id_Ret),
	cant_Ret VARCHAR(45),
	com_Ret VARCHAR(45),
	num_Ret VARCHAR(45)
    
)AUTO_INCREMENT = 1;