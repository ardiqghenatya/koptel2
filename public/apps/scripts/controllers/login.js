'use strict';

/**
 * @ngdoc function
 * @name minovateApp.controller:LoginCtrl
 * @description
 * # LoginCtrl
 * Controller of the minovateApp
 */
angular.module('minovateApp')
  .controller('LoginCtrl', function ($scope, appConfig, appAuth, $state) {
    $scope.user = {
    	username: 'admin@web.co.id',
    	password: 'admin'
    };

    $scope.login = function() {
    	appAuth.login($scope.user).success(function() {
            if ($state.params.backTo && false) {
              var href = decodeURIComponent($state.params.backTo);
              location.href = href == '/' ? $state.params.backTo.substr(1) : href;
            } else {
                $state.go('app.barcodeProcesses.index');
            }
    	});
    };
  });
