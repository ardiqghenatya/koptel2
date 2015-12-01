'use strict';

/**
 * @ngdoc overview
 * @name minovateApp
 * @description
 * # minovateApp
 *
 * Main module of the application.
 */
angular
  .module('minovateApp', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngTouch',
    'picardy.fontawesome',
    'ui.bootstrap',
    'ui.router',
    'ui.utils',
    'angular-loading-bar',
    'angular-momentjs',
    'lazyModel',
    'toastr',
    'smart-table',
    'oc.lazyLoad',
    'angular-flot',
    'easypiechart'
  ])
  .run(['$rootScope', '$state', '$stateParams', 'appAuth', function($rootScope, $state, $stateParams, appAuth) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    $rootScope.$on('$stateChangeSuccess', function(event, toState) {

      event.targetScope.$watch('$viewContentLoaded', function () {

        angular.element('html, body, #content').animate({ scrollTop: 0 }, 200);

        setTimeout(function () {
          angular.element('#wrap').css('visibility','visible');

          if (!angular.element('.dropdown').hasClass('open')) {
            angular.element('.dropdown').find('>ul').slideUp();
          }
        }, 200);
      });
      $rootScope.containerClass = toState.containerClass || 'hz-menu boxed-layout';
    });

    /**
     * RBAC
     */
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
        // console.info('You access state', toState, fromState.url );
        /**
         * Cegah akses ke halaman anon untuk user yang sudah login
         */
        if (toState.data && toState.data.accessLevel === AUTHConfig.accessLevels.anon && appAuth.authenticated === true) {
            angular.element('#pageloader').toggleClass('hide animate');
            return event.preventDefault();
        } else if (toState.data && !appAuth.authorize(toState.data.accessLevel || AUTHConfig.accessLevels.public)) {
            event.preventDefault();
            angular.element('#pageloader').toggleClass('hide animate');
            console.warn('Page Access Denied', toState, fromState.url);
            return $state.go(toState.data.loginState, {backTo: $state.href(toState.name, null, {absolute: true})});
        }
    });

  }])

  .config(['$stateProvider', '$urlRouterProvider',  function ($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/app/barcodeProcesses/index');

    $stateProvider
    .state('app', {
      abstract: true,
      url: '/app',
      templateUrl: 'views/app.html',
      data: {
        loginState: 'app.login'
      }
    })
    .state('app.login', {
      url: '/login?backTo=null',
      controller: 'LoginCtrl',
      templateUrl: 'views/login.html'
    });
  }]);

