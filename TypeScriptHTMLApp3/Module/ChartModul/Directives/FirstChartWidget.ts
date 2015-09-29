//Definition Widget Chart1
/// <reference path="../../../Scripts/_app.ts" />
module Chart {
    'use strict';

    export class FirstChartWidget implements ng.IDirective {
        public injection(): Array<any> {
            return [
                () => { return new FirstChartWidget() }
            ]
        }

        public templateUrl: string;
        public scope;

        public link;
        constructor() {
            this.templateUrl = "Module/ChartModul/Views/firstchartwidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
    }
}   
