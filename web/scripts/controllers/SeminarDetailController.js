angular.module('e-homework').controller('SeminarDetailController', function($scope, $compile, $cookies, $filter, $state, $routeParams, $uibModal, HTTPService, IndexOverlayFactory) {

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

    $scope.loadData = function(action, id){
        var params = {'id': id};
        HTTPService.clientRequest(action, params).then(function(result){
            $scope.Detail = result.data.DATA;
            IndexOverlayFactory.overlayHide();
        });
    }

    $scope.saveResponse = function(Data, AttachFile){

        $scope.alertMessage = 'ต้องการตอบรับการฝีกอบรมนี้ ใช่หรือไม่ ?';
        var modalInstance = $uibModal.open({
            animation : false,
            templateUrl : 'views/dialog_confirm.html',
            size : 'sm',
            scope : $scope,
            backdrop : 'static',
            controller : 'ModalDialogCtrl',
            resolve : {
                params : function() {
                    return {};
                } 
            },
        });

        modalInstance.result.then(function (valResult) {

            if(Data.start_date != null && Data.start_date != undefined && Data.start_date != ''){
                Data.start_date = makeSQLDate(Data.start_date);
            }
            if(Data.end_date != null && Data.end_date != undefined && Data.end_date != ''){
                Data.end_date = makeSQLDate(Data.end_date);
            }

            var params = {'Data': Data, 'AttachFile' : AttachFile};
            HTTPService.uploadRequest('seminar/response/add', params).then(function(result){
                $scope.loadList('seminar/list');
                $scope.PAGE = 'MAIN';
                IndexOverlayFactory.overlayHide();
            });
        });
    }

    $scope.viewDetail = function(data){
        window.location.href = '#/seminar/detail/' + data.id;
        // $scope.Detail = angular.copy(data);
        // $scope.PAGE = 'DETAIL';
    }

    $scope.viewResponse = function(id){
        window.location.href = '#/seminar/response/' + $scope.page_type + '/' + id;
        // $scope.Data = {'seminar_id' : $scope.Detail.id};
        $scope.PAGE = 'RESPONSE';
    }

    $scope.getThaiDate = function(date){
        // console.log('check date :'+date);
        if(date != undefined){
            var splitDate = date.split(' ');
            return convertDateToFullThaiDateIgnoreTime(new Date(splitDate[0]));
        }
    }

    $scope.popup1 = {
        opened: false
    };

    $scope.popup2 = {
        opened: false
    };

    $scope.open1 = function() {
        $scope.popup1.opened = true;
    };

    $scope.open2 = function() {
        $scope.popup2.opened = true;
    };

    $scope.condition = {'keyword' : ''};
    $scope.PAGE = 'MAIN';

    $scope.loadMenu('menu/list');
    $scope.getMenu('menu/get/type', $scope.page_type);
    $scope.loadData('seminar/get', $routeParams.id);
    

});