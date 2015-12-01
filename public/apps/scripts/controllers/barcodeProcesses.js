'use strict';
/**
 * @ngdoc function
 * @name minovateApp.controller:BarcodeProcessesCtrl
 * @description
 * # BarcodeProcessesCtrl
 * Controller of the minovateApp
 */
angular.module('minovateApp').config(['$stateProvider', function($stateProvider) {
    $stateProvider.state('app.barcodeProcesses', {
        url: '/barcodeProcesses',
        abstract: true,
        controller: 'BarcodeProcessesCtrl',
        templateUrl: 'views/barcodeProcesses/main.html'
    }).state('app.barcodeProcesses.index', {
        url: '/index?page',
        views: {
            'page-content': {
                templateUrl: 'views/barcodeProcesses/index.html',
                controller: 'BarcodeProcessesIndexCtrl'
            }
        }
    }).state('app.barcodeProcesses.add', {
        url: '/add?barcode',
        views: {
            'page-content': {
                templateUrl: 'views/barcodeProcesses/form.html',
                controller: 'BarcodeProcessesAddCtrl'
            }
        },
        resolve: {
            shelves: ['storeShelves', '$q', function(storeShelves, $q) {
                var defer = $q.defer();
                storeShelves.query({
                    filter: '[{"field":"status", "operator":"=", "value":0}]'
                }).$promise.then(function(response) {
                    defer.resolve(response.data || []);
                }, function(response) {
                    defer.reject(response);
                });
                return defer.promise;
            }],
            barcodeProcessesDetail: [function() {
                return null;
            }]
        }
    }).state('app.barcodeProcesses.update', {
        url: '/update/:id',
        views: {
            'page-content': {
                templateUrl: 'views/barcodeProcesses/form.html',
                controller: 'BarcodeProcessesAddCtrl'
            }
        },
        resolve: {
            shelves: ['storeShelves', '$q', function(storeShelves, $q) {
                var defer = $q.defer();
                storeShelves.query().$promise.then(function(response) {
                    defer.resolve(response.data || []);
                }, function(response) {
                    defer.reject(response);
                });
                return defer.promise;
            }],
            barcodeProcessesDetail: ['$stateParams', '$q', 'storeBarcodeProcesses', 'appAuth', function($stateParams, $q, storeBarcodeProcesses, appAuth) {
                if ($stateParams.id) {
                    var defer = $q.defer();
                    storeBarcodeProcesses.get({
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
    }).state('app.statistic', {
        url: '/statistic',
        controller: 'StatisticCtrl',
        templateUrl: 'views/barcodeProcesses/statistic.html',
        resolve: {
            // plugins: ['$ocLazyLoad', function($ocLazyLoad) {
            //   return $ocLazyLoad.load([
            //     'scripts/vendor/datatables/datatables.bootstrap.min.css'
            //   ]);
            // }]
        }
    });
}]).factory('storeBarcodeProcesses', function($resource, appConfig) {
    return $resource(appConfig.api.get('baseUrl') + '/barcodeProcesses/:id', {
        id: '@id'
    }, {
        get: {
            method: 'GET',
            cache: false
        },
        query: {
            method: 'GET',
            isArray: false,
            params: {
                order: '[{"field":"created_at", "sort":"desc"}]'
            }
        },
        update: {
            method: 'PUT'
        },
        take: {
            url: appConfig.api.get('baseUrl') + '/barcodeProcesses/take/:id',
            method: 'POST'
        },
        statistic: {
            url: appConfig.api.get('baseUrl') + '/barcodeProcesses/statistic',
            method: 'GET'
        }
    });
}).controller('BarcodeProcessesCtrl', function($scope) {
    $scope.pageInformation = {
        title: 'Penyimpanan',
        route: 'barcodeProcesses',
        subtitle: ''
    };
}).controller('BarcodeProcessesIndexCtrl', function($scope, storeBarcodeProcesses, $state, $stateParams, appConfig, appAuth, appPopup, $filter, $http, $moment) {
    $scope.limit = 15, $scope.page = $stateParams.page || 1;
    $scope.data = [], $scope.message = {};
    $scope.getOffset = function() {
            return ($scope.page - 1) * $scope.limit;
        }
        // $scope.paging = function() {
        //     console.log($scope.page, $stateParams.page);
        //     $state.transitionTo('app.' + $scope.pageInformation.route + '.index', {
        //         page: $scope.page
        //     });
        // };
    $scope.query = '';
    $scope.getData = function(tableState) {
        angular.element('.tile').addClass('refreshing');
        var params = {
            access_token: appAuth.token,
            limit: $scope.limit,
            offset: $scope.getOffset()
        };
        // Filtering
        var filters = [];
        if ($scope.query) filters.push({
            field: $scope.selectedColoumn ? $scope.selectedColoumn.field : 'barcode',
            operator: $scope.selectedColoumn ? $scope.selectedColoumn.operator : '=',
            value: $scope.query
        });
        if ($scope.startDate && $scope.endDate) filters.push({
            field: 'barcode_processes.created_at',
            operator: 'between',
            value: [$moment($scope.startDate).format('YYYY-MM-DD 00:00'), $moment($scope.endDate).format('YYYY-MM-DD 23:59')]
        });
        if (filters.length) params.filter = JSON.stringify(filters);
        // Sorting
        if (tableState && tableState.sort.predicate) {
            params.order = JSON.stringify([{
                field: tableState.sort.predicate,
                sort: tableState.sort.reverse ? 'desc' : 'asc',
            }]);
        }
        storeBarcodeProcesses.query(params).$promise.then(function(response, status) {
            if (response.data) {
                $scope.data = response.data;
                $scope.message = response.meta;
            }
            angular.element('.tile.refreshing').removeClass('refreshing');
        }, function(response, status) {
            angular.element('.tile.refreshing').removeClass('refreshing');
            return appPopup.showResponseAPI(response, status);
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
                url: appConfig.api.get('baseUrl') + '/barcodeProcesses/' + item.id,
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
        // $scope.bulkActions = [{
        //     name: 'Delete All',
        //     fn: function() {
        //         var data = $filter('filter')($scope.data, function(value, key) {
        //             return value.selected === true;
        //         });
        //         if (data.length) {
        //             var deleteRecrusive = function(items) {
        //                 if (!items || !items.length) return;
        //                 var item = items[0];
        //                 $scope.deleteRecord(item).success(function(response, status) {
        //                     if (status === 200) {
        //                         items.splice(0, 1); // remove item yang sudah dihapus
        //                         deleteRecrusive(items);
        //                     }
        //                 });
        //             }
        //             return deleteRecrusive(data);
        //         }
        //         return appPopup.toast('No item selected', 'Warning', {
        //             iconType: 'fa-warning',
        //             iconClass: 'bg-warning'
        //         });
        //     }
        // }];
    $scope.$watch('query', function(newVal, oldVal) {
        if (!angular.equals(newVal, oldVal)) {
            $scope.getData();
        }
    });
    // $scope.getData();
    // Untuk mengambil passport
    $scope.take = function(item) {
            angular.element('.tile').addClass('refreshing');
            storeBarcodeProcesses.take({
                id: item.id
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                $scope.getData();
                return appPopup.showResponseAPI(response, status);
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                return appPopup.showResponseAPI(response, status);
            });
        }
        /**
         * Filter
         */
    $scope.columns = [{
        name: 'Barcode',
        field: 'barcode',
        operator: '='
    }, {
        name: 'Lemari',
        field: 'shelves.code',
        operator: '='
    }];
    $scope.selectedColoumn = $scope.columns[0];
    $scope.selecteColoumn = function(column) {
            $scope.selectedColoumn = column;
            if ($scope.query) {
                $scope.getData();
            }
        }
        /**
         * Filter Tanggal
         */
    $scope.startDate = $moment();
    $scope.endDate = $moment();
    $scope.rangeOptions = {
        ranges: {
            Today: [$moment(), $moment()],
            Yesterday: [$moment().subtract(1, 'days'), $moment().subtract(1, 'days')],
            'Last 7 Days': [$moment().subtract(6, 'days'), $moment()],
            'Last 30 Days': [$moment().subtract(29, 'days'), $moment()],
            'This Month': [$moment().startOf('month'), $moment().endOf('month')],
            'Last Month': [$moment().subtract(1, 'month').startOf('month'), $moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        startDate: $moment(),
        endDate: $moment(),
        parentEl: '#dateContainer'
    };
    $scope.$watch(function() {
        return [$scope.startDate, $scope.endDate].toString();
    }, function(newData, oldData) {
        if (!angular.equals(newData, oldData)) {
            $scope.getData();
        }
    });
    $scope.getData();
}).controller('BarcodeProcessesAddCtrl', function($scope, appConfig, $q, appAuth, $state, $stateParams, storeBarcodeProcesses, barcodeProcessesDetail, appPopup, shelves) {
    $scope.minDate = new Date();
    $scope.openDatepicker = function(key) {
        $scope.opened[key] = !$scope.opened[key];
    }
    $scope.shelves = shelves;
    var isContinueAdd = false;
    $scope.data = barcodeProcessesDetail && barcodeProcessesDetail.data ? barcodeProcessesDetail.data : {
        came_date: new Date(),
        exit_date: new Date()
    };
    if (!barcodeProcessesDetail) {
        $scope.data.barcode = $stateParams.barcode;
    }
    if ($scope.shelves && $scope.shelves.length) {
        $scope.data.shelf_id = $scope.shelves[0].id;
    }
    $scope.save = function(isContinue) {
        isContinueAdd = !!isContinue;
        angular.element('.tile').addClass('refreshing');
        if (!$scope.data.id) {
            storeBarcodeProcesses.save({
                access_token: appAuth.token,
                barcode: $scope.data.barcode,
                shelf_id: $scope.data.shelf_id,
                status: $scope.data.status,
                description: $scope.data.description,
                came_date: $scope.data.came_date,
                exit_date: $scope.data.exit_date
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                if (response.code && response.code >= 400 && response.code <= 1001) return appPopup.showResponseAPI(response, status);
                if (isContinueAdd) {
                    $scope.shelves = $scope.shelves.filter(function(shelf) {
                        return shelf.id != $scope.data.shelf_id
                    });
                    $scope.data = {};
                    $scope.form.$setPristine();
                } else {
                    $state.go('app.' + $scope.pageInformation.route + '.index')
                }
                return appPopup.showResponseAPI(response, status);
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                return appPopup.showResponseAPI(response, status);
            });
        } else {
            storeBarcodeProcesses.update({
                id: $scope.data.id
            }, {
                access_token: appAuth.token,
                barcode: $scope.data.barcode,
                shelf_id: $scope.data.shelf_id,
                description: $scope.data.description,
                status: $scope.data.status,
                came_date: $scope.data.came_date,
                exit_date: $scope.data.exit_date
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                if (response.code && response.code >= 400 && response.code <= 1001) return appPopup.showResponseAPI(response, status);
                $state.go('app.' + $scope.pageInformation.route + '.index');
                return appPopup.showResponseAPI(response, status);
            }, function(response, status) {
                angular.element('.tile.refreshing').removeClass('refreshing');
                return appPopup.showResponseAPI(response, status);
            });
        }
    };
}).controller('StatisticCtrl', function($scope, $http, $moment, storeBarcodeProcesses, appAuth, appPopup) {
    $scope.startDate = $moment();
    $scope.endDate = $moment();
    $scope.rangeOptions = {
        ranges: {
            Today: [$moment(), $moment()],
            Yesterday: [$moment().subtract(1, 'days'), $moment().subtract(1, 'days')],
            'Last 7 Days': [$moment().subtract(6, 'days'), $moment()],
            'Last 30 Days': [$moment().subtract(29, 'days'), $moment()],
            'This Month': [$moment().startOf('month'), $moment().endOf('month')],
            'Last Month': [$moment().subtract(1, 'month').startOf('month'), $moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        startDate: $moment(),
        endDate: $moment(),
        parentEl: '#content'
    };
    $scope.dataset = [{
        data: [
            [1, 15],
            [2, 40],
            [3, 35],
            [4, 39],
            [5, 42],
            [6, 50],
            [7, 46],
            [8, 49],
            [9, 59],
            [10, 60],
            [11, 58],
            [12, 74]
        ],
        label: 'Statistik',
        points: {
            show: true,
            radius: 4
        },
        splines: {
            show: true,
            tension: 0.45,
            lineWidth: 4,
            fill: 0
        }
    }, {
        data: [
            [1, 50],
            [2, 80],
            [3, 90],
            [4, 85],
            [5, 99],
            [6, 125],
            [7, 114],
            [8, 96],
            [9, 130],
            [10, 145],
            [11, 139],
            [12, 160]
        ],
        label: 'Statistik Penyimpanan',
        bars: {
            show: true,
            barWidth: 0.6,
            lineWidth: 0,
            fillColor: {
                colors: [{
                    opacity: 0.3
                }, {
                    opacity: 0.8
                }]
            }
        }
    }];
    $scope.options = {
        colors: ['#e05d6f', '#61c8b8'],
        series: {
            shadowSize: 0
        },
        legend: {
            backgroundOpacity: 0,
            margin: -7,
            position: 'ne',
            noColumns: 2
        },
        xaxis: {
            tickLength: 0,
            font: {
                color: '#fff'
            },
            position: 'bottom',
            ticks: [
                [1, 'JAN'],
                [2, 'FEB'],
                [3, 'MAR'],
                [4, 'APR'],
                [5, 'MAY'],
                [6, 'JUN'],
                [7, 'JUL'],
                [8, 'AUG'],
                [9, 'SEP'],
                [10, 'OCT'],
                [11, 'NOV'],
                [12, 'DEC']
            ]
        },
        yaxis: {
            tickLength: 0,
            font: {
                color: '#fff'
            }
        },
        grid: {
            borderWidth: {
                top: 0,
                right: 0,
                bottom: 1,
                left: 1
            },
            borderColor: 'rgba(255,255,255,.3)',
            margin: 0,
            minBorderMargin: 0,
            labelMargin: 20,
            hoverable: true,
            clickable: true,
            mouseActiveRadius: 6
        },
        tooltip: true,
        tooltipOpts: {
            content: '%s: %y',
            defaultTheme: false,
            shifts: {
                x: 0,
                y: 20
            }
        }
    };
    $scope.$watch(function() {
        return [$scope.startDate, $scope.endDate].toString();
    }, function(newData, oldData) {
        if (!angular.equals(newData, oldData)) {
            $scope.getData();
        }
    });
    $scope.getData = function(tableState) {
        angular.element('.tile').addClass('refreshing');
        var params = {
            access_token: appAuth.token,
            // limit: $scope.limit,
            // offset: $scope.getOffset()
        };
        // Filtering
        var filters = [];
        if ($scope.query) filters.push({
            field: $scope.selectedColoumn ? $scope.selectedColoumn.field : 'barcode',
            operator: $scope.selectedColoumn ? $scope.selectedColoumn.operator : '=',
            value: $scope.query
        });
        if ($scope.startDate && $scope.endDate) filters.push({
            field: 'barcode_processes.created_at',
            operator: 'between',
            value: [$moment($scope.startDate).format('YYYY-MM-DD 00:00'), $moment($scope.endDate).format('YYYY-MM-DD 23:59')]
        });
        if (filters.length) params.filter = JSON.stringify(filters);
        // Sorting
        if (tableState && tableState.sort.predicate) {
            params.order = JSON.stringify([{
                field: tableState.sort.predicate,
                sort: tableState.sort.reverse ? 'desc' : 'asc',
            }]);
        }
        storeBarcodeProcesses.statistic(params).$promise.then(function(response, status) {
            if (response.data) {
                $scope.dataset[0].data = $scope.dataset[1].data = response.data.map(function(data) {
                    return [data.shelf_id, data.total_transactions];
                });
                $scope.options.xaxis.ticks = response.data.map(function(data) {
                    return [data.shelf_id, data.shelf.code.toUpperCase()];
                });
                $scope.message = response.message;
            }
            angular.element('.tile.refreshing').removeClass('refreshing');
        }, function(response, status) {
            angular.element('.tile.refreshing').removeClass('refreshing');
            return appPopup.showResponseAPI(response, status);
        });
    };
    $scope.getData();
});
