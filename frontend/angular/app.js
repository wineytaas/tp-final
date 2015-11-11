var app = angular.module('sistemaAcademico', []);



app.controller('loginController', ['$http',function($http) {
    // acoes e propriedades do meu controller
    this.userLog = {};
    this.login = function(user) {
        $http.get('http://localhost:8000/backend/'+user.tipo+'/'+user.login+'/'+user.senha).then(function(response){
            alert(response.result);
            userLog=response.data;
        }, function(){
            alert("Erro!");
        });
    };
}]);