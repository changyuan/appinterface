<?php
//
namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helper\Token;
use App\Models\v2\Shipping;
use App\Models\v2\Goods;
use App\Models\v2\Comment;
use App\Models\v2\GoodsCategory;
use App\Models\v2\CollectVideo;
use App\Models\v2\Products;
use Log;

class VideoController extends Controller
{
	/**
	 * POST /ecapi.video.like
	 */
	public function setLike()
	{
		$rules = [
			'video' => 'required|integer|min:1',
		];

		if ($error = $this->validateInput($rules)) {
			return $error;
		}

		$data = CollectVideo::setLike($this->validated);

		return $this->json($data);
	}

	/**
	 * POST /ecapi.video.unlike
	 */
	public function setUnlike()
	{
		$rules = [
			'video' => 'required|integer|min:1',
		];

		if ($error = $this->validateInput($rules)) {
			return $error;
		}

		$data = CollectVideo::setUnlike($this->validated);

		return $this->json($data);
	}

	/**
	 * POST /ecapi.video.liked.list
	 */
	public function likedList()
	{
		$rules = [
			'page'     => 'required|integer|min:1',
			'per_page' => 'required|integer|min:1',
		];

		if ($error = $this->validateInput($rules)) {
			return $error;
		}

		$data = CollectVideo::getList($this->validated);

		return $this->json($data);
	}
}
