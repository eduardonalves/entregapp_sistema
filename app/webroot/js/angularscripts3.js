var app = angular.module('myApp', []);
app.controller('customersCtrl', function($scope, $http) {
    $http.get("http://localhost/angular/json.php")
    .success(function(response) {$scope.names = response; console.log(response);});
});