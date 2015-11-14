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
      controllerAs: "loginCtrl"
    }
  )
  .when("/logout", 
    {
      redirectTo: "/login"
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

app.controller('homeController',['$rootScope', '$location', '$http', 'AuthenticationService', function($rootScope, $location, $http, AuthenticationService){

        this.authS = AuthenticationService;
        var self = this;
        
        (function initController() {
            AuthenticationService.getMenu();            
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
            document.getElementById("response").innerHTML = "<p class='bg-danger box'>Erro ao tentar conectar banco de dados</p>";            
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