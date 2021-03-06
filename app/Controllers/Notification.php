<?php

// if ( ! defined('BASEPATH')) exit('No direct script access allowed');
namespace App\Controllers;
use App\Libraries\Veritrans;
use App\Models\ModelPublic;

class Notification extends BaseController {

	/**
	* Index Page for this controller.
	*
	* Maps to the following URL
	* 		http://example.com/index.php/welcome
	*	- or -
	* 		http://example.com/index.php/welcome/index
	*	- or -
	* Since this controller is set as the default controller in
	* config/routes.php, it's displayed at http://example.com/
	*
	* So any other public methods not prefixed with an underscore will
	* map to /index.php/welcome/<method_name>
	* @see http://codeigniter.com/user_guide/general/urls.html
	*/


	public function __construct()
	{
		// parent::__construct();
		$params = array('server_key' => 'SB-Mid-server-b6FrguK7IhTaWTHhcgSNUJMN', 'production' => false);
		// $this->load->library('veritrans');
		$veritrans = new Veritrans;
		$veritrans->config($params);
		base_url('url');

	}

	public function index()
	{
		$veritrans = new Veritrans;
		$ModelPublic = new ModelPublic;

		echo 'test notification handler';
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);

		#get data id
		$order_id = $result->order_id;
		$dataUpdate = $ModelPublic->jumlahPesan($order_id);

		if ($result->status_code==200) {
			if ($ModelPublic->VerifyTransaksiSukses($order_id)) {
				if ($ModelPublic->VerifySukses($order_id)) {
					if ($ModelPublic->UpdateJual($dataUpdate['banyak'], $dataUpdate['id_jual'])) {
						echo "sukses verify";
					}
				}
			}
		} elseif ($result->status_code==202) {
			if ($ModelPublic->UpdateGagalJual($dataUpdate['banyak'], $dataUpdate['id_jual'])) {
				echo "sukses verify";
			}
		}

		// if($result){
		// 	$notif = $veritrans->status($result->order_id);
		// }
		//
		// error_log(print_r($result,TRUE));

		//notification handler sample

		/*
		$transaction = $notif->transaction_status;
		$type = $notif->payment_type;
		$order_id = $notif->order_id;
		$fraud = $notif->fraud_status;

		if ($transaction == 'capture') {
		// For credit card transaction, we need to check whether transaction is challenge by FDS or not
		if ($type == 'credit_card'){
		if($fraud == 'challenge'){
		// TODO set payment status in merchant's database to 'Challenge by FDS'
		// TODO merchant should decide whether this transaction is authorized or not in MAP
		echo "Transaction order_id: " . $order_id ." is challenged by FDS";
	}
	else {
	// TODO set payment status in merchant's database to 'Success'
	echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
}
}
}
else if ($transaction == 'settlement'){
// TODO set payment status in merchant's database to 'Settlement'
echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
}
else if($transaction == 'pending'){
// TODO set payment status in merchant's database to 'Pending'
echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
}
else if ($transaction == 'deny') {
// TODO set payment status in merchant's database to 'Denied'
echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
}*/

}
}
