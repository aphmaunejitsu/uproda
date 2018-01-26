CREATE DATABASE IF NOT EXISTS `uproda` DEFAULT CHARACTER SET utf8mb4;
USE `uproda`
CREATE USER 'updater'@'%' IDENTIFIED BY '0p;/.Lo9';
GRANT SELECT,UPDATE,INSERT,DELETE ON uproda.* TO 'updater'@'%';
CREATE USER 'reader'@'%' IDENTIFIED BY '9Ol.,ki8';
GRANT SELECT ON uproda.* TO 'reader'@'%';
CREATE USER 'maintenance'@'%' IDENTIFIED BY '8ik,.lO9';
GRANT ALL ON uproda.* TO 'maintenance'@'%';
