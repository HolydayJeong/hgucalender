<?php
/**
 * @class  hgucalendarAdminView
 * @author CRA (developers@cra.com)
 * memo module's admin view class
 **/

class hgucalendarAdminView extends hgucalendar {
	function init() {
		// ������ ���ø� ������ ��� ���� (tpl)
            $template_path = sprintf("%stpl/",$this->module_path);
            $this->setTemplatePath($template_path);
	}

	function dispHgucalendarAdminDelete() {
 
            // ������ ��û�ϴ� module_srl Ȯ���ϰ� ������ ������ ��� ����
            if(!Context::get('module_srl')) 
				return $this->dispHgucalendarAdminShow();
 
            // ���õ� ����� ������ set ��
            $module_info = Context::get('module_info');
            Context::set('module_info',$module_info);
 
            // ���ø� ���� ����
            $this->setTemplateFile('hgucalendar_admin_delete');
        }

	function dispHgucalendarAdminInsertHgucalendar(){
		// ��Ų ����� ���ؿ�
		$oModuleModel = &getModel('module');
		$skin_list = $oModuleModel->getSkins($this->module_path);
		Context::set('skin_list',$skin_list);

		// ���̾ƿ� ����� ���ؿ�
		 $oLayoutMode = &getModel('layout');
		 $layout_list = $oLayoutMode->getLayoutList();
		 Context::set('layout_list', $layout_list);
		 $this->setTemplateFile('hgucalendar_admin_insert');
	}

	function dispHgucalendarAdminShow() {
		// ������ �׺�Խü��� ���� ����
		$page = Context::get('page');
		if(!$page) $page = 1;
		$args->page = $page;

		// book admin model ��ü ����
		$oBookAdminModel = &getAdminModel('hgucalendar');
		// book module_srl ��� ������
		$output = $oBookAdminModel->getHgucalendarAdminList($args);

		// ���ø��� �����ֱ� ���� set��
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('book_list', $output->data);
		Context::set('page_navigation', $output->page_navigation);

		// ������ ���(mid) ���� ���ø� ����(tpl/index.html)

		$this->setTemplateFile('index');
	}

	function dispHgucalendarAdminSkinInfo(){
		$oModuleAdminModel = &getAdminModel('module');
		$skin_content = $oModuleAdminModel->getModuleSkinHTML($this->module_info->module_srl);
		Context::set('skin_content', $skin_content);

		debugPrint($skin_content);

		$this->setTemplateFile('skin_info');
	}

}
?>