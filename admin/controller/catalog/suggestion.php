<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\User;
use App\Eloquent\Suggestion;
use App\Validation\LevelValidator;

use App\View\ViewManager;

class ControllerCatalogSuggestion extends Controller {
	public function index() {
		$this->document->setTitle('商品建議');

		$data['content'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('common/blade.tpl', $data));
	}

	public function getList() {

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = (object)[
			'text' => 'Home',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = (object)[
			'text' => '商品建議',
			'href' => $this->url->link('sale/level', 'token=' . $this->session->data['token'], 'SSL')
		];

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$limit = $this->config->get('config_limit_admin');

		$suggestions = Suggestion::orderBy('id', 'desc')->take($limit)->offset($limit*($page-1))->get();
		$suggestion_total = Suggestion::count();
		$data['suggestions'] = $suggestions;
		$data['opencart'] = $this;

		$pagination = new Pagination();
		$pagination->total = $suggestion_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('catalog/suggestion', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($suggestion_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($suggestion_total - $limit)) ? $suggestion_total : ((($page - 1) * $limit) + $limit), $suggestion_total, ceil($suggestion_total / $limit));

		$r = ViewManager::loadBlade('not-sure', 'suggestion/admin/view.blade.php', $data);
		return $r->render();
	}
}