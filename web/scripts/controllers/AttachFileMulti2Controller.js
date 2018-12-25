angular.module('e-homework').controller('AttachFileMulti2Controller', function($scope, $compile, $cookies, $filter, $state, $routeParams, HTTPService, IndexOverlayFactory) {
    IndexOverlayFactory.overlayShow();
    
    $scope.page_type = $routeParams.page_type;

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

    $scope.loadMasterList = function(action, menu_type){
        var params = {'menu_type' : menu_type};
        HTTPService.clientRequest(action, params).then(function(result){
            console.log(result);
            $scope.MasterList = result.data.DATA.DataList;
            IndexOverlayFactory.overlayHide();
        });
    }

    $scope.loadDataList = function(action, menu_type, condition){
        var params = {'menu_type' : menu_type, 'condition' : condition, 'actives' : 'Y'};
        HTTPService.clientRequest(action, params).then(function(result){
            console.log(result);
            $scope.DataList = result.data.DATA.DataList;
            IndexOverlayFactory.overlayHide();
        });
    }

    $scope.search = function(condition){
        $scope.loadDataList('attachfile-multi/get/type' ,$scope.page_type, condition);
    }

    IndexOverlayFactory.overlayHide();
    $scope.FileList = [];
    
    $scope.loadMenu('menu/list');
    $scope.getMenu('menu/get/type' ,$scope.page_type);
    $scope.loadDataList('attachfile-multi/get/type' ,$scope.page_type, null);
    $scope.loadMasterList('attachfile-multi/get/master' ,$scope.page_type);

});