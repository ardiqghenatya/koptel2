'use strict';
/**
 * @ngdoc function
 * @name minovateApp.controller:ShelvesCtrl
 * @description
 * # ShelvesCtrl
 * Controller of the minovateApp
 */
angular.module('minovateApp').config(['$stateProvider', function($stateProvider) {
    $stateProvider
        .state('app.shelves', {
            url: '/shelves',
            abstract: true,
            controller: 'ShelvesCtrl',
            templateUrl: 'views/shelves/main.html',
            data: {
                accessLevel: AUTHConfig.accessLevels.user
            }
        }).state('app.shelves.index', {
            url: '/index?page',
            views: {
                'page-content': {
                    templateUrl: 'views/shelves/index.html',
                    controller: 'ShelvesIndexCtrl'
                }
            }
        }).state('app.shelves.add', {
            url: '/add?backTo=null',
            views: {
                'page-content': {
                    templateUrl: 'views/shelves/form.html',
                    controller: 'ShelvesAddCtrl'
                }
            },
            resolve: {
                shelvesDetail: [function() {
                    return null;
                }]
            }
        }).state('app.shelves.update', {
            url: '/update/:id',
            views: {
                'page-content': {
                    templateUrl: 'views/shelves/form.html',
                    controller: 'ShelvesAddCtrl'
                }
            },
            resolve: {
                shelvesDetail: ['$stateParams', '$q', 'storeShelves', 'appAuth', function($stateParams, $q, storeShelves, appAuth) {
                    if ($stateParams.id) {
                        var defer = $q.defer();
                        storeShelves.get({
                            id: $stateParams.id,
                            access_token: appAuth.token
                        }, function(a) {
                            defer.resolve(a);
                        });
                        return defer.promise;
                    } else {
                        return null;
                    }
                }]
            }
        });
}]).factory('storeShelves', function($resource, appConfig) {
    return $resource(appConfig.api.get('baseUrl') + '/shelves/:id', {
        id: '@id'
    }, {
        get: {
            method: 'GET',
            cache: false
        },
        query: {
            method: 'GET',
            isArray: false
        },
        update: {
            method: 'PUT'
        }
    });
}).controller('ShelvesCtrl', function($scope) {
    $scope.pageInformation = {
        title: 'Lemari',
        route: 'shelves',
        subtitle: ''
    };
}).controller('ShelvesIndexCtrl', function($scope, storeShelves, $state, $stateParams, appConfig, appAuth, appPopup, $filter, $http) {
    $scope.limit = 15, $scope.page = $stateParams.page || 1;
    $scope.data = [], $scope.message = {};
    $scope.getOffset = function() {
        return ($scope.page - 1) * $scope.limit;
    }
    $scope.paging = function() {
        console.log($scope.page, $stateParams.page);
        $state.transitionTo('app.' + $scope.pageInformation.route + '.index', {
            page: $scope.page
        });
    };
    $scope.query = '';
    $scope.getData = function(tableState) {
        angular.element('.tile').addClass('refreshing');
        var params = {
            access_token: appAuth.token,
            limit: $scope.limit,
            offset: $scope.getOffset()
        };
        // Filtering
        if ($scope.query) params.filter = JSON.stringify([{
            "field": "code",
            "operator": "like",
            "value": $scope.query
        }]);
        // Sorting
        if (tableState && tableState.sort.predicate) {
            params.order = JSON.stringify([{
                field: tableState.sort.predicate,
                sort: tableState.sort.reverse ? 'desc' : 'asc',
            }]);
        }
        storeShelves.query(params).$promise.then(function(response, status) {
            if (response.data) {
                $scope.data = response.data;
                $scope.message = response.meta;
            }
            angular.element('.tile.refreshing').removeClass('refreshing');
        }, function(response, status) {
            angular.element('.tile.refreshing').removeClass('refreshing');
            appPopup.showResponseAPI(response, status);
        });
    };
    $scope.selectAll = function(status) {
        angular.forEach($scope.data, function(value, key) {
            value.selected = !!status;
        });
    }
    $scope.deleteRecord = function(item) {
        angular.element('.tile').addClass('refreshing');
        // DELETE MENGGUNAKAN $http, karena $resource tidak mau mengirim body
        var config = {
            method: 'DELETE',
            url: appConfig.api.get('baseUrl') + '/shelves/' + item.id,
            data: {
                access_token: appAuth.token
            }
        };
        return $http(config).success(function(response, status) {
            angular.element('.tile.refreshing').removeClass('refreshing');
            $scope.getData();
            return appPopup.showResponseAPI(response, status);
        }).error(function(response, status) {
            angular.element('.tile.refreshing').removeClass('refreshing');
            return appPopup.showResponseAPI(response, status);
        });
    }
    $scope.bulkActions = [{
        name: 'Delete All',
        fn: function() {
            var data = $filter('filter')($scope.data, function(value, key) {
                return value.selected === true;
            });
            if (data.length) {
                var deleteRecrusive = function (items) {
                    if (!items || !items.length) return;
                    var item = items[0];
                    $scope.deleteRecord(item).success(function(response, status) {
                        if (status === 200) {
                            items.splice(0, 1); // remove item yang sudah dihapus
                            deleteRecrusive(items);
                        }
                    });
                }
                return deleteRecrusive(data);
            }
            return appPopup.toast('No item selected', 'Warning', {
                iconType: 'fa-warning',
                iconClass: 'bg-warning'
            });
        }
    }];
    $scope.$watch('query', function(newVal, oldVal) {
        if (!angular.equals(newVal, oldVal)) {
            $scope.getData();
        }
    });
    $scope.getData();
}).controller('ShelvesAddCtrl', function($scope, appConfig, $q, appAuth, $state, $stateParams, storeShelves, shelvesDetail, appPopup) {
    var isContinueAdd = false;
    $scope.data = shelvesDetail && shelvesDetail.data ? shelvesDetail.data : {};
    $scope.save = function(isContinue) {
        isContinueAdd = !!isContinue;
        angular.element('.tile').addClass('refreshing');
        if (!$scope.data.id) {
            storeShelves.save({
                access_token: appAuth.token,
                code: $scope.data.code,
                description: $scope.data.description,
                status: $scope.data.status
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                if(response.code && response.code >= 400 && response.code <= 1001 )  return appPopup.showResponseAPI(response, status);
                if (isContinueAdd) {
                    $scope.data = {};
                    $scope.form.$setPristine();
                } else {
                    if($state.params.backTo) {
                        $state.go($state.params.backTo)
                    } else {
                        $state.go('app.' + $scope.pageInformation.route + '.index')
                    }
                }
                return appPopup.showResponseAPI(response, status);
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                return appPopup.showResponseAPI(response, status);
            });
        } else {
            storeShelves.update({
                id: $scope.data.id
            }, {
                access_token: appAuth.token,
                code: $scope.data.code,
                description: $scope.data.description,
                status: $scope.data.status
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                if(response.code && response.code >= 400 && response.code <= 1001 )  return appPopup.showResponseAPI(response, status);
                $state.go('app.' + $scope.pageInformation.route + '.index');
                return appPopup.showResponseAPI(response, status);
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                return appPopup.showResponseAPI(response, status);
            });
        }
    };
});
