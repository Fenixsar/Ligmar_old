/**
 * Created by root on 1/27/14.
 */
$(document).ready(function () {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function (e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                switch (e.srcElement.id) {
                    case "exampleInputText1":
                        e.target.setCustomValidity("Введите логин");
                        break;
                    case "exampleInputPassword1":
                        e.target.setCustomValidity("Введите пароль");
                        break;
                    case "exampleInputPassword2":
                        e.target.setCustomValidity("Введите пароль еще раз");
                        break;
                    case "exampleInputEmail11":
                        e.target.setCustomValidity("Введите email");
                        break;
                }
            }
        };
        elements[i].oninput = function (e) {
            e.target.setCustomValidity("");
        };
    }
    $('#regform').on('submit',function(){
        if($('#exampleInputText1').val().length< 4){
            $('#exampleInputText1').next().html('<p style="color: red; text-align: center">Логин должен быть не менее 4 символов</p>');
            return false;
        }
        else {
            $('#exampleInputText1').next().html('<p style="text-align: center">Используется для входа в игру</p>');
        }
        if($('#exampleInputPassword1').val().length< 6){
            $('#exampleInputPassword1').next().html('<p style="color: red; text-align: center">Пароль должен содержать не менее 6 символов</p>');
            return false;
        }
        else{
            $('#exampleInputPassword1').next().html('<p style="text-align: center">Пароль должен содержать не менее 6 символов</p>');
        }
        if($('#exampleInputPassword1').val()!=$('#exampleInputPassword2').val()){
            $('#exampleInputPassword2').next().html('<p style="color: red; text-align: center">Пароли не совпадают!</p>');
            return false;
        }
        return true;
    });

    $('#authform').on('submit',function(){
        if($('#exampleInputText1').val().length< 4){
            $('#exampleInputText1').next().html('<p style="color: red; text-align: center">Логин должен быть не менее 4 символов</p>');
            return false;
        }
        else {
            $('#exampleInputText1').next().html('<p style="text-align: center">Используется для входа в игру</p>');
        }
        if($('#exampleInputPassword1').val().length< 6){
            $('#exampleInputPassword1').next().html('<p style="color: red; text-align: center">Пароль должен содержать не менее 6 символов</p>');
            return false;
        }
        else{
            $('#exampleInputPassword1').next().html('<p style="text-align: center">Пароль должен содержать не менее 6 символов</p>');
        }
        return true;
    });
});



