angular.module('app').controller('StakeholderUpdateController', function($scope, $compile, $cookies, $filter, $state, $routeParams, HTTPService, IndexOverlayFactory) {
    IndexOverlayFactory.overlayShow();
    var $user_session = sessionStorage.getItem('user_session');
    
    if($user_session != null){
        $scope.$parent.currentUser = angular.fromJson($user_session);
    }else{
       window.location.replace('#/guest/logon');
    }
    console.log('Hello ! stakeholder update page');
	$scope.DEFAULT_LANGUAGE = 'TH';
    $scope.$parent.menu_selected = 'stakeholder';
    $scope.ID = $routeParams.id;

    $scope.loadStakeholder = function(action, id){
        var params = {'id': id};
        HTTPService.clientRequest(action, params).then(function(result){
            console.log(result);
            if(result.data.STATUS == 'OK'){
                $scope.Stakeholders = result.data.DATA.Stakeholder;
                 IndexOverlayFactory.overlayHide();
            }else{
                IndexOverlayFactory.overlayHide();
            }
        });
    }

    $scope.saveStakeholder = function(data){
        console.log(data);
        var params = {'Stakeholder' : data};
        IndexOverlayFactory.overlayShow();
        HTTPService.clientRequest('stakeholder/update', params).then(function(result){  
            // console.log(result.data);
            // $scope.loadStakeholders($scope.Commodity_Standards.standardID);
            if(result.data.STATUS == 'OK'){
                if($scope.ID === undefined){
                    window.location.href = '#/stakeholder/update/' + result.data.DATA.stakeholderID;
                }else{
                    $scope.ID = result.data.DATA.stakeholderID;
                    $scope.loadStakeholder('stakeholder/get', $scope.ID);
                    IndexOverlayFactory.overlayHide();    
                }
            }
            IndexOverlayFactory.overlayHide();
        });
    }

    $scope.cancelUpdate = function(){
        window.location.href = '#/stakeholder';
    }

    $scope.setStakeholder = function(){
        $scope.Stakeholders = {'stakeholderID':''
                                ,'nameThai':''
                                ,'lastNameThai':''
                                ,'nameEng':''
                                ,'lastNameEng':''
                                ,'positionThai':''
                                ,'positionEng':''
                                ,'responsible':''
                                ,'experience':''
                                ,'institution':''
                                ,'address':''
                                ,'phone':''
                                ,'fax':''
                                ,'email':''
                                ,'status':'Active'
                                ,'createBy':$scope.currentUser.adminID
                                ,'createDate':''
                                ,'updateBy':$scope.currentUser.adminID
                                ,'updateDate':''
                            };
    }

    IndexOverlayFactory.overlayHide();

    if($scope.ID !== undefined){
        $scope.loadStakeholder('stakeholder/get', $scope.ID);
    }else{
        $scope.setStakeholder();
    }
    

});