/// <reference path="Scripts/_app.ts" />
module App {
    'use strict';
    angular.module('app', [])
    //Services
        .service('energyService', Energy.EnergyService)

    //Directives
        .directive('energywidget', Energy.FirstEnergyWidget.prototype.injection())

    //Controller
        .controller('EnergyController', Energy.EnergyController)
}