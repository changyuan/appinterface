<?php
//
use App\Helper\Token;
use Illuminate\Support\Facades\Redis;
use App\Services\Other\DesHelper;
use App\Models\v2\Member;


$app->get('/', function () use ($app) {
	return 'Hi';
});

// 生成一个用户的token ，测试使用
$app->get('/gen/{uid}', function ($uid) use($app) {
	$uid = DesHelper::des_encrypt($uid,"yaolan_app",true);
	return Member::rsyncUserInfo($uid);
});

// redis demo
$app->get('/redis', function () {

	Redis::set("name1", "test");
	return Redis::get("name1");
});


//Other
$app->group(['namespace' => 'App\Http\Controllers\v2', 'prefix' => 'v2'], function ($app) {
	$app->get('article.{id:[0-9]+}', 'ArticleController@show');

	//显示商城公告，店铺公告，用户通知什么的传递shop_config_id
	$app->get('notice.{id:[0-9]+}', 'NoticeController@show');

	//各种支付异步通知接口，这里不能用token，和自定义加密的，否则通不过
	$app->post('order.notify.{code}', 'OrderController@notify');

	//退款通知 code 
	$app->post('refund.notify.{code}', 'RefundController@notify');

	$app->get('product.intro.{id:[0-9]+}', 'GoodsController@intro');

	$app->get('product.share.{id:[0-9]+}', 'GoodsController@share');

	$app->get('api.auth.web', 'UserController@webOauth');

	$app->get('api.auth.web.callback/{vendor:[0-9]+}', 'UserController@webCallback');

	//token获取
	$app->get('api/auth/sign/{userid}', 'UserController@getSign');

	//商品列表
	$app->get('api/product/list', 'GoodsController@index');

	//分类列表
	$app->get('api/category/list', 'GoodsController@category');

	

});

//Guest
$app->group(['namespace' => 'App\Http\Controllers\v2', 'prefix' => 'v2', 'middleware' => ['xss']], function ($app) {
	$app->post('api.access.dns', 'AccessController@dns');

	$app->post('api.access.batch', 'AccessController@batch');

	$app->post('api.home.product.list', 'GoodsController@home');

	$app->post('api.search.product.list', 'GoodsController@search');

	$app->post('api.review.product.list', 'GoodsController@review');

	$app->post('api.review.product.subtotal', 'GoodsController@subtotal');

	$app->post('api.recommend.product.list', 'GoodsController@recommendList');

	$app->post('api.product.accessory.list', 'GoodsController@accessoryList');

	$app->post('api.product.get', 'GoodsController@info');


	//用户认证
	$app->post('api.auth.signin', 'UserController@signin');

	$app->post('api.auth.social', 'UserController@auth');

	$app->post('api.auth.default.signup', 'UserController@signupByEmail');

	$app->post('api.auth.mobile.signup', 'UserController@signupByMobile');

	$app->post('api.user.profile.fields', 'UserController@fields');

	$app->post('api.auth.mobile.verify', 'UserController@verifyMobile');

	$app->post('api.auth.mobile.send', 'UserController@sendCode');

	$app->post('api.auth.mobile.reset', 'UserController@resetPasswordByMobile');

	$app->post('api.auth.default.reset', 'UserController@resetPasswordByEmail');


	$app->post('api.cardpage.get', 'CardPageController@view');

	$app->post('api.cardpage.preview', 'CardPageController@preview');

	//配置信息
	$app->post('api.config.get', 'ConfigController@index');

	//文章列表
	$app->post('api.article.list', 'ArticleController@index');

	//品牌列表
	$app->post('api.brand.list', 'BrandController@index');

	//关键词列表
	$app->post('api.search.keyword.list', 'SearchController@index');

	//区域列表
	$app->post('api.region.list', 'RegionController@index');

	//发票
	$app->post('api.invoice.type.list', 'InvoiceController@type');

	$app->post('api.invoice.content.list', 'InvoiceController@content');

	$app->post('api.invoice.status.get', 'InvoiceController@status');

	$app->post('api.notice.list', 'NoticeController@index');

	$app->post('api.banner.list', 'BannerController@index');

	$app->post('api.version.check', 'VersionController@check');

	$app->post('api.recommend.brand.list', 'BrandController@recommend');

	//系统消息
	$app->post('api.message.system.list', 'MessageController@system');
	//未读消息
	$app->post('api.message.count', 'MessageController@unread');

	$app->post('api.site.get', 'SiteController@index');

	$app->post('api.splash.list', 'SplashController@index');

	$app->post('api.splash.preview', 'SplashController@view');

	$app->post('api.theme.list', 'ThemeController@index');

	$app->post('api.theme.preview', 'ThemeController@view');

	$app->post('api.search.category.list', 'GoodsController@categorySearch');

	$app->post('api.order.reason.list', 'OrderController@reasonList');

	$app->post('api.search.shop.list', 'ShopController@search');

	$app->post('api.recommend.shop.list', 'ShopController@recommand');

	$app->post('api.shop.list', 'ShopController@index');

	$app->post('api.shop.get', 'ShopController@info');

	$app->post('api.areacode.list', 'AreaCodeController@index');


});

//Authorization
$app->group(['prefix' => 'v2', 'namespace' => 'App\Http\Controllers\v2', 'middleware' => ['token', 'xss']], function ($app) {
	// 个人资料
	$app->post('api.user.profile.get', 'UserController@profile');

	$app->post('api.user.profile.update', 'UserController@updateProfile');

	$app->post('api.user.password.update', 'UserController@updatePassword');

	
	// 订单列表
	$app->get('api/order/list', 'OrderController@index');

	// 订单详情
	$app->get('api/order/get', 'OrderController@view');

	//购物车结算订单确认
	$app->post('api/order/check', 'OrderController@check');

	$app->post('api/order/submit', 'OrderController@submit');

	$app->post('api.order.confirm', 'OrderController@confirm');

	$app->post('api.order.cancel', 'OrderController@cancel');

	$app->post('api.order.price', 'OrderController@price');

	//添加产品收藏
	$app->post('api/product/like', 'GoodsController@setLike');
	//取消产品收藏
	$app->post('api/product/unlike', 'GoodsController@setUnlike');
	//产品收藏列表
	$app->get('api/product/liked/list', 'GoodsController@likedList');

	//添加视频收藏
	$app->post('api/video/like', 'VideoController@setLike');
	//取消视频收藏
	$app->post('api/video/unlike', 'VideoController@setUnlike');
	//视频收藏列表
	$app->get('api/video/liked/list', 'VideoController@likedList');

	$app->post('api.order.review', 'OrderController@review');

	$app->post('api.order.subtotal', 'OrderController@subtotal');

	//开通的支付类型（控制前台的支付选项）
	$app->post('api.payment.types.list', 'OrderController@paymentList');

	// 
	$app->post('api.payment.pay', 'OrderController@pay');

	//根据城市id，查询配送支持的快递
	$app->post('api.shipping.vendor.list', 'ShippingController@index');

	//获取订单所有的发货信息
	$app->post('api.shipping.get', 'ShippingController@info');

	

	//收货人地址信息
	$app->post('api.consignee.list', 'ConsigneeController@index');

	$app->post('api.consignee.update', 'ConsigneeController@modify');

	$app->post('api.consignee.add', 'ConsigneeController@add');

	$app->post('api.consignee.delete', 'ConsigneeController@remove');

	$app->post('api.consignee.setDefault', 'ConsigneeController@setDefault');

	


	$app->post('api.score.get', 'ScoreController@view');

	$app->post('api.score.history.list', 'ScoreController@history');

	//我的红包列表
	$app->get('api/cashgift/list', 'CashGiftController@index');

	//领取红包，目前仅支持用户类型的红包
	$app->post('api/cashgift/receive', 'CashGiftController@receive');

	$app->post('api.cashgift.available', 'CashGiftController@available');

	$app->post('api.push.update', 'MessageController@updateDeviceId');


	//加入购物车
	$app->post('api.cart.add', 'CartController@add');
	//清空购物车
	$app->post('api.cart.clear', 'CartController@clear');
	//删除购物车
	$app->post('api.cart.delete', 'CartController@delete');

	//购物车列表
	$app->post('api.cart.get', 'CartController@index');
	//更新购物车
	$app->post('api.cart.update', 'CartController@update');

	//购物车结算
	$app->post('api/cart/checkout', 'CartController@checkout');

	//促销
	$app->post('api.cart.promos', 'CartController@promos');



	//立即购买
	$app->post('api.product.purchase', 'GoodsController@purchase');

	$app->post('api.product.validate', 'GoodsController@checkProduct');

	//订单提醒
	$app->post('api.message.order.list', 'MessageController@order');

	$app->post('api.shop.watch', 'ShopController@watch');

	$app->post('api.shop.unwatch', 'ShopController@unwatch');

	$app->post('api.shop.watching.list', 'ShopController@watchingList');

    //优惠券（未完成）
	$app->post('api.coupon.list', 'CouponController@index');

	$app->post('api.coupon.available', 'CouponController@available');

    //管理日志
	$app->post('api.recommend.bonus.list', 'AffiliateController@index');
	$app->post('api.recommend.bonus.info', 'AffiliateController@info');

	$app->post('api.withdraw.submit', 'AccountController@submit');
	$app->post('api.withdraw.cancel', 'AccountController@cancel');
	$app->post('api.withdraw.list', 'AccountController@index');
	$app->post('api.withdraw.info', 'AccountController@getDetail');

	$app->post('api.balance.get', 'AccountController@surplus');
	$app->post('api.balance.list', 'AccountController@accountDetail');
});
