/// <reference path="../../../Scripts/_app.ts" />
module Chart {
    export class ChartService extends App.HttpHandlerService {

        static $inject = ['$http'];

        constructor($http: ng.IHttpService) {
            //this.handlerUrl = 'chart1_demo_testdaten.txt';
            this.handlerUrl = '../../../energyData.txt';
            super($http);
        }

        getData(): ng.IPromise<any> {
            var config: any = {};
            return this.useGetHandler(config);
        }
    }
} 