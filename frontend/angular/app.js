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
            GeneralService.getNoticias();
            self.getNoticia();
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
                },2000);
                
  
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