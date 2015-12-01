<?php

namespace App\Helpers;

use App\Models\RewardType;
use App\Models\RewardLog;
use App\Models\Customers;
use App\Models\ProductPoint;
use Carbon;

class RewardHelper
{
    public function rewardByReferal($customer){

        /*
        ** Check if customer has referal
        ** Check if referal  already get reward
        */
        if($customer->referal_id && !$customer->flag_referal_get_reward)
        {
            $referal = Customers::find($customer->referal_id);

            if(!$referal){
                /*
                ** Referal customer is not exist
                */

                return false;
            }

            $reference_id = [
                'customer_id' => $customer->id
            ];

            $data = [
                'customer_id' => $referal->id,
                'reward_key' => 'referal',
                'reference_id' => $reference_id
            ];

            if($this->addReward($data))
            {
                return true;
            }
            
        }

        return false;
    }

    public function rewardByOrder($customer, $order)
    {
        $products = $order['order_products'];

        $total_reward = 0;
        $reference_id = [];

        $reference_id['order_id'] = (string) $order['id'];

        /* 
        ** Check for multiple reward request 
        */
        $reward_key = $this->__rewardType('order');

        $log_check = RewardLog::where('customer_id', $customer->id)
                    ->where('reward_type_id', $reward_key->id)
                    ->where('reference_id','like','%"order_id":"'.$order['id'].'"%')->count();

        if($log_check)
        {   
            /*
            ** Break process if customer already earned reward from this order
            */
            return false;
        }

        /*
        ** Sum all point reward
        */
        foreach ($products as $key => $product) {
            /*
            ** Looking for product with date active date period or null(forever)
            */
            $product_point = ProductPoint::where('product_id', $product['product_id'])
                ->where('date_start', '<=', Carbon::now())
                ->where('date_end', '>=', Carbon::now())
                ->orWhere('product_id', $product['product_id'])
                ->where('date_start', null)
                ->where('date_end', null)
                ->orderBy('date_end', 'desc')
                ->orderBy('id', 'desc')
                ->first();

           if($product_point)
           {
                $reference_id['product_id'][] = (string) $product['product_id'];
                $total_reward += ($product_point->point * $product['quantity']);
           }
        }

        $data = [
            'customer_id' => $customer->id,
            'reward_key' => 'order',
            'reference_id' => $reference_id,
            'reward_point' => $total_reward
        ];

        /*
        ** Insert point reward
        */
        if($this->addReward($data))
        {
            return true;
        }

        return false;
    }

    public function addReward($data)
    {
        /*
        ** Cancel process if data invalid
        */
        if(!isset($data['reward_key']) || !isset($data['customer_id']) || !isset($data['reference_id']))
        {
            return false;
        }

        $customer_id = $data['customer_id'];
        $reward_key = $data['reward_key'];
        $reference_id = $data['reference_id'];

        /*
        ** Create log 
        */
        $reward_type = RewardType::where('key', $reward_key)->first();

        $reward_type_id = $reward_type->id;
        $point_rewards = $reward_type->point;

        if($reward_key == 'order')
        {
            $point_rewards = isset($data['reward_point']) ? $data['reward_point'] : $point_rewards;
        }

        $reward_log = new RewardLog;
        $reward_log->reward_type_id = $reward_type_id;
        $reward_log->customer_id = $customer_id;
        $reward_log->reference_id = is_array($reference_id) ? json_encode($reference_id) : $reference_id;
        $reward_log->point = $point_rewards;
        $reward_log->message = $this->__rewardMessage($reward_key, $point_rewards);

        /*
        ** Update customer point 
        */
        $customer = Customers::find($customer_id);
        $customer->point_rewards += $point_rewards;

        if($reward_key == 'referal' && isset($reference_id['customer_id']))
        {
            $referal = Customers::find($reference_id['customer_id']);
            $referal->flag_referal_get_reward = true;
        }

        try{
            $reward_log->save();
            $customer->save();

            if(isset($referal))
            {
                $referal->save();
            }

            return true;
        } catch(Exception $e) {
            return false;
        }

        return false;
    }

    private function __rewardMessage($reward_type_id, $point)
    {
        switch ($reward_type_id) {
            case 'first_invitation':
                $message = "Earned ".$point. " reward(s) point from register by invitation.";
                break;

            case 'upvote':
                $message = "Earned ".$point." reward(s) point by other customer upvote from belonging comment.";
                break;

            case 'referal':
                $message = "Earned ".$point." reward(s) point by refering your friend.";
                break;

            case 'order':
                $message = "Earned ".$point." reward(s) point by order.";
                break;
            
            default:
                $message = "Earned ".$point." reward(s) point.";
                break;
        }

        return $message;
    }

    private function __rewardType($key)
    {
        return RewardType::where('key', $key)->first();
    }
}
