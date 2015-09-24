/// <reference path="../../../Scripts/_app.ts" />
module Chart {
    'use strict';

    export class SecondChartWidget implements ng.IDirective {
        public injection(): Array<any> {
            return [
                () => { return new SecondChartWidget() }
            ]
        }

        public templateUrl: string;
        public scope;

        public link;
        constructor() {
            this.templateUrl = "Module/ChartModul/Views/secondchartwidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
    }
}   