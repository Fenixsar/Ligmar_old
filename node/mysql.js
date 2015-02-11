//Подключение MySQL
var mysql = require('mysql-utilities/node_modules/mysql');
mysqlUtilities = require('mysql-utilities');

//Настройки конфигурации mysql
var connection = mysql.createConnection({
    host:   'localhost',
    user:   'root',
    password:   '53Hpontar',
    database:   'game'
});
connection.connect();
mysqlUtilities.upgrade(connection);
mysqlUtilities.introspection(connection);

exports.c = connection;
