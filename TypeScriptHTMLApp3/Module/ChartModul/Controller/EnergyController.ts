/// <reference path="../../../Scripts/_app.ts" />
module Chartx {
    'use strict';
    export interface IEnergyCtrlScope extends ng.IScope {
        energyData: IEnergyModel;
    }

    export class ChartController {
        scope: IEnergyCtrlScope;

        static $inject = ['$scope', 'energyService'];

        constructor(private $scope: IEnergyCtrlScope, serviceEnergy: EnergyService) {
            console.log("Energy Constructor started");
            serviceEnergy.getData().then((data) => $scope.energyData = data);
            setInterval(function () {
                $scope.$apply(function () {
                    serviceEnergy.getData().then((data) => $scope.energyData = data);
                });
            }, 1000);
        }
    }
}