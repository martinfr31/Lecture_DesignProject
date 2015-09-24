/// <reference path="Scripts/_app.ts" />
module App {
    'use strict';
    angular.module('app', [])
    //Services
        .service('chartService', Chart.ChartService)

    //Directives
        .directive('chartwidget1', Chart.FirstChartWidget.prototype.injection())
        .directive('chartwidget2', Chart.SecondChartWidget.prototype.injection())
        .directive('chartwidget3', Chart.ThirdChartWidget.prototype.injection())

    //Controller
        .controller('ChartController', Chart.ChartController)
}