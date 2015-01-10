<?php namespace App\Service;

use App\Eloquent\Customer;
use App\Eloquent\Deposit;
use App\Eloquent\DepositHistory;

class RemitService
{
	protected $user;

	protected $params;

	protected $customer;

	public function __construct($user, $params)
	{
		$this->user = $user;
		$this->params = $params;
		$this->customer = Customer::find($this->params['customer_id']);
	}
	public function validate()
	{
		$draw_amount = $this->params['draw_amount'];
		$transaction = $this->customer->customerTransactions()->sum('amount');

		if (!is_numeric($draw_amount)) {
			return ['error' => '請填入數字'];
		}
		if ($draw_amount < 0) {
			return ['error' => '不可小於0'];
		}
		if ($transaction < $draw_amount) {
			return ['error' => '提撥金額大於餘額'];
		}
		return false;
	}
	public function remit()
	{
		$deposit_status_id = $this->params['deposit_status_id'];
		$draw_amount = $this->params['draw_amount'];

		$deposit = $this->customer->deposits()->find($this->params['deposit_id']);
		$user = $this->user;

		$status_changed = $deposit->status != $deposit_status_id;

		$oldStatusName = $deposit->statusName();
		$detail = '管理者'.$user->user_id;

		if ($status_changed) {
			$detail .= ' '.$user->username.'將狀態'.$oldStatusName.'變更為'.Deposit::statusNameOf($deposit_status_id);
			$deposit->status = $deposit_status_id;
		}
		if ($draw_amount != 0) {
			$detail .= '，提撥了'.$draw_amount.'元';
			$deposit->increment('remit_amount', $draw_amount);
		}
		$deposit->reply = $this->params['deposit_reply'];
		$deposit->save();

		$history = DepositHistory::create([
			'user_id' => $user->user_id, 
			'detail' => $detail,
			'comment' => $this->params['draw_comment'],
			'amount' => $draw_amount
		]);
		$deposit->depositHistories()->save($history);

		if ($draw_amount != 0) {
			$this->customer->customerTransactions()->create([
				'order_id' => 0,
				'profit_record_id' => 0,
				'description' => '已提撥了'.$draw_amount.'元，要求編號'.$deposit->id.' 交易編號:'.$history->id,
				'amount' => (- $draw_amount)
			]);
		}
	}
}