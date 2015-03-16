<?php
class ControllerModuleAskSeller extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/ask_seller');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
		
			if (isset($this->request->post['mvd_ask_seller_title'])) {
				$this->request->post['mvd_ask_seller_title'] = serialize($this->request->post['mvd_ask_seller_title']);
			}
			
			if (isset($this->request->post['mvd_ask_seller_description'])) {
				$this->request->post['mvd_ask_seller_description'] = serialize($this->request->post['mvd_ask_seller_description']);
			}
			
			if (isset($this->request->post['mvd_ask_seller_subject_text'])) {
				$this->request->post['mvd_ask_seller_subject_text'] = serialize($this->request->post['mvd_ask_seller_subject_text']);
			}
			
			$this->model_setting_setting->editSetting('mvd_ask_seller', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));			

		}
				
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default_title'] = $this->language->get('text_default_title');
		$data['text_default_description'] = $this->language->get('text_default_description');
		$data['text_edit'] = $this->language->get('text_edit');
		
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_email_seller'] = $this->language->get('entry_email_seller');
		$data['entry_email_store_admin'] = $this->language->get('entry_email_store_admin');
		$data['entry_additional_email_address'] = $this->language->get('entry_additional_email_address');
		
		$data['help_title'] = $this->language->get('help_title');
		$data['help_description'] = $this->language->get('help_description');
		$data['help_subject'] = $this->language->get('help_subject');
		$data['help_email_seller'] = $this->language->get('help_email_seller');
		$data['help_email_store_admin'] = $this->language->get('help_email_store_admin');
		$data['help_additional_email_address'] = $this->language->get('help_additional_email_address');
		
		$data['column_text'] = $this->language->get('column_text');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_status'] = $this->language->get('column_status');
		
		$data['button_add_text'] = $this->language->get('button_add_text');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_notification'] = $this->language->get('tab_notification');

 	    $data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ask_seller', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['action'] = $this->url->link('module/ask_seller', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->load->model('localisation/language');		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['mvd_ask_seller_status'])) {
			$data['mvd_ask_seller_status'] = $this->request->post['mvd_ask_seller_status'];
		} else {
			$data['mvd_ask_seller_status'] = $this->config->get('mvd_ask_seller_status');
		}
		
		$mvd_ask_seller_title = unserialize($this->config->get('mvd_ask_seller_title'));
		
		if (isset($this->request->post['mvd_ask_seller_title'])) {
			$data['mvd_ask_seller_title'] = $this->request->post['mvd_ask_seller_title'];
		} elseif (isset($mvd_ask_seller_title)) {
			$data['mvd_ask_seller_title'] = $mvd_ask_seller_title;
		} else { 	
			$data['mvd_ask_seller_title'] = array();
		}
		
		$mvd_ask_seller_description = unserialize($this->config->get('mvd_ask_seller_description'));
		
		if (isset($this->request->post['mvd_ask_seller_description'])) {
			$data['mvd_ask_seller_description'] = $this->request->post['mvd_ask_seller_description'];
		} elseif (isset($mvd_ask_seller_description)) {
			$data['mvd_ask_seller_description'] = $mvd_ask_seller_description;
		} else { 	
			$data['mvd_ask_seller_description'] = array();
		}
		
		$mvd_ask_seller_subject_text = unserialize($this->config->get('mvd_ask_seller_subject_text'));
		
		if (isset($this->request->post['mvd_ask_seller_subject_text'])) {
			$data['mvd_ask_seller_subject_text'] = $this->request->post['mvd_ask_seller_subject_text'];
		} elseif (isset($mvd_ask_seller_subject_text)) {
			$data['mvd_ask_seller_subject_text'] = $mvd_ask_seller_subject_text;
		} else { 
			$data['mvd_ask_seller_subject_text'] = array();
		}
		
		if (isset($this->request->post['mvd_ask_seller_email_store_admin'])) {
			$data['mvd_ask_seller_email_store_admin'] = $this->request->post['mvd_ask_seller_email_store_admin'];
		} else {
			$data['mvd_ask_seller_email_store_admin'] = $this->config->get('mvd_ask_seller_email_store_admin');
		}
	
		if (isset($this->request->post['mvd_ask_seller_add_email_address'])) {
			$data['mvd_ask_seller_add_email_address'] = $this->request->post['mvd_ask_seller_add_email_address'];
		} else {
			$data['mvd_ask_seller_add_email_address'] = $this->config->get('mvd_ask_seller_add_email_address');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ask_seller.tpl', $data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/ask_seller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>