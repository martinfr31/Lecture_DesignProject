/// <reference path="../../../Scripts/_app.ts" />
module Chartx {
    'use strict';

    export class FirstEnergyWidget implements ng.IDirective {
        public injection(): Array<any> {
            return [
                () => { return new FirstEnergyWidget() }
            ]
        }

        public templateUrl: string;
        public scope;

        public link;
        constructor() {
            this.templateUrl = "Module/ChartModul/Views/firstenergywidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
    }
}   