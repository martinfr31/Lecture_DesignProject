//Controller erstellt das Diagramm auf Basis der Widgets, Daten und views.
/// <reference path="../../../Scripts/_app.ts" />
module Chart {
    'use strict';
    export interface IChartCtrlScope extends ng.IScope {
        chartData: IChartModel;
    }

    export class ChartController {
        scope: IChartCtrlScope;

        static $inject = ['$scope', 'chartService'];

        constructor(private $scope: IChartCtrlScope, serviceChart: ChartService) {
            console.log("Chart Constructor started");
            serviceChart.getData().then((data) => $scope.chartData = data);
            setInterval(function () {
                $scope.$apply(function () {
                    serviceChart.getData().then((data) => $scope.chartData = data);
                });
            }, 1000); 
        }
    }
}