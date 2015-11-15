-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
-- Database: osudining, Server version	5.7.9-log
-- Date: 11/15/2015
-- ------------------------------------------------------

--
-- Data table for `tags` and `types`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'Coffee','coffee',1),(2,'Deli','deli',2),(3,'American','merica',3),(4,'Ice Cream/Desserts','dessert',4),(5,'Pizza/Pasta','pizza-pasta',5),(6,'Ethnic','ethnic',6),(7,'Convenience/Pharmacy','convenience',7),(8,'Other','other',8),(9,'Gluten Free','gf',9),(10,'Vegan','veg',10);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` VALUES (1,'BuckID','buckid',4),(2,'Dining Dollars','dining',3),(3,'Meal Exchange','exch',2),(4,'Traditions','trad',1);
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;