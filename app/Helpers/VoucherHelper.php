<?php

namespace App\Helpers;

use App\Models\Voucher;
use Carbon;

class VoucherHelper
{
    private $code = 0;
    private $voucher = 0;

    public function validate($voucher_code = 0)
    {
        if(!$voucher_code)
        {
            return false;
        }

        $return = [];

        $query = Voucher::query();
        $query->where('code', $voucher_code);
        $query->select('code', 'value', 'operation', 'status', 'date_expired');

        $voucher = $query->first();

        /*
        ** Check if voucher is exist
        */
        if($voucher)
        {
            /*
            ** Check voucher status is usable
            */
            if($voucher->status == false)
            {
                return false;
            }

            /*
            ** Check voucher expiry
            */
            if($voucher->date_expired >= Carbon::now())
            {
                $this->code = $voucher_code;
                $this->voucher = $voucher;

                return true;
            }

            return false;
        }

        return false;
    }

    public function getValue($grandtotal = 0)
    {
        $voucher_code = $this->code;

        if(!$this->validate($voucher_code))
        {
            return 0;
        }

        $voucher = $this->voucher;
        $result = $voucher->value;

        if($voucher->operation == "%")
        {
            $result = $grandtotal * ($voucher->value / 100);
        }

        /*
        ** Value to negative
        */
        return (-1 * abs($result));
    }

    public function updateStatus()
    {
        $voucher_code = $this->code;

        if(!$this->validate($voucher_code))
        {
            return false;
        }

        $original_voucher = Voucher::where('code', $this->voucher->code)->first();

        $voucher = Voucher::find($original_voucher->id);

        if($voucher->limit_count >= $voucher->limit)
        {
            return false;
        }

        $voucher->limit_count += 1;

        if($voucher->limit_count == $voucher->limit)
        {
            $voucher->status = false;
        }

        if($voucher->save())
        {
            return true;
        }
    }
}
