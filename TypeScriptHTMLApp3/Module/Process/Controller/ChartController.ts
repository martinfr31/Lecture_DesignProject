 /// <reference path="../../../Scripts/_app.ts" />
module Chart {
    'use strict';
    export interface IChartCtrlScope extends ng.IScope {
        ChartData: IChartModel;
    }

    export class ChartController {
        scope: IChartCtrlScope;

        static $inject = ['$scope', 'ChartService'];

        constructor(private $scope: IChartCtrlScope, serviceChart: ChartService) {
            console.log("Chart Constructor started");
            serviceChart.getData().then((data) => $scope.ChartData = data);
            setInterval(function () {
                $scope.$apply(function () {
                    serviceChart.getData().then((data) => $scope.ChartData = data);
                });
            }, 1000); 
        }
    }
}