//Подключаем Express
var express = require('express');
var app = express();
var cookieParser = require('cookie-parser');
app.use(cookieParser());


app.get('/', function (req, res) {
    res.render('../../jades/login_page.jade');
	//Проверка на логин пользователя

});

var server = app.listen(2000, function () {
    var host = server.address().address;
    var port = server.address().port;
    console.log('Example app listening at http://%s:%s', host, port)
});

// app.use(express.static(__dirname));	

app.get('*', function(req, res){
    //Записать в логи
    res.send('Я тебя запомнил.', 404);
    console.log('Какой то хуй ломится.');
});