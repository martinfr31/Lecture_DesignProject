/// <reference path="Scripts/_app.ts" />
module App {
    'use strict';
    angular.module('app', [])
    //Services
        .service('chartService', Chart.ChartService)

    //Directives
        .directive('chartwidget', Chart.FirstChartWidget.prototype.injection())

    //Controller
        .controller('ChartController', Chart.ChartController)
}