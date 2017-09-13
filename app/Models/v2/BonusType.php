<?php

namespace App\Models\v2;

use App\Models\BaseModel;
use App\Helper\Token;

class BonusType extends BaseModel {

    protected $connection = 'shop';
    protected $table      = 'bonus_type';
    public    $timestamps = false;

    protected $appends = ['id', 'name', 'status', 'value', 'effective', 'expires', 'condition'];

    protected $visible = ['id', 'name', 'status', 'value', 'effective', 'expires', 'condition'];




    public static function getListByUser(array $attributes)
    {
        extract($attributes);

        $uid = Token::authorization();
        if (isset($status)) {

            $today  = self::today();
            $model = self::join('user_bonus', 'bonus_type.type_id', '=', 'user_bonus.bonus_type_id')
                   ->where('user_id', '<>', 0)
                   ->where('user_id', $uid);


            switch ($status) {
                case 0:
                $model =  $model->where('order_id', 0)
                                ->where('use_start_date', '<=', $today)
                                ->where('use_end_date', '>=', $today);
                    break;

                case 1:
                $model =  $model->where('order_id', 0)
                                ->where('use_start_date', '<=', $today)
                                ->where('use_end_date', '<', $today);
                    break;

                case 2:
                $model =  $model->where('order_id', '>', 0);
                    break;

                default:
                    return self::formatError(self::NOT_FOUND);
            }


            $total = $model->count();
            $data = $model->paginate($per_page)->toArray();

            return self::formatBody(['cashgifts' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
        }

        return self::formatError(self::NOT_FOUND);

    }

    public static function getAvailableListByUser(array $attributes)
    {
        extract($attributes);

        $today  = self::today();

        $uid = Token::authorization();

        $model =self::join('user_bonus','bonus_type.type_id','=','user_bonus.bonus_type_id')
                    ->where('user_id', '<>', 0)
                    ->where('user_id', $uid)
                    ->where('order_id', 0)
                    ->where('use_start_date', '<=', $today)
                    ->where('use_end_date', '>=', $today)
                    ->where('min_goods_amount', '<=', $total_price);


        $total = $model->count();
        $data = $model->paginate($per_page)->toArray();

        return self::formatBody(['cashgifts' => $data['data'],'paged' => self::formatPaged($page, $per_page, $total)]);
    }

    public function userbonus()
    {
        return $this->hasOne('App\Models\v2\UserBonus', 'bonus_type_id', 'type_id');
    }

    public function getIdAttribute()
    {
        return $this->attributes['bonus_id'];
    }

    public function getNameAttribute()
    {
        return $this->attributes['type_name'];
    }

    public function getStatusAttribute()
    {
        $today  = self::today();

        if($this->order_id > 0){
            return 2;
        }elseif ($this->use_end_date >= $today) {
            return 0;
        }elseif($this->use_end_date < $today){
            return 1;
        }
    }

    public function getValueAttribute()
    {
        return $this->attributes['type_money'];
    }

    public function getEffectiveAttribute()
    {
        return $this->attributes['use_start_date'];
    }

    public function getExpiresAttribute()
    {
        return $this->attributes['use_end_date'];
    }

    public function getConditionAttribute()
    {
      return $this->attributes['min_goods_amount'];
    }


    /**
     * 取得红包信息
     * @param   int     $bonus_id   红包id
     * @param   string  $bonus_sn   红包序列号
     * @param   array   红包信息
     */
    public static function bonus_info($bonus_id, $bonus_sn = '')
    {
        return self::join('user_bonus', 'bonus_type.type_id', '=', 'user_bonus.bonus_type_id')
                ->where('bonus_id', $bonus_id)
                ->first();
    }

    /**
     * 取得今日23:59:59的时间戳
     * @param   int 　时间戳
     */
    public static function today()
    {
        $timezone = ShopConfig::findByCode('timezone');
        $day    = getdate();
        $today  = mktime(23, 59, 59, $day['mon'], $day['mday'], $day['year']) - $timezone * 3600;
        return $today;
    }

    /*领取红包*/
    public static function receiveBonus(array $attributes)
    {
        extract($attributes);
        $avaiableReceiveType = [0];
        $today  = self::today();

        $uid = Token::authorization();

        if($uid <= 0){
            return self::formatError(self::NOT_FOUND);
        }

        $model = self::join('user_bonus','bonus_type.type_id','=','user_bonus.bonus_type_id')
                    ->where(['bonus_type_id'=>$id,'user_id'=>$uid])->first();
        if(!empty($model)){
            return self::formatError(self::BAD_REQUEST, trans('您已经领取过该红包了，把机会留给别人吧！'));
        }else{
            $bonusType = self::where(['type_id'=>$id])->first();
            if(empty($bonusType)){
                return self::formatError(self::BAD_REQUEST, trans('您领取的红包不存在，请去领取其它红包！'));
            }

            if($bonusType['send_start_date'] <= $today && $bonusType['send_end_date'] >= $today){
                if(in_array($bonusType['send_type'], $avaiableReceiveType)){
                    $bonusId = UserBonus::insertGetId(['bonus_type_id'=>$id,'user_id'=>$uid]);
                    if($bonusId){
                        return ["id"=>$bonusId,"name"=>$bonusType['type_name'],"status"=>0,"value"=>$bonusType['type_money'],
                                "effective"=>$bonusType['use_start_date'],"expires"=>$bonusType['use_end_date'],
                                "condition"=>$bonusType['min_goods_amount']];
                    }else{
                        return self::formatError(self::BAD_REQUEST, trans('服务器君太忙了，请稍后再试'));
                    }
                }else{
                    return self::formatError(self::BAD_REQUEST, trans('您无法领取此红包！'));
                }
            }else{
                return self::formatError(self::BAD_REQUEST, trans('红包已过期！'));
            }
        }
    }
}
