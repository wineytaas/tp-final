var app = angular.module('sistemaAcademico', ['ngRoute', 'ngCookies']);

app.config(function($routeProvider) {
  
  $routeProvider.when("/", 
    {
      templateUrl: "home.view.html",
      controller: "homeController",
      controllerAs: "homeCtrl"
    }
  )
  .when("/login", 
    {
      templateUrl: "login.view.html",
      controller: "loginController",
      controllerAs: "lCtrl"
    }
  )
  .when("/noticia/:noticiaid", 
    {
      templateUrl: "noticia.view.html",
      controller: "noticiaController",
      controllerAs: "nCtrl"
    }
  )
  .when("/gerenciarnoticias", 
    {
      templateUrl: "gnoticias.view.html",
      controller: "gnoticiasController",
      controllerAs: "gnCtrl"
    }
  )
  .when("/noticias", 
    {
      templateUrl: "noticias.view.html",
      controller: "gnoticiasController",
      controllerAs: "gnCtrl"
    }
  )
  .when("/novanoticia", 
    {
      templateUrl: "novanoticia.view.html",
      controller: "novanoticiaController",
      controllerAs: "nCtrl"
    }
  )
  .when("/editarnoticia/:noticiaid", 
    {
      templateUrl: "editarnoticia.view.html",
      controller: "editarnoticiaController",
      controllerAs: "nCtrl"
    }
  )
  .when("/meusdados", 
    {
      templateUrl: "meusdados.view.html",
      controller: "meusdadosController",
      controllerAs: "nCtrl"
    }
  )
  .when("/editarmeusdados", 
    {
      templateUrl: "meusdadose.view.html",
      controller: "meusdadosController",
      controllerAs: "nCtrl"
    }
  )
  .when("/minhaturma", 
    {
      templateUrl: "minhaturma.view.html",
      controller: "minhaturmaController",
      controllerAs: "tCtrl"
    }
  )
  .when("/logout", 
    {
      redirectTo: "/login"
    }
  )
  .when("/Pagina-Invalida", 
    {
      templateUrl: "invalida.view.html",
      controller: "homeController",
      controllerAs: "hCtrl"
    }
  )
  .otherwise(
    {
      redirectTo: "/Pagina-Invalida"    
    }
  );
});


app.factory('AuthenticationService', ['$http', '$cookieStore', '$rootScope', '$location', function($http, $cookieStore, $rootScope, $location) {
    var service = {};
    service.menu=[];
    service.getMenu = getMenu;
    service.treatError = treatError;
    service.SetCredentials = SetCredentials;
    service.ClearCredentials = ClearCredentials;
    
    return service;
    
        function getMenu(){            
            $http.get('/backend/menu').then(function(response){
               service.menu = response.data.menu;
               if(response.data.error !== undefined) service.treatError(response.data);
            }, function(){
            });
        }
        
        function treatError(error){
            document.getElementById("response").innerHTML = "<p class='alert-danger alert'>"+error.description+"</p>";            
            if(error.error === 1){
                service.ClearCredentials();                   
                $location.path("/login");
            }
        }
   
        function SetCredentials(username, password) {
            var authdata = password;
 
            $rootScope.globals = {
                currentUser: {
                    username: username,
                    authdata: authdata
                }
            };
 
            $http.defaults.headers.common['Authorization'] = authdata; // jshint ignore:line
            $cookieStore.put('globals', $rootScope.globals);
        }
 
        function ClearCredentials() {
            $rootScope.globals = {};
            $cookieStore.remove('globals');
            $http.defaults.headers.common.Authorization = '';
        }
 
}]);

app.factory('GeneralService', ['$http', '$cookieStore', '$rootScope', '$location', 'AuthenticationService', function($http, $cookieStore, $rootScope, $location, AuthenticationService) {
    var service = {};
    service.noticias=[];
    service.getNoticias = getNoticias;
    service.getAllNoticias = getAllNoticias;
    
    return service;
    
        function getNoticias(){            
            $http.get('/backend/noticias').then(function(response){
               service.noticias = response.data.noticias;
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
            }, function(){
            });
        }
        
        function getAllNoticias(){            
            $http.get('/backend/noticiasa').then(function(response){
               service.noticias = response.data.noticias;
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
            }, function(){
            });
        }
        
        
}]);

app.controller('homeController',['$rootScope', '$location', '$http', 'AuthenticationService', 'GeneralService', function($rootScope, $location, $http, AuthenticationService, GeneralService){

        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        var self = this;
        
        (function initController() {
            AuthenticationService.getMenu();   
            GeneralService.getNoticias();
        })();
        
}]);

app.controller('gnoticiasController',['$rootScope', '$location', '$http', 'AuthenticationService', 'GeneralService', function($rootScope, $location, $http, AuthenticationService, GeneralService){

        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        var self = this;
        
        this.deletaNoticia = function(noticiaid){
            $http.delete('/backend/noticias/'+noticiaid).then(function(response){ 
                if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
                } else {
                    document.getElementById("response").innerHTML = "<p class='alert alert-success box'>Notícia deletada !</p>";
                    GeneralService.getAllNoticias();
                }
            }, function(){
                document.getElementById("response").innerHTML = "<p class='alert-danger alert'>Erro ao tentar conectar banco de dados</p>";            
            });
        };
        
        (function initController() {
            AuthenticationService.getMenu();   
            GeneralService.getAllNoticias();
        })();
        
}]);

app.controller('noticiaController',['$rootScope','$routeParams', '$location', '$http', 'AuthenticationService', 'GeneralService', function($rootScope, $routeParams, $location, $http, AuthenticationService, GeneralService){
        this.noticia;
        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        var self = this;
        
        this.getNoticia = function(){            
            $http.get('/backend/noticias/'+$routeParams.noticiaid).then(function(response){
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
               self.noticia = response.data.noticia;
            }, function(){
            });
        };        
        
        
        (function initController() {
            AuthenticationService.getMenu();   
            self.getNoticia();
        })();
        
}]);    

app.controller('minhaturmaController',['$rootScope','$routeParams', '$location', '$http', 'AuthenticationService', 'GeneralService', function($rootScope, $routeParams, $location, $http, AuthenticationService, GeneralService){
        this.turma;
        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        var self = this;
        
        this.getTurma = function(){            
            $http.get('/backend/alunos').then(function(response){
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
               self.turma = response.data;
            }, function(){
            });
        };        
        
        
        (function initController() {
            AuthenticationService.getMenu();   
            self.getTurma();
        })();
        
}]);


app.controller('novanoticiaController',['$rootScope','$routeParams', '$location', '$http', '$interval', 'AuthenticationService', 'GeneralService', function($rootScope, $routeParams, $location, $http, $interval, AuthenticationService, GeneralService){
        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        var self = this;
        
        
        this.enviaNoticia = function(noticia){
            $("#btEnviar").attr("disabled","disabled");
            $http.post('/backend/noticias',noticia).then(function(response){ 
                if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
                    $("#btEnviar").removeAttr("disabled");
                } else {

                    $("#btEnviar").text("Redirecionando...");
                    document.getElementById("response").innerHTML = "<p class='alert alert-success box'>Notícia cadastrada com sucesso !</p>";
             
                    $interval(function(){                    
                        $location.path("/gerenciarnoticias");
                    },2000,1);   
                }
            }, function(){
                document.getElementById("response").innerHTML = "<p class='alert-danger alert'>Erro ao tentar conectar banco de dados</p>";            
                $("#btEnviar").removeAttr("disabled");
            });
        };
        
        (function initController() {
            AuthenticationService.getMenu();  
        })();
        
}]);    

app.controller('editarnoticiaController',['$rootScope','$routeParams', '$location', '$http', '$interval', 'AuthenticationService', 'GeneralService', function($rootScope, $routeParams, $location, $http, $interval, AuthenticationService, GeneralService){
        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        this.noticia;
        var self = this;
        
        this.getNoticia = function(){            
            $http.get('/backend/noticias/'+$routeParams.noticiaid).then(function(response){
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
               self.noticia = response.data.noticia;
            }, function(){
            });
        }; 
        
        this.enviaNoticia = function(){
            $("#btEnviar").attr("disabled","disabled");
            $http.put('/backend/noticias/'+self.noticia.id,self.noticia).then(function(response){ 
                if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
                    $("#btEnviar").removeAttr("disabled");
                } else {

                    $("#btEnviar").text("Redirecionando...");
                    document.getElementById("response").innerHTML = "<p class='alert alert-success box'>Notícia editada com sucesso !</p>";
             
                    $interval(function(){                    
                        $location.path("/gerenciarnoticias");
                    },2000,1);   
                }
            }, function(){
                document.getElementById("response").innerHTML = "<p class='alert-danger alert'>Erro ao tentar conectar banco de dados</p>";            
                $("#btEnviar").removeAttr("disabled");
            });
        };
        
        (function initController() {
            AuthenticationService.getMenu();  
            self.getNoticia();
        })();
        
}]);    

app.controller('meusdadosController',['$rootScope','$routeParams', '$location', '$http', '$interval', 'AuthenticationService', 'GeneralService', function($rootScope, $routeParams, $location, $http, $interval, AuthenticationService, GeneralService){
        this.authS = AuthenticationService;
        this.generalS = GeneralService;
        this.aluno;
        var self = this;
        
        this.getAluno = function(){            
            $http.get('/backend/alunos/0').then(function(response){
               if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
               }
               self.aluno = response.data;
            }, function(){
            });
        }; 
        
        this.enviaAluno = function(){
            $("#btEnviar").attr("disabled","disabled");
            $http.put('/backend/alunos/0',self.aluno).then(function(response){ 
                if(response.data.error !== undefined) {
                   AuthenticationService.treatError(response.data);
                    $("#btEnviar").removeAttr("disabled");
                } else {

                    $("#btEnviar").text("Redirecionando...");
                    document.getElementById("response").innerHTML = "<p class='alert alert-success box'>Dados alterados !</p>";
             
                    $interval(function(){                    
                        $location.path("/meusdados");
                    },2000,1);   
                }
            }, function(){
                document.getElementById("response").innerHTML = "<p class='alert-danger alert'>Erro ao tentar conectar banco de dados</p>";            
                $("#btEnviar").removeAttr("disabled");
            });
        };
        
        (function initController() {
            AuthenticationService.getMenu();  
            self.getAluno();
        })();
        
}]); 
    
app.controller('loginController', ['$rootScope','$location','$http', '$interval', 'AuthenticationService',function($rootScope, $location, $http, $interval, AuthenticationService) {
    // acoes e propriedades do meu controller
    this.userLog = {};
    var self = this;
    self.logResp = {}; 
    (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();
    this.login = function(user) {

       $("#btLogin").attr("disabled","disabled");
        $http.post('/backend/login',user).then(function(response){
            if(response.data.result === true){                
                
                $("#btLogin").text("Redirecionando...");
                document.getElementById("response").innerHTML = "<p class='alert alert-success box'>Logado com sucesso como "+response.data.user.nome+"!</p>";
                AuthenticationService.SetCredentials(response.data.user.login, response.data.auth_key);
                
                $interval(function(){                    
                    $location.path("/");
                },2000,1);
                
  
            } else {
                document.getElementById("response").innerHTML = "<p class='alert alert-danger box'>Login ou senha incorretos!</p>";      
                $("#btLogin").removeAttr("disabled");
            }
            self.logResp=response.data;
        }, function(){
            document.getElementById("response").innerHTML = "<p class='alert-danger alert'>Erro ao tentar conectar banco de dados</p>";            
            $("#btLogin").removeAttr("disabled");
        });
    };
}]);

app.run(['$rootScope', '$location', '$cookieStore', '$http', function run($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }
 
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray($location.path(), ['/login']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn) {
                $location.path('/login');
            }
        });
    }]);

app.filter('cep', function () {
  return function (input) {
    var str = input + '';
    str = str.replace(/\D/g, '');
    str = str.replace(/^(\d{2})(\d{3})(\d)/, '$1.$2-$3');
    return str;
  };
});

app.filter('cnpj', function () {
  return function (input) {
    // regex créditos Matheus Biagini de Lima Dias
    var str = input + '';
    str = str.replace(/\D/g, '');
    str = str.replace(/^(\d{2})(\d)/, '$1.$2');
    str = str.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    str = str.replace(/\.(\d{3})(\d)/, '.$1/$2');
    str = str.replace(/(\d{4})(\d)/, '$1-$2');
    return str;
  };
});
// Source: dist/.temp/brasil/filters/cpf.js
app.filter('cpf', function () {
  return function (input) {
    var str = input + '';
    str = str.replace(/\D/g, '');
    str = str.replace(/(\d{3})(\d)/, '$1.$2');
    str = str.replace(/(\d{3})(\d)/, '$1.$2');
    str = str.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    return str;
  };
});
// Source: dist/.temp/brasil/filters/realbrasileiro.js
function formatReal(int) {
  var tmp = int + '';
  var res = tmp.replace('.', '');
  tmp = res.replace(',', '');
  var neg = false;
  if (tmp.indexOf('-') === 0) {
    neg = true;
    tmp = tmp.replace('-', '');
  }
  if (tmp.length === 1) {
    tmp = '0' + tmp;
  }
  tmp = tmp.replace(/([0-9]{2})$/g, ',$1');
  if (tmp.length > 6) {
    tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, '.$1,$2');
  }
  if (tmp.length > 9) {
    tmp = tmp.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, '.$1.$2,$3');
  }
  if (tmp.length > 12) {
    tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, '.$1.$2.$3,$4');
  }
  if (tmp.indexOf('.') === 0) {
    tmp = tmp.replace('.', '');
  }
  if (tmp.indexOf(',') === 0) {
    tmp = tmp.replace(',', '0,');
  }
  return neg ? '-' + tmp : tmp;
}
app.filter('realbrasileiro', function () {
  return function (input) {
    return 'R$ ' + formatReal(input);
  };
});
// Source: dist/.temp/brasil/filters/tel.js
app.filter('tel', function () {
  return function (input) {
    var str = input + '';
    str = str.replace(/\D/g, '');
    if (str.length === 11) {
      str = str.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    } else {
      str = str.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    }
    return str;
  };
});