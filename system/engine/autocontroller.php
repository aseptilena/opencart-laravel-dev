<?php
abstract class Autocontroller extends Controller {
	public $data = array();
	public $error = array();

	public function index() {
		$this->start();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->post();
		}

		$this->data['content'] = $this->content();

		$this->end();
	}
	public function post() {
		
	}
	public function content() {
		
	}
	public function start() {
		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/cooperation')
		);

		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['column_right'] = $this->load->controller('common/column_right');
		$this->data['content_top'] = $this->load->controller('common/content_top');
		$this->data['content_bottom'] = $this->load->controller('common/content_bottom');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['header'] = $this->load->controller('common/header');
	}
	public function end() {
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/blade.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/blade.tpl', $this->data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/blade.tpl', $this->data));
		}
	}
}