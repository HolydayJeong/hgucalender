<?php
/**
 * @class  memoView
 * @author CRA (developers@developers.com)
 * @brief View class of memo module
 **/

class hgucalendarView extends hgucalendar {
	function init() {
	  // module_srl이 있으면 미리 체크하여 존재하는 모듈이면 module_info 세팅
		$module_srl = Context::get('module_srl');
		if(!$module_srl && $this->module_srl) {
			$module_srl = $this->module_srl;
			Context::set('module_srl', $module_srl);
		}

		// module model 객체 생성 
		$oModuleModel = &getModel('module');

		// module_srl이 넘어오면 해당 모듈의 정보를 미리 구해 놓음
		// 모듈의 브라우저 타이틀, 관리자, 레이아웃 등 xe_modules table의 값과 정보
		if($module_srl) {
			$module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
			$this->module_info = $module_info;
			Context::set('module_info',$module_info);
		}

		// 스킨 경로를 미리 template_path 라는 변수로 설정함
		// 스킨이 존재하지 않는다면 default로 변경
		$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		if(!is_dir($template_path)||!$this->module_info->skin) {
			$this->module_info->skin = 'default';
			$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		}
		// 관리자 템플릿 파일의 경로 설정 (tpl)
            $template_path = sprintf("%stpl/",$this->module_path);
            $this->setTemplatePath($template_path);
	}

	function dispHgucalendarShow() {
		// 달력 목록 가져오는 모델 생성
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->getHgucalendarEventList($obj);

		//debugPrint($output->da);

		// Context에 세팅하기
		Context::set('eventinfo', $output->data);

		$output = $ohgucalendarModel->isManager();
		Context::set('manager', $output->data);

		//$eventinfo = Context::get('eventinfo');
		//debugPrint($eventinfo);

		// 정보를 보내기
		$this->setTemplateFile('calendar');
		
	}

	function arrangeHgucalendarInfo($output){
		if($output->data){
			foreach($output->data as $val){
				$obj = null;
				$obj->event_srl = $val->event_srl;
				$obj->eventname = $val->eventname;
				$obj->runtime = $val->runtime;
				$obj->sdate = $val->startdate;
				$obj->edate = $val->enddate;
				$obj->stime1_1 = $val->stime1_1;
				$obj->stime1_2 = $val->stime1_2;
				$obj->stime2_1 = $val->stime2_1;
				$obj->stime2_2 = $val->stime2_2;
				$obj->stime3_1 = $val->stime3_1;
				$obj->stime3_2 = $val->stime3_2;
				$obj->stime4_1 = $val->stime4_1;
				$obj->stime4_2 = $val->stime4_2;
				$obj->stime5_1 = $val->stime5_1;
				$obj->stime5_2 = $val->stime5_2;
			}
			return $obj;
		}
	}

	function dispHgucalendarContentRegist() {
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->UserRegCheck();
		
		if($output->data != null){
			// request object 다 받기
			$obj = Context::getRequestVars();

			$startY = substr($obj->start, 0, 4);
			$startM = substr($obj->start, 4, 2);
			$startD = substr($obj->start, 6, 2);
			$endY = substr($obj->end, 0, 4);
			$endM = substr($obj->end, 4, 2);
			$endD = substr($obj->end, 6, 2);
			Context::set('startY', $startY);
			Context::set('startM', $startM);
			Context::set('startD', $startD);
			Context::set('endY', $endY);
			Context::set('endM', $endM);
			Context::set('endD', $endD);

			// editor 모듈 사용하기
			// 에디터 모델 인스턴스 얻기

			$oDocumentModel = &getModel('document');

			// 옵션 정하기
			$option->allow_fileupload = true;
			$option->content_style = 'default';
			$option->content_font = null;
			$option->content_font_size = null;
			$option->enable_autosave = false;
			$option->enable_default_component = true;
			$option->enable_component = true;
			$option->disable_html = true;
			$option->height = 400;
			$option->skin = 'xpresseditor';
			$option->colorset = 'white_text_usehtml';
			$option->primary_key_name = 'document_srl';
			$option->content_key_name = 'content';
	 
			// 에디터 HTML 정하기
			$oDocument = $oDocumentModel->getDocument();
			Context::set('oDocument', $oDocument);
					
			// 관리자 템플릿 파일의 경로 설정 (tpl)
			$template_path = sprintf("%stpl/",$this->module_path);
			$this->setTemplatePath($template_path);
				
			$this->setTemplateFile('event_reg');
		}
		else{
			$info->message = "단체 등록을 해주세요";
				$info->url = "./index.php?mid=".$this->mid;
				Context::set('alertinfo',$info);
				// 정보를 보내기
				$this->setTemplateFile('alert');
//			echo('<script>alert("단체등록을 해주세요");location.href="./'.$this->mid.'";</script>');
		}
	}

	
	function dispHgucalendarEventModify() {
		$obj = Context::getRequestVars();
		$logged_info = Context::get("logged_info");

		// 글쓴이 체크 필요 X, 수정버튼이 글쓴이만 가능
		
		// request object 다 받기
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->getHgucalendarEvent($obj);
		//debugPrint($output);
		Context::set('eventinfo',$output->data[0]);
		//debugPrint($output->data[0]);

		$startY = substr($obj->start, 0, 4);
		$startM = substr($obj->start, 5, 2);
		$startD = substr($obj->start, 8, 2);
		$endY = substr($obj->end, 0, 4);
		$endM = substr($obj->end, 5, 2);
		$endD = substr($obj->end, 8, 2);
		Context::set('startY', $startY);
		Context::set('startM', $startM);
		Context::set('startD', $startD);
		Context::set('endY', $endY);
		Context::set('endM', $endM);
		Context::set('endD', $endD);

		// editor 모듈 사용하기
		// 에디터 모델 인스턴스 얻기

		$oDocumentModel = &getModel('document');

		// 옵션 정하기
		$option->allow_fileupload = true;
		$option->content_style = 'default';
		$option->content_font = null;
		$option->content_font_size = null;
		$option->enable_autosave = false;
		$option->enable_default_component = true;
		$option->enable_component = true;
		$option->disable_html = true;
		$option->height = 400;
		$option->skin = 'xpresseditor';
		$option->colorset = 'white_text_usehtml';
		$option->primary_key_name = 'document_srl';
		$option->content_key_name = 'content';
 
		// 에디터 HTML 정하기
		$oDocument = $oDocumentModel->getDocument($obj->document_srl);
		Context::set('oDocument', $oDocument);
		//debugPrint($oDocument);
				
		// 관리자 템플릿 파일의 경로 설정 (tpl)
        $template_path = sprintf("%stpl/",$this->module_path);
        $this->setTemplatePath($template_path);
		
		$this->setTemplateFile('event_modify');
	
	}

	function dispHgucalendarEventView() { //이벤트 세부내용 보여주기
		//document_srl 받아오기
		$obj = Context::getRequestVars();
		$document_srl = $obj->document_srl;

		//Document모델 가져오기
		$oDocumentModel = &getModel('document');
		$oDocument = $oDocumentModel->getDocument($document_srl);


	//	$oDocument->document_srl = $document_srl;

//		$oDocument->setDocument($document_srl);
	//	$oDocument->add('module_srl', $this->module_srl);
		
		
//		debugPrint($document_srl);
//		debugPrint($oDocument);

		Context::set('document_title', $oDocument->variables[title]);
		Context::set('document_srl', $document_srl);
		Context::set('oDocument',$oDocument);

//		debugPrint($oDocument->getUploadedFiles());

		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->getHgucalendarEvent($obj);
		
		// 유저의 GroupName 가져오기
		$output1 = $ohgucalendarModel->getUserGroup($output->data[0]);
		// Groupname 할당
		$output->data[0]->groupname = $output1->data->groupname;
		
		//이미지 가져오기
		$obj->file_srl = ($document_srl-1);
		$output1 = $ohgucalendarModel->getImgSrc($obj);
		$output->data[0]->source_filename = $output1->data->source_filename;
		$output->data[0]->uploaded_filename = $output1->data->uploaded_filename;

		// Context에 세팅하기
		Context::set('eventinfo', $output->data[0]);
		//debugPrint($output->data[0]);

		// 정보를 보내기
		$this->setTemplateFile('event');
	}

	function dispHgucalendarGroupManager() {
		// 모델 생성
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->groupapply();

		Context::set('member', $output->data);
		
		$this->setTemplateFile('groupManage');

	}

	function dispHgucalendarSeatManager(){
		$obj = Context::getRequestVars();

		// 모델 생성
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->getUnconfirmed($obj);

		Context::set('requests', $output->data);
//		$seat = Context::get('requests');
//		debugPrint($seat);

		$this->setTemplateFile('seatManage');
	}
	
	function dispHgucalendarSeatView(){
		$obj = Context::getRequestVars();

		// 모델 생성
		$ohgucalendarModel = &getModel('hgucalendar');
		$output = $ohgucalendarModel->getReserved($obj);
		
		Context::set('seat', $output->data);
//		$seat = Context::get('seat');
//		debugPrint($seat);

		$this->setTemplateFile('seat');
	}

	function dispHgucalendarUser(){
		// 내용 작성시 검증을 위해 사용되는 XmlJSFilter 
		Context::addJsFilter($this->module_path.'tpl/filter', 'user_insert.xml');

	    // 내용 작성화면 템플릿 파일 지정 register.html
		$this->setTemplateFile('register');
	}	

	function dispHgucalendarEventReg(){
		$this->setTemplateFile('event_reg');
	}

}
?>