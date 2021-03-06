INSERT INTO 
	`customers` 
VALUES 
	(1,'Ana Paula','05309369929','ana@email.com','2019-11-09 15:43:56','2019-11-09 15:43:56'),
	(2,'Fernando Silva','18616224149','fernando@email.com','2019-11-09 15:44:48','2019-11-09 15:44:48'),
	(3,'Maria Souza','51090606060','m.souza@email.com','2019-11-09 15:46:18','2019-11-09 15:46:18');
	
INSERT INTO 
	`products` 
VALUES 
	(1,8552515751438646,'Calça jeans',12,'2019-11-09 15:41:56','2019-11-09 15:41:56'),
	(2,8552515751438647,'Calça jeans sarja',70,'2019-11-09 15:42:24','2019-11-09 15:42:24'),
	(3,8552515751438648,'Moleton  Masculino',159.9,'2019-11-09 15:42:53','2019-11-09 15:42:53'),
	(4,8552515751438649,'Moleton  Feminino',120.55,'2019-11-09 15:43:31','2019-11-09 15:43:31');

INSERT INTO 
	`orders` 
VALUES 
	(1,1,82,'ACTIVATED','2019-11-09 15:51:50'),
	(2,3,171.9,'ACTIVATED','2019-11-09 12:20:59'),
	(3,1,70,'ACTIVATED','2019-11-10 17:23:13'),
	(4,1,159.9,'ACTIVATED','2019-11-10 17:32:08'),
	(5,2,401,'ACTIVATED','2019-11-10 17:33:51');	
	
INSERT INTO 
	`order_items` 
VALUES 
	(1,1,1,1,12,12),
	(2,1,2,1,70,70),
	(3,2,1,1,12,12),
	(4,2,3,1,159.9,159.9),
	(5,3,2,1,70,70),
	(6,4,3,1,159.9,159.9),
	(7,5,3,1,159.9,159.9),
	(8,5,4,2,120.55,241.1);