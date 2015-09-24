/// <reference path="../../../Scripts/_app.ts" />
module Chart {
    'use strict';

    export class ThirdChartWidget implements ng.IDirective {
        public injection(): Array<any> {
            return [
                () => { return new ThirdChartWidget() }
            ]
        }

        public templateUrl: string;
        public scope;

        public link;
        constructor() {
            this.templateUrl = "Module/ChartModul/Views/thirdchartwidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
    }
}   