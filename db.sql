CREATE DATABASE sistema;
USE sistema;

CREATE TABLE IF NOT EXISTS login (
            `id` INT AUTO_INCREMENT,
            `user` VARCHAR(20),
            `password` VARCHAR(30),            
            PRIMARY KEY (`id`)
        );

CREATE TABLE IF NOT EXISTS clients (
            `id` INT AUTO_INCREMENT,
            `name` VARCHAR(50),
            `ip_adress` VARCHAR(15),
            `home_adress` VARCHAR(30),
            `phone` VARCHAR(10),
            `month_payment` VARCHAR(10),
            `mbps` VARCHAR(10),
            `install_date` VARCHAR(10),
	        `status` VARCHAR (10),
            PRIMARY KEY (`id`)
        );

CREATE TABLE IF NOT EXISTS payments(
            `id` INT AUTO_INCREMENT,
            `year` VARCHAR(10),
            `month` VARCHAR(50),
            `payment` VARCHAR(10),
            `payment_date` VARCHAR(10),
	        `id_rel` INT(10),
	        PRIMARY KEY (`id`)
        );

CREATE TABLE IF NOT EXISTS history (
            `id` INT AUTO_INCREMENT,
            `description` VARCHAR(20),
            `payment_date` VARCHAR(10),
            `payment` VARCHAR(10),
            `id_rel` INT(10),
            PRIMARY KEY (`id`)
        );

/*Procedimientos almacenados para la tabla de clientes*/

DELIMITER $$
CREATE PROCEDURE add_client (nameP VARCHAR(50), ipP VARCHAR(15),
homeP VARCHAR(30),phoneP VARCHAR(10), paymentP VARCHAR(10), mbpsP VARCHAR(10), dateP VARCHAR(15))
BEGIN
    INSERT INTO clients VALUES(null,nameP,ipP,homeP,phoneP,paymentP,mbpsP,dateP,'Activo'); 
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE edit_client (idP VARCHAR(10), nameP VARCHAR(50),ipP VARCHAR(15),
homeP VARCHAR(30), phoneP VARCHAR(10), paymentP VARCHAR(10), mbpsP VARCHAR(10),dateP VARCHAR(10))
BEGIN
    UPDATE clients SET name=nameP,ip_adress=ipP,home_adress=homeP,phone=phoneP,
    month_payment=paymentP,mbps=mbpsP,install_date=dateP WHERE id=idP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE delete_client (idP VARCHAR(10))
BEGIN
    DELETE FROM clients WHERE id=idP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE upload_client (idP VARCHAR(10))
BEGIN
    SELECT * FROM clients WHERE id=idP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE consult_client (view VARCHAR(10), order_ip VARCHAR(5))
BEGIN
    IF order_ip='asc' THEN
        SELECT * FROM clients  WHERE CASE 
            WHEN view='all' THEN  status='Activo' or status='Inactivo'
            WHEN view='debtors' THEN  status=view
            ELSE status=view
        END ORDER BY ip_adress ASC;
    ELSE
        SELECT * FROM clients  WHERE CASE 
            WHEN view='all' THEN  status='Activo' or status='Inactivo'
            WHEN view='debtors' THEN  status=view
            ELSE status=view
        END ORDER BY ip_adress DESC;
    END IF;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE search_client (view VARCHAR(10), string VARCHAR(50))
BEGIN
    SELECT * FROM clients  WHERE CASE 
        WHEN view='all' THEN  (status='Activo' or status='Inactivo')
        WHEN view='debtors' THEN  status=view
        ELSE status=view
    END AND name LIKE string ORDER BY ip_adress;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE edit_status (idP VARCHAR(10),statusP VARCHAR(10), dateP VARCHAR(10))
BEGIN
    UPDATE clients SET status=statusP WHERE id=idP;
    
    IF statusP='Inactivo' THEN
        CALL add_history ('Corte',dateP,'0',idP);
    ELSE
        CALL add_history ('Reactivado',dateP,'0',idP);
    END IF;
END $$
DELIMITER ;

/* Procedimientos almacenados para la tabla de pagos*/

DELIMITER $$
CREATE PROCEDURE upload_payment (idP VARCHAR(10),yearP VARCHAR(10),monthP VARCHAR(10))
BEGIN
    SELECT * FROM payments WHERE id=idP AND year=yearP AND month=monthP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE add_payment (yearP VARCHAR(10),monthP VARCHAR(10),
paymentP VARCHAR(10), dateP VARCHAR(10),id_relP VARCHAR(10))
BEGIN
    INSERT INTO payments VALUES(null,yearP,monthP,paymentP,dateP,id_relP);
END $$
DELIMITER ;

/* Procedimientos almacenados para la tabla de historial*/

DELIMITER $$
CREATE PROCEDURE add_history (descriptionP VARCHAR(20), dateP VARCHAR(10), paymentP VARCHAR(10), id_relP VARCHAR(10))
BEGIN
    INSERT INTO history VALUES(null,descriptionP,dateP,paymentP,id_relP); 
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE edit_history (idP VARCHAR(10), descriptionP VARCHAR(30), dateP VARCHAR(15), paymentP VARCHAR(10))
BEGIN
    UPDATE history SET description=descriptionP,payment_date=dateP,payment=paymentP WHERE id=idP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE delete_history (idP VARCHAR(10))
BEGIN
    DELETE FROM history WHERE id=idP;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE consult_history (idP VARCHAR(10))
BEGIN
    SELECT * FROM history WHERE id_rel=idP ORDER BY id DESC;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE upload_history (idP VARCHAR(10))
BEGIN
    SELECT * FROM datos_historial WHERE id=idP;
END $$
DELIMITER ;


/* crear un usuario */
INSERT INTO login VALUES (null,'admin','');