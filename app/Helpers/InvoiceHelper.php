<?php

namespace App\Helpers;
use Response;
use App\Models\Order;

use Mail;

class InvoiceHelper{
	
	function generate(){
		/*  INV-ddmmyyyXXXXX (INV-0206201500356) */
		// $lastInvoice = "INV-270720150001";

		$orders = new Order;
		$lastOrder = $orders->orderBy('created_at','desc')->first();
		if($lastOrder){
			$lastInvoice = $lastOrder->invoice_no;
		}
		
		# date configuration
		$date = Date('d');
		$month = Date('m');
		$year = Date('Y');
		$newInvoice = "";
		if(!$lastOrder || !$lastInvoice){
			$newInvoice = "INV-".$date.$month.$year."0001";
		}else{
			#get the month of the lastInvoice
			$last_inv_month = substr($lastInvoice,6,2);
			if(!$last_inv_month==$month){
				$increment = "0001";
			}
			else{
				#get last four digit of last invoice number
				$zeroDigits = "";
				$invoice_length = strlen($lastInvoice);
				$last_increment = intval(substr($lastInvoice,($lastInvoice-4),4));
				for($c=0;$c<(4-$last_increment);$c++){
					$zeroDigits = $zeroDigits."0";
				}
				$new_increment = strval($last_increment + 1);
				$newInvoice = "INV-".$date.$month.$year.$zeroDigits.$new_increment;
			}
		}

		return $newInvoice;

	}
}