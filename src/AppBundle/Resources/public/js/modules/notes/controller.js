'use strict';
(function () {
    var app = angular.module("app", ['ngRoute', 'ngResource']);

    app.controller("NoteController", function ($scope, $routeParams, $http, Notes) {
        $scope.form = false;
        $scope.note = {};

        $scope.getNotes = function () {
            Notes.query({model: $routeParams.model}).$promise.then(function (result) {
                $scope.notes = result;
            });
        };

        $scope.edit = function (note) {
            $scope.form = true;
            $scope.note = note;
        };

        $scope.create = function () {
            angular.extend($scope.note, {models: [$routeParams.model]});

            return Notes.create($scope.note).$promise.then(function (response) {
                //$scope.notes.push(response); TODO: order new notes list by priority

                $scope.getNotes();

                $scope.close();
            });
        };

        $scope.update = function () {
            return Notes.update({id: $scope.note.id}, {
                title: $scope.note.title,
                content: $scope.note.content,
                priority: $scope.note.priority,
                models: angular.isObject($scope.note.models) ? $scope.note.models.map(function (model) {
                    return model.id
                }) : []
            }).$promise.then(function () {
                $scope.close();
            });
        };

        $scope.remove = function (id) {
            Notes.unbuckle({id: id, action: 'model', model: $routeParams.model});

            $scope.getNotes();
        };

        $scope.submit = function (form) {
            $scope.submitted = true;

            if (form.$invalid) {
                return;
            }

            if (!$scope.note.hasOwnProperty('id')) {
                $scope.create();
            } else {
                $scope.update();
            }

            $scope.submitted = false;
        };

        $scope.close = function () {
            $scope.form = false;
            $scope.note = {};
        };

        $scope.getNotes();
    });

    app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
            .when('/notes/:model', {
                templateUrl: Routing.generate('notes'),
                controller: 'NoteController'
            }, null);
    }]);

    app.factory('Notes', function ($resource) {
        return $resource('/notes/:id/:action/:model', {}, {
            query: {method: 'GET', isArray: true, params: {model: '@model'}},
            create: {method: 'POST', params: {action: 'create'}},
            update: {method: 'PUT', params: {id: '@id'}},
            unbuckle: {method: 'DELETE', params: {id: '@id', action: '@action', model: '@model'}}
        })
    })
})();
