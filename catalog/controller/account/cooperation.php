<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\CustomerGroup;
use App\Eloquent\Language;
use App\View\ViewManager;
use Illuminate\Database\Capsule\Manager as DB;

class ControllerAccountCooperation extends Controller {
	public function index() {
		$this->document->setTitle('合作企業');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/cooperation')
		);

		$data['content'] = $this->getContent();

		$data['heading_title'] = '合作企業';
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/blade.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/blade.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/blade.tpl', $data));
		}
	}
	public function getContent() {
		$language = Language::current($this);
		$cooperations = CustomerGroup::cooperation()->get();
		foreach ($cooperations as &$cooperation) {
			$cooperation->setLanguage($language->language_id);
		}
		$data['cooperations'] = $cooperations;

		$cooperation_id = isset($this->request->get['cooperation_id']) ? $this->request->get['cooperation_id'] : 2;
		$cooperation = CustomerGroup::find($cooperation_id);
		$data['customers'] = $cooperation->customers;

		$data['opencart'] = $this;

		$r = ViewManager::loadBlade('not-sure', 'cooperation/catalog/view.blade.php', $data);
		return $r->render();
	}
}