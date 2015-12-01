'use strict';

(function(exports) {
    var userRoles = {
        public : 1,
        user : 2,
        admin : 4,
    };
    exports.userRoles = userRoles;
    exports.accessLevels = {
        anon : userRoles.public,
        public : userRoles.public | userRoles.user | userRoles.admin,
        user : userRoles.user | userRoles.admin,
        admin : userRoles.admin,
    };
})( typeof exports === 'undefined' ? window.AUTHConfig = {} : exports);

/**
 * @ngdoc service
 * @name minovateApp.appAuth
 * @description
 * # appAuth
 * Factory in the minovateApp.
 */
angular.module('minovateApp')
  .factory('appAuth', function ($q, $store, $http, appConfig, appPopup) {

    function UserData() {
        this.authenticated = false;
        this.role = AUTHConfig.userRoles.public;
        // this.user = null;
        this.token = null;
        this.expires = null;
        // this.customer = null;
        this._sync();
    }

    UserData.prototype = {
        _default: function() {
            return {
                authenticated:false,
                role:AUTHConfig.userRoles.public,
                // user:null,
                // customer:null,
                token:null,
                expires:null
            };
        },

        _sync: function() {
            angular.forEach(this._default(), function(val, key) {
                // if (key==='user' || key==='customer') {
                if ((key==='user' || key==='customer') && $store.get(key)) {
                    this[key] = JSON.parse($store.get(key));
                } else if ($store.get(key)) {
                    this[key] = $store.get(key);
                }
            }, this);
        },

        _reset: function() {
            angular.forEach(this._default(), function(val, key) {
                $store.set(key, val);
                this[key] = val;
            }, this);
        },

        _isExpired: function() {
            var now = +new Date, expires = this.expires, expired = now >= this.expires;
            if(this.expires && expired)
                console.log('token expired', new Date(this.expires));
            return expired;
        },

        authorize: function(accessLevel, role) {
            /**
             * Default Role apabila $cookies.UserService belum ada
             */
            if (!angular.isDefined(role)) {
                role = this.role || AUTHConfig.userRoles.public;
            }

            /**
             * Apabila token expired, reset data
             */
            if (accessLevel != AUTHConfig.accessLevels.public && accessLevel != AUTHConfig.accessLevels.anon  && this._isExpired()) {
                this._reset();
                return false;
            }

            /**
             * Check Page Access Level vs User Role
             */
            if (angular.isNumber(accessLevel) && angular.isNumber(role)) {
                // console.log('authorize', accessLevel, role, accessLevel & role);
                return accessLevel & role;
            } else {
                return false;
            }
        },

        logout: function() {
            var me = this;
            /**
             * Logout via API /session/logout
             */
            // var http = $http.post(appConfig.api.get('baseUrl') +'/sessions/logout',{
            //     token : me.token,
            // });
            // http.success(function(response, status){
                me._reset();
            // });
            // return http;
            
            var http = $q.defer();
            setTimeout(function(){
                http.resolve();
            }, 500);
            return http.promise;
        },

        login: function(data, type) {
            var me = this;

            /**
             * Login via API /session/login
             */
            var http = $http.post(appConfig.api.get('baseUrl') +'/oauth/login',angular.extend(appConfig.api.get('auth'), {
                username : data.username,
                password : data.password,
            }));
            http.success(function(response, status){
                // console.log(response, status);
                /**
                 * Store athenticated user data
                 */
                var now = new Date();
                $store.set('authenticated', true);
                $store.set('role', AUTHConfig.userRoles.user);
                // $store.set('user', JSON.stringify(response.user));
                $store.set('token', response.access_token);
                $store.set('expires', now.setSeconds(response.expires_in - 60));
                // $store.set('customer', JSON.stringify(response.customer));
                me._sync();
            });
            return http;
        }
    };

    var userData = new UserData();
    return userData;

});
