var app = angular.module('sistemaAcademico', []);



app.controller('loginController', ['$http',function($http) {
    // acoes e propriedades do meu controller
    this.userLog = {};
    this.login = function(user) {
        $http.get('http://150.164.237.223:8000/backend/alunos/4'+user.tipo+'/'+user.login+'/'+user.senha).then(function(response){
            alert("ok");
            userLog=response.data;
        }, function(){
            alert("Erro!");
        });
    };
}]);