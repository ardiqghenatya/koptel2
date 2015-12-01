'use strict';

/**
 * @ngdoc service
 * @name minovateApp.appCache
 * @description
 * # appCache
 * Modul untuk container Cache API
 */
angular.module('minovateApp')
  .factory('appCache', function ($cacheFactory) {
    return $cacheFactory('appCache');
  });
