<html>
<header>
    <title>Baptiste</title>
</header>


<style>

    div{
        text-align: center;
        font-size: 50px;
        color: orangered;
    }

</style>


<body ng-app="monApp">


<div ng-view></div>


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
<script src="https://code.angularjs.org/1.6.1/angular-route.min.js"></script>

<script>

    var monApp = angular.module('monApp',['ngRoute']);

    monApp.config(function($routeProvider){
       $routeProvider
            .when('/',{templateUrl: 'partials/home.html'})
            .when('/comments',{templateUrl:  'partials/comments.hmtl'})
            .otherwise({redirectTo : '/'});
    });

    monApp.controller('monController',function($scope){
        $scope.users = [{"name":"toto"},{"name":"titi"}];
    });

</script>

</body>
</html>
