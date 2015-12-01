'use strict';

/**
 * @ngdoc service
 * @name minovateApp.appCache
 * @description
 * # appCache
 * Modul untuk container Cache API
 */
angular.module('minovateApp')
  .filter('moment', function ($moment) {
    return function (input, fn) {
      if(!input) return input;
      return $moment(input).format(fn);
    };
  });