'use strict';

/**
 * @ngdoc service
 * @name minovateApp.appConfig
 * @description
 * # appConfig
 * Constant in the minovateApp.
 */
angular.module('minovateApp')

  .provider('appConfig', function () {

    this.$get = function ($q, $http) {
      return {

        veritrans: {
          tokenUrl: 'https://api.sandbox.veritrans.co.id/v2/token',
          clientKey: 'VT-client-OWvqqsqilRJOJZL6'
        },

        gplus: {
          clientId: '58117686305-ock3t5sltmb7hgbs66lpn8bq7j4eg39k.apps.googleusercontent.com' 
        },

        api: {

          get env() {
            if (angular.isDefined(window.env) && angular.isObject(window.env)) {
              return window.env;
            } else {
              return false;
            };
          },

          'default': {
            baseUrl: '../index.php/api/v1',
            auth: {
              client_id: 1,
              grant_type: 'password',
              client_secret: 'mIeCPklWs4MbbzmA2Mel'
            },
            pageSize: 15
          },
          get: function(key) {
            var config = {};
            angular.extend(config, this['default']);

            if(this.env) {
               angular.extend(config, this.env);
            }

            if (key) 
              return config[key];
          }
        }
      };
    };

  });
