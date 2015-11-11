var app = angular.module('sistemaAcademico', []);



app.controller('loginController', ['$http',function($http) {
    // acoes e propriedades do meu controller
    this.userLog = {};
    var self = this;
    self.logResp = {}; 
    this.login = function(user) {
        /*
        var jsonObject = {'nome': 'Teste', 'login': 'Teste', 'senha': 'Teste', 'logradouro': 'Teste', 'numero': '100','bairro': 'Teste','cep': '102030','cidade': 'Teste','complemento': 'Teste','salario': '1000'};
        $http.post('http://localhost:8000/backend/professores', jsonObject).then(function(response){
            alert("funfou");
        },function(){
            alert("nao funfou");
        });
        */
        $http.get('http://localhost:8000/backend/'+user.tipo+'/'+user.login+'/'+user.senha).then(function(response){
            if(response.data.result === true){                
                document.getElementById("response").innerHTML = "<p class='bg-success'>Logado com sucesso como "+response.data.user.nome+"</p>";
            } else document.getElementById("response").innerHTML = "<p class='bg-danger'>Login ou senha incorretos!</p>";
            self.logResp=response.data;
        }, function(){
            document.getElementById("response").innerHTML = "<p class='bg-danger'>Erro ao tentar conectar banco de dados</p>";
        });
    };
}]);