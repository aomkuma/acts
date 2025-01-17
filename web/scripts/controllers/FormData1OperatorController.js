angular.module('e-homework').controller('FormData1OperatorController', function($scope, $compile, $cookies, $filter, $state, $routeParams, HTTPService, IndexOverlayFactory) {
    IndexOverlayFactory.overlayShow();

    $scope.page_type = 'operator-list';

    $scope.loadMenu = function(action){
        HTTPService.clientRequest(action, null).then(function(result){
            //console.log(result);
            $scope.Menu = result.data.DATA.Menu;
            IndexOverlayFactory.overlayHide();
            $(document).ready(function(){
                // console.log('asd');
              $('a.test').on("click", function(e){
                // alert('aa');
                // $('ul.dropdown-menu').hide();
                $(this).next('ul').toggle();
                e.stopPropagation();
                e.preventDefault();
              });
            });

            // $scope.load('menu/page/get', $scope.ID);
            
        });
    }

    $scope.getMenu = function(action, menu_type){
        var params = {'menu_type' : menu_type};
        HTTPService.clientRequest(action, params).then(function(result){
            console.log(result);
            $scope.MenuName = result.data.DATA.Menu;
            IndexOverlayFactory.overlayHide();
        });
    }
    $scope.loadList = function(condition, operator_type){
        $scope.operator_type = operator_type;
        IndexOverlayFactory.overlayShow();
        var params = {'condition' : condition, 'menu_type' : $scope.page_type, 'operator_type' : operator_type, 'actives' : 'Y'};
        HTTPService.clientRequest('form-data1/operator/list', params).then(function(result){
            if(result.data.STATUS == 'OK'){
                $scope.DataList = result.data.DATA.List;
            }
            IndexOverlayFactory.overlayHide();
        });
    }

    $scope.cancel = function(){
        $scope.PAGE = 'MAIN';
    }

    $scope.condition = {'keyword':''};
    $scope.PAGE = 'MAIN';

    $scope.loadMenu('menu/list');
    $scope.getMenu('menu/get/type' ,$scope.page_type);
    $scope.loadList($scope.condition, 'private');
    

});