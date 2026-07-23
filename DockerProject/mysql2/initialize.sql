CREATE USER IF NOT EXISTS 'data_user'@'localhost' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'localhost';

CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'%';

DROP DATABASE IF EXISTS test_db;
CREATE DATABASE IF NOT EXISTS test_db;

use test_db;

DROP TABLE IF EXISTS test_table;
CREATE TABLE IF NOT EXISTS test_table (
    id INT PRIMARY KEY,
    name VARCHAR(255)
);

insert into test_table (id, name) values (1, 'あああ');
