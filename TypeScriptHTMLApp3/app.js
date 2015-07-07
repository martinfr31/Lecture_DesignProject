var __extends = this.__extends || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
/// <reference path="../../Scripts/_app.ts" />
var App;
(function (App) {
    var Service = (function () {
        function Service() {
        }
        return Service;
    })();
    App.Service = Service;
    var HttpHandlerService = (function (_super) {
        __extends(HttpHandlerService, _super);
        function HttpHandlerService($http) {
            _super.call(this);
            this.httpService = $http;
        }
        HttpHandlerService.prototype.useGetHandler = function (params) {
            var _this = this;
            var result = this.httpService.get(this.handlerUrl, params).then(function (response) {
                return _this.handlerResponded(response, params);
            });
            return result;
        };

        HttpHandlerService.prototype.usePostHandler = function (params) {
            var _this = this;
            var result = this.httpService.post(this.handlerUrl, params).then(function (response) {
                return _this.handlerResponded(response, params);
            });
            return result;
        };

        HttpHandlerService.prototype.handlerResponded = function (response, params) {
            response.data.requestParams = params;
            return response.data;
        };
        return HttpHandlerService;
    })(Service);
    App.HttpHandlerService = HttpHandlerService;
})(App || (App = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Chart;
(function (Chart) {
    var ChartService = (function (_super) {
        __extends(ChartService, _super);
        function ChartService($http) {
            this.handlerUrl = 'http://cloud.livinglab-energy.de/ProSeminar/SchnittstelleLive.php';
            _super.call(this, $http);
        }
        ChartService.prototype.getData = function () {
            var config = {};
            return this.useGetHandler(config);
        };
        ChartService.$inject = ['$http'];
        return ChartService;
    })(App.HttpHandlerService);
    Chart.ChartService = ChartService;
})(Chart || (Chart = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Chart;
(function (Chart) {
    'use strict';

    var FirstChartWidget = (function () {
        function FirstChartWidget() {
            this.templateUrl = "Module/Process/Views/Charts.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
        FirstChartWidget.prototype.injection = function () {
            return [
                function () {
                    return new FirstChartWidget();
                }
            ];
        };
        return FirstChartWidget;
    })();
    Chart.FirstChartWidget = FirstChartWidget;
})(Chart || (Chart = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Chart;
(function (Chart) {
    'use strict';

    var ChartController = (function () {
        function ChartController($scope, serviceChart) {
            this.$scope = $scope;
            console.log("Chart Constructor started");
            serviceChart.getData().then(function (data) {
                return $scope.ChartData = data;
            });
            setInterval(function () {
                $scope.$apply(function () {
                    serviceChart.getData().then(function (data) {
                        return $scope.ChartData = data;
                    });
                });
            }, 1000);
        }
        ChartController.$inject = ['$scope', 'ChartService'];
        return ChartController;
    })();
    Chart.ChartController = ChartController;
})(Chart || (Chart = {}));
/// <reference path="typings/angularjs/angular.d.ts" />
/// <reference path="typings/jquery/jquery.d.ts" />
/// <reference path="../App/Service/HttpHandlerService.ts" />
/// <reference path="../Module/Process/Service/ChartService.ts" />
/// <reference path="../Module/Process/Directives/FirstChartWidget.ts" />
/// <reference path="../Module/Process/Controller/ChartController.ts" />
/// <reference path="../ChartApp.ts" />
/// <reference path="Scripts/_app.ts" />
var App;
(function (App) {
    'use strict';
    angular.module('app', []).service('Service', Chart.ChartService).directive('chartwidget', Chart.FirstChartWidget.prototype.injection()).controller('ChartController', Chart.ChartController);
})(App || (App = {}));
/// <reference path="../../../Scripts/_app.ts" />
//# sourceMappingURL=app.js.map
