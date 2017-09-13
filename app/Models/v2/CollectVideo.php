<?php

namespace App\Models\v2;

use App\Models\BaseModel;

use App\Helper\Token;
use Illuminate\Support\Facades\DB;

class CollectVideo extends BaseModel
{
	protected $connection = 'shop';

	protected $table = 'collect_video';

	protected $primaryKey = 'rec_id';

	public $timestamps = false;

	protected $guarded = [];


	public static function getList(array $attributes)
	{
		extract($attributes);

		$uid = Token::authorization();

		$model = self::where(['user_id' => $uid])->with('video')->orderBy('add_time', 'DESC');
		//paged
		$total = $model->count();
		$data  = $model->paginate($per_page)
			->toArray();
		//format
		$videos = [];
		foreach ($data['data'] as $key => $value) {
			$videos[$key] = $data['data'][$key]['video'];
		}

		return self::formatBody(['videos' => $videos, 'paged' => self::formatPaged($page, $per_page, $total)]);
	}

	/**
	 * 获取当前用户收藏此视频状态
	 *
	 * @access public
	 * @param integer $video_id
	 * @return integer
	 */
	public static function getIsLiked($video_id)
	{
		$uid = Token::authorization();
		if ($model = self::where(['user_id' => $uid])->where(['video_id' => $video_id])->first()) {
			return true;
		}
		return false;
	}

	public static function setLike(array $attributes)
	{
		extract($attributes);

		$uid = Token::authorization();
		$num = CollectVideo::where(['user_id' => $uid, 'video_id' => $video])->count();

		//因为有网站和手机 所以可能$num大于1
		if ($num == 0) {
			$model               = new CollectVideo;
			$model->user_id      = $uid;
			$model->video_id     = $video;
			$model->add_time     = time();
			$model->is_attention = 1;

			if ($model->save()) {
				return self::formatBody(['is_liked' => true]);
			} else {
				return self::formatError(self::UNKNOWN_ERROR);
			}
		} elseif ($num > 0) {
			return self::formatBody(['is_liked' => true]);
		}

	}

	public static function setUnlike(array $attributes)
	{
		extract($attributes);

		$uid   = Token::authorization();
		$model = self::where(['user_id' => $uid, 'video_id' => $video]);
		$num   = $model->count();

		if ($num == 1) {
			$model->delete();
		} else if ($num > 1) {
			for ($i = 0; $i < $num; $i++) {
				$model = $model->first();
				$model->delete();
			}
		}
		if ($model->count() == 0) {
			return self::formatBody(['is_liked' => false]);
		}
	}

	public function video()
	{
		return $this->hasOne('App\Models\v2\Video', 'id', 'video_id');
	}

}
