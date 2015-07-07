/// <reference path="../../Scripts/_app.ts" />
module App {
    export class Service {

    }
    export class HttpHandlerService extends Service {
        httpService: ng.IHttpService;
        handlerUrl: string;

        constructor($http: ng.IHttpService) {
            super();
            this.httpService = $http;
        }

        useGetHandler(params: any): ng.IPromise<any> {
            var result: ng.IPromise<any> = this.httpService.get(this.handlerUrl, params)
                .then((response: any): ng.IPromise<any> => this.handlerResponded(response, params));
            return result;
        }

        usePostHandler(params: any): ng.IPromise<any> {
            var result: ng.IPromise<any> = this.httpService.post(this.handlerUrl, params)
                .then((response: any): ng.IPromise<any> => this.handlerResponded(response, params));
            return result;
        }

        handlerResponded(response: any, params: any): any {
            response.data.requestParams = params;
            return response.data;
        }

    }
} 