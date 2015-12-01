'use strict';

/**
 * @ngdoc function
 * @name minovateApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the minovateApp
 */
angular.module('minovateApp')
  .controller('MainCtrl', function ($scope, $http, $window, appAuth, $state, $location) {

    $scope.main = {
      title: 'Minovate',
      settings: {
        navbarHeaderColor: 'scheme-black',
        sidebarColor: 'scheme-black',
        brandingColor: 'scheme-black',
        activeColor: 'black-scheme-color',
        headerFixed: false,
        asideFixed: false,
        rightbarShow: false
      }
    };

    $scope.ajaxFaker = function(){
      $scope.data=[];
      var url = 'http://www.filltext.com/?rows=10&fname={firstName}&lname={lastName}&delay=5&callback=JSON_CALLBACK';

      $http.jsonp(url).success(function(data){
        $scope.data=data;
        console.log('cecky');
        angular.element('.tile.refreshing').removeClass('refreshing');
      });
    };

    $scope.logout = function() {
      appAuth.logout().then(function(){
        $state.go('app.login');
      });
    }

    $scope.historyBack = function() {
      $window.history.back();
    }

    $scope.appAuth = appAuth;
    $scope.$location = $location;

    console.log($location);

  });