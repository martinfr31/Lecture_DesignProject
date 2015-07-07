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
var Energy;
(function (Energy) {
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
    Energy.EnergyService = EnergyService;
})(Energy || (Energy = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Energy;
(function (Energy) {
    'use strict';

    var FirstEnergyWidget = (function () {
        function FirstEnergyWidget() {
            this.templateUrl = "Module/Process/Views/firstenergywidget.html";
            this.scope = {
                data: "=",
                size: "@"
            };
        }
        FirstEnergyWidget.prototype.injection = function () {
            return [
                function () {
                    return new FirstEnergyWidget();
                }
            ];
        };
        return FirstEnergyWidget;
    })();
    Energy.FirstEnergyWidget = FirstEnergyWidget;
})(Energy || (Energy = {}));
/// <reference path="../../../Scripts/_app.ts" />
var Energy;
(function (Energy) {
    'use strict';

    var EnergyController = (function () {
        function EnergyController($scope, serviceEnergy) {
            this.$scope = $scope;
            console.log("Energy Constructor started");
            serviceEnergy.getData().then(function (data) {
                return $scope.energyData = data;
            });
            setInterval(function () {
                $scope.$apply(function () {
                    serviceEnergy.getData().then(function (data) {
                        return $scope.energyData = data;
                    });
                });
            }, 1000);
        }
        EnergyController.$inject = ['$scope', 'energyService'];
        return EnergyController;
    })();
    Energy.EnergyController = EnergyController;
})(Energy || (Energy = {}));
/// <reference path="typings/angularjs/angular.d.ts" />
/// <reference path="typings/jquery/jquery.d.ts" />
/// <reference path="../App/Service/HttpHandlerService.ts" />
/// <reference path="../Module/Process/Service/EnergyService.ts" />
/// <reference path="../Module/Process/Directives/FirstEnergyWidget.ts" />
/// <reference path="../Module/Process/Controller/EnergyController.ts" />
/// <reference path="../app.ts" />
/// <reference path="Scripts/_app.ts" />
var App;
(function (App) {
    'use strict';
    angular.module('app', []).service('energyService', Energy.EnergyService).directive('energywidget', Energy.FirstEnergyWidget.prototype.injection()).controller('EnergyController', Energy.EnergyController);
})(App || (App = {}));
/// <reference path="../../../Scripts/_app.ts" />
//# sourceMappingURL=app.js.map
