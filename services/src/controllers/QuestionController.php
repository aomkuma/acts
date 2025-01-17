<?php

    namespace App\Controller;
    
    use App\Service\QuestionService;
    use App\Service\EmailService;
    use App\Service\SubcommitteeService;
    use App\Service\AcademicBoardService;
    
    class QuestionController extends Controller {
        
        protected $logger;
        protected $db;
        
        public function __construct($logger, $db){
            $this->logger = $logger;
            $this->db = $db;
        }

        public function getListActive($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $years = $params['obj']['YearFrom'];
                
                $_List = QuestionService::getListActive($years);
                
                $this->data_result['DATA']['Questionnaire'] = $_List;
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getListPage($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $keyword = $params['obj']['keyword'];
                
                $_List = QuestionService::getListPage($keyword);
                
                $this->data_result['DATA']['Questionnaire'] = $_List;
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getList($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $currentPage = filter_var($params['obj']['currentPage'], FILTER_SANITIZE_NUMBER_INT);
                $limitRowPerPage = filter_var($params['obj']['limitRowPerPage'], FILTER_SANITIZE_NUMBER_INT);
                $questionType = $params['obj']['questionType'];

                $_Result = QuestionService::getList($currentPage, $limitRowPerPage, $questionType);

                $_List = $_Result['DataList'];
                $_Total = $_Result['Total'];

                $this->data_result['DATA']['Questionnaire'] = $_List;
                $this->data_result['DATA']['Total'] = $_Total;
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getStandardList($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                //$questionID = $params['obj']['questionID'];

                $_List = QuestionService::getStandardList();

                $this->data_result['DATA']['StandardList'] = $_List;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getStandardQuestionList($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                //$questionID = $params['obj']['questionID'];
                $standardID = $params['obj']['standardID'];
                $_List = QuestionService::getStandardQuestionList($standardID);

                $this->data_result['DATA']['QuestionList'] = $_List;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getData($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $questionID = $params['obj']['id'];
                $_Data = QuestionService::getData($questionID);
                // $_QuestionCommodityData = QuestionService::getQuestionCommodityData($questionID);
                $this->data_result['DATA']['Questionnaire'] = $_Data;
                // $this->data_result['DATA']['QuestionCommodity'] = $_QuestionCommodityData;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getDataByStandard($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $standardID = $params['obj']['standardID'];
                // $_Data = QuestionService::getDataByStandard($standardID);
                // $_QuestionCommodityData = QuestionService::getQuestionCommodityData($questionID);
                // $this->data_result['DATA']['Questionnaire'] = $_Data;
                // $this->data_result['DATA']['QuestionCommodity'] = $_QuestionCommodityData;
                $StandardQuestionnaireList = QuestionService::getQuestionListByStandard($standardID);
                $this->data_result['DATA']['StandardQuestionnaireList'] = $StandardQuestionnaireList;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function updateQuestionnaireResponseData($request, $response, $args){
            try{

                $params = $request->getParsedBody();
                $_Questionnaire = $params['obj']['Questionnaire'];
                $response_by = $params['obj']['ResponseBy'];
                $response_date = date('Y-m-d H:i:s');

                foreach ($_Questionnaire['question'] as $key => $value) {
                    $data = [];
                    $data['questionnaire_id'] = $_Questionnaire['questionnaireID'];
                    $data['q_id'] = $value['questionID'];
                    $data['q_response'] = $value['q_response'];
                    $data['q_response_comment'] = $value['q_response_comment'];
                    $data['response_by'] = $response_by;
                    $data['response_date'] = $response_date;

                    QuestionService::updateQuestionnaireResponseData($data);
                }

                $this->data_result['DATA']['result'] = $result;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function updateData($request, $response, $args){
            $_WEB_FILE_PATH = 'files/files';
            try{
                // error_reporting(E_ERROR);
                // error_reporting(E_ALL);
                // ini_set('display_errors','On');
                $params = $request->getParsedBody();
                $_Questionnaire = $params['obj']['Questionnaire'];
                $Questionnaire_Person = $_Questionnaire['questionnaire_person'];
                $QuestionList = $_Questionnaire['question'];

                unset($_Questionnaire['academicBoardName']);
                unset($_Questionnaire['subcommitteeName']);
                unset($_Questionnaire['standardName']);
                unset($_Questionnaire['questionnaire_person']);
                unset($_Questionnaire['question']);

                /*
                $_QuestionCommodity = $params['obj']['QuestionCommodity'];
                $user_session = $params['user_session'];
                
                $questionID = QuestionService::updateData($_Question);
                foreach ($_QuestionCommodity as $key => $value) {
                    if(empty($value['questionCommodityID'])){
                        unset($value['standardNameThai']);
                        $value['questionID'] = $questionID;
                        QuestionService::updateQuestionCommodityData($value);
                    }
                }
                */

                $files = $request->getUploadedFiles();
                $f = $files['obj']['AttachFile'];
                // print_r($f);
                // exit;
                if($f != null){
                    if($f->getClientFilename() != ''){
                        // Unset old image if exist
                        
                        $ext = pathinfo($f->getClientFilename(), PATHINFO_EXTENSION);
                        $FileName = date('YmdHis').'_'.rand(100000,999999). '.'.$ext;
                        $FilePath = $_WEB_FILE_PATH . '/question/'.$FileName;
                        
                        $_Questionnaire['fileName'] = $f->getClientFilename();
                        $_Questionnaire['filePath'] = $FilePath;
                        
                        $f->moveTo('../../' . $FilePath);
                    }        
                }

                $questionnaireID = QuestionService::updateData($_Questionnaire);

                // save questionare person
                
                foreach ($Questionnaire_Person as $key => $value) {
                    if(empty($value['questionnairePersonID'])){
                        $data = [];
                        $data['questionaireID'] = $questionnaireID;
                        $data['stakeholderID'] = $value['stakeholderID'];
                        $questionnairePersonID = QuestionService::updateQuestionnairePerson($data);
                    }
                }

                foreach ($QuestionList as $key => $value) {
                    unset($value['$hashKey']);
                    $value['questionaireID'] = $questionnaireID;
                    $questionID = QuestionService::updateQuestion($value);
                    
                }

                // update URL type = normal
                if($_Questionnaire['questionnaireType'] == 'normal'){
                    $link_url = "http://61.19.221.109/acfs/web/#/questionnaire-response/detail/" . $questionnaireID;
                    QuestionService::updateQuestionLinkURL($questionnaireID, $link_url);
                }

                $this->data_result['DATA']['questionnaireID'] = $questionnaireID;

                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function deleteData($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $_questionID = $params['obj']['id'];
                QuestionService::removeData($_questionID);
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function getListQuestionnairePerson($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function updateQuestionnairePerson($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function deleteQuestionnairePerson($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $questionnairePersonID = $params['obj']['id'];

                $result = QuestionService::removeQuestionnairePerson($questionnairePersonID);
                $this->data_result['DATA'] = $result;
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function deleteQuestion($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $questionID = $params['obj']['id'];

                $result = QuestionService::removeQuestion($questionID);

                $this->data_result['DATA'] = $result;
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }

        public function sendMail($request, $response, $args){
            try{
                $params = $request->getParsedBody();
                $Questionnaire = $params['obj']['Questionnaire'];
                $Questionnaire = QuestionService::getData($Questionnaire['questionnaireID']);
                // print_r($Questionnaire);
                // exit;

                $title_name = $Questionnaire['standardName'];
                if($Questionnaire['questionnaireSubType'] == 'standard'){
                    $groupName = $Questionnaire['academicBoardName'];
                }else{
                    $groupName = $Questionnaire['subcommitteeName'];
                    // if($Questionnaire['questionnaireType'] == 'online'){
                    $title_name = $Questionnaire['questionName'];
                    // }
                }
                $email_settings = EmailService::getEmailDefault();
                
                // $mail_content = "แบบสอบถาม " . $Questionnaire['questionName'];
                                // exit;
                $mailer = new Mailer;
                // $mailer->setMailHost('smtp.gmail.com');
                // $mailer->setMailPort('465');
                // $mailer->setMailUsername($email_settings->email);
                // $mailer->setMailPassword($email_settings->password);
                $mailer->setMailHost('tls://mail.acfs.go.th:587');
                $mailer->setMailPort('587');
                $mailer->setMailUsername('standarddevelopment@acfs.go.th');
                $mailer->setMailPassword('279sktX2DX');
                $mailer->setSubject("แบบสอบถาม " . $Questionnaire['questionName']);

                //$mail_content .= "<br>สามารถแสดงความคิดเห็นได้แล้วที่  <a href='".$Questionnaire['filePath']."'>" . $Questionnaire['filePath']."</a>";

                $mail_content .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ด้วย " . $groupName . " ได้ขอให้หน่วยงานที่เกี่ยวข้องพิจารณาให้ข้อคิดเห็นต่อ " . $title_name . "
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สำนักงานมาตรฐานสินค้าเกษตรและอาหารแห่งชาติ (มกอช.) พิจารณาแล้วเห็นว่าเรื่องดังกล่าวมีขอบข่ายเกี่ยวข้องกับท่าน/หน่วยงานของท่าน มกอช.";
    
                $link_url = $Questionnaire['filePath'];
                if($Questionnaire['questionnaireType'] == 'normal'){
                    $mail_content .= "ขอความร่วมมือพิจารณาให้ข้อคิดเห็นในเรื่องดังกล่าวให้ มกอช. ทราบ ภายในวันที่ " . $this->getThaiDate($Questionnaire['closeDate']);

                    $link_url = $Questionnaire['link_url'];

                    if(!empty($Questionnaire['fileName'])){
                        $mailer->addAttachFile('../../' . $Questionnaire['filePath'], $Questionnaire['fileName']);
                    }
                }

                
                $mail_content .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณาให้ข้อคิดเห็นในเรื่องดังกล่าวด้วย จะขอบคุณมาก
                ลิงก์แบบสอบถาม <a href='".$link_url."'>" . $link_url."</a>";
                // echo $mail_content;exit;
                $mailer->setHTMLContent($mail_content);
                $mailer->isHtml(true);

                if($Questionnaire['questionnaireSubType'] == 'committee'){
                    // set Receiver
                    $SubcommitteeList = SubcommitteeService::getData($Questionnaire['subcommitteeID']);
                    foreach ($SubcommitteeList['subcommitteePerson'] as $key => $value) {
                        
                        $mailer->setReceiver($value['email']);
                    }
                    $OtherList = $Questionnaire['questionnaire_person'];
                    foreach ($OtherList as $key => $value) {
                        $mailer->setReceiver($value['email']);
                    }
                }else{
                    $PersonList = AcademicBoardService::getAcademicBoardList($Questionnaire['standardID']);
                    foreach ($PersonList as $key => $value) {
                        $mailer->setReceiver($value->email);
                        $this->logger->info('Online Question Sent mail academic to ' . $value->email);
                    }
                }
                // exit;
                $res = $mailer->sendMail();
                if($res){
                    $this->logger->info('Online Question Sent mail Room success to ' . $userData->email);
                }else{
                    // print_r($res);
                    // exit;
                    $this->logger->info('Online Question Sent mail Room failed' . $userData->email . $res);
                }

                $this->data_result['DATA'] = $res;
                return $this->returnResponse(200, $this->data_result, $response, false);
                
            }catch(\Exception $e){
                return $this->returnSystemErrorResponse($this->logger, $this->data_result, $e, $response);
            }
        }


        private function getThaiDate($d){
            
            $arr = explode('-', $d);
            switch($arr[1]){
                case 1 : $monthTxt = 'มกราคม';break;
                case 2 : $monthTxt = 'กุมภาพันธ์';break;
                case 3 : $monthTxt = 'มีนาคม';break;
                case 4 : $monthTxt = 'เมษายน';break;
                case 5 : $monthTxt = 'พฤษภาคม';break;
                case 6 : $monthTxt = 'มิถุนายน';break;
                case 7 : $monthTxt = 'กรกฎาคม';break;
                case 8 : $monthTxt = 'สิงหาคม';break;
                case 9 : $monthTxt = 'กันยายน';break;
                case 10 : $monthTxt = 'ตุลาคม';break;
                case 11 : $monthTxt = 'พฤศจิกายน';break;
                case 12 : $monthTxt = 'ธันวาคม';break;
            }

            return $arr[2] . ' ' . $monthTxt . ' ' . ($arr[0] + 543);
        }

    
    }