<?php

require_once(DIR_SYSTEM.'laravel/load.php');

use App\Eloquent\Customer;
use App\Eloquent\User;
use App\Eloquent\Level;
use App\Validation\LevelValidator;

use App\View\ViewManager;

class ControllerSaleLevel extends Controller {
	public function index() {
		$this->document->setTitle('會員等級');

		$data['content'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('common/blade.tpl', $data));
	}
	public function edit() {
		$this->document->setTitle('會員等級');

		$data['content'] = $this->getForm();

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
			'text' => '會員等級',
			'href' => $this->url->link('sale/level', 'token=' . $this->session->data['token'], 'SSL')
		];

		$levels = Level::all();
		$data['levels'] = $levels;
		$data['opencart'] = $this;

		$r = ViewManager::loadBlade('not-sure', 'level/view.blade.php', $data);
		return $r->render();
	}
	public function getForm() {
		$level = Level::find($this->request->get['level_id']);
		$level->fill($this->request->post);

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$validator = new LevelValidator();

			$validator->with($this->request->post)->passes();

			$this->errors = $validator->getResponse();
			if (($this->request->post['downline'] && !$this->request->post['level_id']) || (!$this->request->post['downline'] && $this->request->post['level_id'])) {
				$this->errors = array_merge($this->errors, array('須同時設定擁有幾位與會員等級'));
			}
			if (!($this->request->post['downline'] && $this->request->post['level_id'])) {
				if ($this->request->post['next']) {
					$this->errors = array_merge($this->errors, array('你設定的下月組織業績不起作用'));
				}
			}

			if (count($this->errors) == 0) {
				$level->save();
				$data['success'] = '更新成功';
			}
			else {
				$data['errors'] = $this->errors;
			}
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = (object)[
			'text' => 'Home',
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		];

		$data['breadcrumbs'][] = (object)[
			'text' => '會員等級',
			'href' => $this->url->link('sale/level', 'token=' . $this->session->data['token'], 'SSL')
		];

		$levels = Level::all();
		$data['levels'] = $levels;
		$data['level'] = $level;
		$data['opencart'] = $this;

		$r = ViewManager::loadBlade('not-sure', 'level/form.blade.php', $data);
		return $r->render();
	}
}