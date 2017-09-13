<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;

class UserBonus extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'user_bonus';
    public    $timestamps = false;
    protected $primaryKey = 'bonus_id';

    /* 红包发放的方式 */
    const SEND_BY_USER               = 0; // 按用户发放
    const SEND_BY_GOODS              = 1; // 按商品发放
    const SEND_BY_ORDER              = 2; // 按订单发放
    const SEND_BY_PRINT              = 3; // 线下发放


    /**
     * 设置红包为已使用
     * @param   int     $bonus_id   红包id
     * @param   int     $order_id   订单id
     * @return  bool
     */
    public static function useBonus($bonus_id, $order_id)
    {
        if($model = self::where('bonus_id', $bonus_id)->first()){
            $model->order_id  = $order_id;
            $model->used_time = time();
            if($model->save()){
                return true;
            }
        }
        return false;
    }

    /**
     * 设置红包为未使用
     * @param   int     $bonus_id   红包id
     * @return  bool
     */
    public static function unuseBonus($bonus_id)
    {
        if($model = self::where('bonus_id', $bonus_id)->first()){
            $model->order_id  = 0;
            $model->used_time = 0;
            if($model->save()){
                return true;
            }
        }
        return false;
    }

    /**
     * 根据订单信息获取用户红包
     * @param   int     $user_id   用户id
     * @param   int     $totalMoney   使用订单总金额
     * @return  bool
     */
    public static function getBonusByOrder($user_id,$totalMoney)
    {
        $all_bonus = UserBonus::leftJoin('bonus_type','user_bonus.bonus_type_id','=','bonus_type.type_id')
                        ->where(['user_id'=>$user_id,'order_id'=>0])
                        ->where('use_end_date','<',time())
                        ->orderBy('bonus_type.use_end_date')->get();
        $bonus_info = array('unavailable'=>[],'available'=>[]);
        foreach ($all_bonus as $key => $value) {
            unset($value['user_id']);
            unset($value['order_id']);
            unset($value['send_start_date']);
            unset($value['send_end_date']);
            unset($value['emailed']);
            unset($value['max_amount']);
            unset($value['min_amount']);
            unset($value['send_type']);
            unset($value['bonus_sn']);
            unset($value['used_time']);
            unset($value['type_id']);
            if($value['min_goods_amount'] > $totalMoney){//不可用的红包,目前红包只对订单金额有限制，无其他限制
                $bonus_info['unavailable'][] = $value;
            }else{//可用红包
                $bonus_info['available'][] = $value;
            }
        }
        return $bonus_info;
    }

}
