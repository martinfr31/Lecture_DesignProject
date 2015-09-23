var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
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
            var result = this.httpService.get(this.handlerUrl, params)
                .then(function (response) { return _this.handlerResponded(response, params); });
            return result;
        };
        HttpHandlerService.prototype.usePostHandler = function (params) {
            var _this = this;
            var result = this.httpService.post(this.handlerUrl, params)
                .then(function (response) { return _this.handlerResponded(response, params); });
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
            this.handlerUrl = 'chart1_demo_testdaten.txt';
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
            this.templateUrl = "Module/ChartModul/Views/firstchartwidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
        FirstChartWidget.prototype.injection = function () {
            return [
                function () { return new FirstChartWidget(); }
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
            serviceChart.getData().then(function (data) { return $scope.chartData = data; });
            setInterval(function () {
                $scope.$apply(function () {
                    serviceChart.getData().then(function (data) { return $scope.chartData = data; });
                });
            }, 1000);
        }
        ChartController.$inject = ['$scope', 'chartService'];
        return ChartController;
    })();
    Chart.ChartController = ChartController;
})(Chart || (Chart = {}));
/// <reference path="typings/angularjs/angular.d.ts" />
/// <reference path="typings/jquery/jquery.d.ts" />
/// <reference path="typings/d3/d3.d.ts" />
/// <reference path="../App/Service/HttpHandlerService.ts" /> 
/// <reference path="../Module/ChartModul/Service/ChartService.ts" /> 
/// <reference path="../Module/ChartModul/Directives/FirstChartWidget.ts" /> 
/// <reference path="../Module/ChartModul/Controller/ChartController.ts" /> 
/// <reference path="../app.ts" />
/// <reference path="Scripts/_app.ts" />
var App;
(function (App) {
    'use strict';
    angular.module('app', [])
        .service('chartService', Chart.ChartService)
        .directive('chartwidget', Chart.FirstChartWidget.prototype.injection())
        .controller('ChartController', Chart.ChartController);
})(App || (App = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Chartx;
(function (Chartx) {
    'use strict';
    var ChartController = (function () {
        function ChartController($scope, serviceEnergy) {
            this.$scope = $scope;
            console.log("Energy Constructor started");
            serviceEnergy.getData().then(function (data) { return $scope.energyData = data; });
            setInterval(function () {
                $scope.$apply(function () {
                    serviceEnergy.getData().then(function (data) { return $scope.energyData = data; });
                });
            }, 1000);
        }
        ChartController.$inject = ['$scope', 'energyService'];
        return ChartController;
    })();
    Chartx.ChartController = ChartController;
})(Chartx || (Chartx = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Chartx;
(function (Chartx) {
    'use strict';
    var FirstEnergyWidget = (function () {
        function FirstEnergyWidget() {
            this.templateUrl = "Module/ChartModul/Views/firstenergywidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
        FirstEnergyWidget.prototype.injection = function () {
            return [
                function () { return new FirstEnergyWidget(); }
            ];
        };
        return FirstEnergyWidget;
    })();
    Chartx.FirstEnergyWidget = FirstEnergyWidget;
})(Chartx || (Chartx = {}));
/// <reference path="../../../Scripts/_app.ts" />
/// <reference path="../../../Scripts/_app.ts" />
/// <reference path="../../../Scripts/_app.ts" />
var Chartx;
(function (Chartx) {
    var EnergyService = (function (_super) {
        __extends(EnergyService, _super);
        function EnergyService($http) {
            this.handlerUrl = 'http://cloud.livinglab-energy.de/ProSeminar/SchnittstelleLive.php';
            _super.call(this, $http);
        }
        EnergyService.prototype.getData = function () {
            var config = {};
            return this.useGetHandler(config);
        };
        EnergyService.$inject = ['$http'];
        return EnergyService;
    })(App.HttpHandlerService);
    Chartx.EnergyService = EnergyService;
})(Chartx || (Chartx = {}));
//# sourceMappingURL=app.js.map