'use strict';

var engine = angular
        .module('engine', ['ngRoute', 'engine.controllers'])
        .config(['$routeProvider',
            function($routeProvider) {
                $routeProvider.
                when('/', {
                    templateUrl: '/templates/default/admin/panel.html',
                    controller: 'AdminPanelCtrl'
                }).
                when('/admin', {
                    templateUrl: '/templates/default/admin/layout.html',
                    controller: 'AdminMainCtrl'
                }).
                otherwise({
                    redirectTo: '/'
                });
            }
        ]);

angular.module('engine.controllers', []);

engine.controller('AdminPanelCtrl', ['$scope',
    function ($scope) {
        $scope.tools = [{
            name: 'Admin tools',
            styleClass: 'admin-tools'
        }];
    }
]);

engine.controller('AdminMainCtrl', ['$scope',
    function ($scope) {
        $scope.title = 'Administration';
    }
]);
