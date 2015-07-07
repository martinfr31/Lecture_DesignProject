/// <reference path="../../../Scripts/_app.ts" />
module Energy {
    export class EnergyService extends App.HttpHandlerService {

        static $inject = ['$http'];

        constructor($http: ng.IHttpService) {
            this.handlerUrl = 'http://cloud.livinglab-energy.de/ProSeminar/SchnittstelleLive.php';
            super($http);
        }

        getData(): ng.IPromise<any> {
            var config: any = {};
            return this.useGetHandler(config);
        }
    }
} 