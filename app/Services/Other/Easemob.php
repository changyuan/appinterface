<?php
/**
 * 注册到环信即时通 通用类
 * 所有默认注册和调用的环信为except，当调用changeConfig方法后会修改默认配置，如果再次调用默认的except，注意再次调用changeConfig;
 * Class Message
 */
namespace App\Services\Other;

use Illuminate\Support\Facades\Redis;
class Easemob
{
    private static $baseUrl = 'https://a1.easemob.com/';

    /*以下为正式环境使用*/
    private static $orgName     = 'yaolanwang';
    private static $appName     = 'expect';
    private static $appPassword = 'expect_app';
    private static $appClientId = 'YXA6nACisP3bEeSzTuvZ0YXL4A';
    private static $appSecret   = 'YXA6szuwLzL2tyiR_JNo_K9BsbJ8QiY';

    private static $exceptionName = 'easemob';
    private static $emailTitle    = "调取环信接口出现发生错误";

    private static $interface = array(
        'getToken'           => 'token', //获取TOKEN，POST
        'regist'             => 'users', //注册用户，POST
        'createChatrooms'    => 'chatrooms', //创建聊天室，POST
        'deleteChatrooms'    => 'chatrooms/{chatroom_id}', //删除某个聊天室，DELETE
        'getAllChatrooms'    => 'chatrooms', //获取当前应用下所有聊天室，GET
        'getChatroomsDetail' => 'chatrooms/{chatroom_id}', //获取某个聊天室详情，GET
        'updateChatrooms'    => 'chatrooms/{chatroom_id}', //修改聊天室信息，PUT
        'addUserToRoom'      => 'chatrooms/{chatroom_id}/users/{username}', //向指定聊天室添加用户，POST
        'removeUserFromRoom' => 'chatrooms/{chatroom_id}/users/{username}', //从指定聊天室删除用户，DELETE
        'getUserRooms'       => 'users/{username}/joined_chatrooms/', //获取某一用户加入的聊天室，GET
        'sendMessage'        => 'messages', //获取某一用户加入的聊天室，POST
        'getMessageHistory'  => 'chatmessages', //获取聊天历史
    );

    private static $token = null;

    /**
     * 获取url
     * @param $fn
     * @return string
     */
    private static function getInterface($fn)
    {
        return $getInterface = self::$baseUrl . self::$orgName . '/' . self::$appName . '/' . self::$interface[$fn];
    }

    /**
     * 获取token
     * 此处返回的token为组装好的header
     * 此方法直接调用webHelper
     * @param bool|false $refresh
     * @return null|string
     */
    private static function getToken()
    {
        $cacheToken = Redis::get($key);
        if (empty($cacheToken)) {
            $data = array(
                "grant_type"    => 'client_credentials',
                "client_id"     => self::$appClientId,
                "client_secret" => self::$appSecret,
            );
            $returnData  = self::urlRequest(self::getInterface(__FUNCTION__), 'post', json_encode($data), array('Content-Type”:”application/json'));
            $token_arr   = json_decode($returnData, true);
            self::$token = isset($token_arr['access_token']) ? "Authorization: Bearer " . $token_arr['access_token'] : '';
            if (!empty(self::$token)) {
//缓存取得token有效时间 减去 3600秒
                // $redis->set($key, self::$token, 'EX', intval($token_arr['expires_in']) - 3600);
                Redis::set($key, self::$token, 'EX', intval($token_arr['expires_in']) - 3600);
            }
        } else {
            self::$token = $cacheToken;
        }
        return self::$token;
    }

    /**
     * 发送网络请求
     * @param string $url
     * @param array $params
     * @param string $method
     * @param array $header
     * @param int $httpCode
     * @return string|array
     */
    private static function urlRequest($url, $method = 'POST', $params = array(), $header = array(), &$httpCode = 0)
    {
        $timeout        = 4;
        $connectTimeOut = 3;
        $curl           = curl_init(); // 启动一个CURL会话

        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        // curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_HEADER, 0); // 是否显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //设置请求头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查

        $type = strtoupper($method);
        switch ($type) {
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
                break;
        }

        $output = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            var_dump(curl_error($curl));
        }
        //获取返回数据的状态码
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl); // 关键CURL会话
        return $output; // 返回数据
    }

    /**
     * 对聊天室进行操作   获取聊天室详情，修改聊天室信息，删除聊天室
     * @param string $name 聊天室名称
     * @param string $description 聊天室描述
     * @param string $owner 聊天室管理员 必须是string类型
     * @param int $maxusers 聊天室成员最大数，默认选最大5000
     * @param array $members 聊天室成员，为可选，每个元素必须为string类型
     * @return mixed
     */
    private static function operagteChatrooms($chatroom_id, $url, $type = 'get', $data = array())
    {
        $result = array('code' => -1, 'msg' => '缺少参数');
        if (empty($chatroom_id) || !intval($chatroom_id)) {
            return $result;
        }
        $operate = 'GET';
        switch ($type) {
            case 'update':
                $operate = 'PUT';
                break;
            case 'delete':
                $operate = 'DELETE';
                break;
        }
        $url = str_replace('{chatroom_id}', $chatroom_id, $url);
        $res = self::urlRequest($url, $operate, json_encode($data), array('Content-Type : application/json', self::getToken()));

        $res = json_decode($res, true);
        if (isset($res['error'])) {
            // Yii::error("操作聊天室异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,$operate."操作聊天室异常：".$res);
            $result = array('code' => -1, 'msg' => $res['error_description']);
        } else {
            $result = array('code' => 1, 'msg' => $res['data']);
        }
        return $result;
    }

    /**
     * 向聊天室发送基本消息，暂不支持图片、语音、位置
     * 随便输入均返回成功
     * @param array $target 聊天室id 必须是数组
     * @param string $msg 发送的数据 Type：txt => msg为发送的文本， Type:cmd => 透传的事件 ， 其余=>上传后获取的相应数据，
     * @param $from 发送人
     * @param string $type txt，img，audio，video，cmd
     * @param array $ext 扩展字段
     * @return mixed
     */
    public static function sendMessage($target, $target_type = 'chatrooms', $msg, $from, $type = 'txt', $ext = array())
    {
        if (!is_array($target)) {
            return false;
        }
        $data = array(
            "target_type" => $target_type,
            "target"      => $target,
            "msg"         => array(
                'type' => $type,
            ),
            "from"        => $from,
            'ext'         => $ext,
        );

        //如果没有扩展信息，去除该选项
        if (empty($ext) || !is_array($ext)) {
            unset($data['ext']);
        }

        if ($type == 'txt') {
//发送文本或者透传消息
            $data['msg']['msg'] = $msg;
        } elseif ($type == 'cmd') {
            $data['msg']['action'] = $msg;
        } elseif (is_array($msg)) {
            $data['msg'] = array_merge($data['msg'], $msg);
        }

        $res = self::urlRequest(
            self::getInterface(__FUNCTION__), 'post',
            json_encode($data),
            array('Content-Type : application/json', self::getToken())
        );

        $res          = json_decode($res, true);
        $successCount = 0;
        if ($res['data']) {
            foreach ($res['data'] as $key => $val) {
                if ('success' === $val) {
                    $successCount++;
                }
            }

        }
        $result = $successCount === 0 ? false : true;
        if ($successCount !== count($target)) {
            // Yii::error("发送环信消息异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"发送环信消息异常：".$res);
        }
        return $result;
    }

    /**
     * 创建聊天室
     * @param string $name 聊天室名称
     * @param string $description 聊天室描述
     * @param string $owner 聊天室管理员 必须是string类型
     * @param int $maxusers 聊天室成员最大数，默认选最大5000
     * @param array $members 聊天室成员，为可选，每个元素必须为string类型
     * @return mixed
     */
    public static function createChatrooms($name, $description, $owner, $maxusers = 5000, $members = array())
    {
        $result = array('code' => -1, 'msg' => '缺少参数');
        if (empty($name) || empty($description) || empty($owner)) {
            return $result;
        }

        $data = array(
            'name'        => strval($name),
            'description' => strval($description),
            'owner'       => strval($owner),
            'members'     => $members,
            'maxusers'    => $maxusers,
        );

        $res = self::urlRequest(
            self::getInterface(__FUNCTION__), 'post',
            json_encode($data),
            array('Content-Type : application/json', self::getToken())
        );

        $res = json_decode($res, true);
        if (isset($res['error'])) {
            // Yii::error("创建聊天室异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"创建聊天室异常：".$res);
            $result = array('code' => -1, 'msg' => $res['error_description']);
        } else {
            $result = array('code' => 1, 'msg' => $res['data']['id']);
        }
        return $result;
    }

    /**
     * 删除聊天室
     * @param string $name 聊天室ID
     * @return mixed
     */
    public static function deleteChatrooms($chatroom_id)
    {
        return self::operagteChatrooms($chatroom_id, self::getInterface(__FUNCTION__), 'delete');
    }

    /**
     * 修改聊天室信息
     * @param string $name 聊天室ID
     * @return mixed
     */
    public static function updateChatrooms($chatroom_id, $data)
    {
        return self::operagteChatrooms($chatroom_id, self::getInterface(__FUNCTION__), 'update', $data);
    }

    /**
     * 获取聊天室详情
     * @param string $chatroom_id 聊天室ID
     * @return mixed
     */
    public static function getChatroomsDetail($chatroom_id)
    {
        return self::operagteChatrooms($chatroom_id, self::getInterface(__FUNCTION__));
    }

    /**
     * 添加聊天室成员
     * @param string $chatroom_id 聊天ID
     * @param string|array 要添加的用户名，为string类型，多个时为array，其中每个元素为string类型
     * @return mixed
     */
    public static function addUserToRoom($chatroom_id, $username)
    {
        $result = array('code' => -1, 'msg' => '缺少参数');
        if (empty($username) || empty($chatroom_id)) {
            return $result;
        }
        $data = array();
        $url  = str_replace('{chatroom_id}', $chatroom_id, self::getInterface(__FUNCTION__));
        if (is_array($username)) {
            $url               = str_replace('{username}', '', $url);
            $data['usernames'] = $username;
        } else {
            $url = str_replace('{username}', $username, $url);
        }

        $res = self::urlRequest($url, 'post', json_encode($data), array('Content-Type : application/json', self::getToken())
        );

        $res = json_decode($res, true);
        if (isset($res['error'])) {
            // Yii::error("添加聊天室成员异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"添加聊天室成员异常：".$res);
            $result = array('code' => -1, 'msg' => $res['error_description']);
        } else {
            $result = array('code' => 1, 'msg' => $res['data']);
        }
        return $result;
    }

    /**
     * 删除聊天室成员
     * @param string $chatroom_id 聊天室ID
     * @param string|array 要删除的用户名，为string类型，多个时为array，其中每个元素为string类型
     * @return mixed
     */
    public static function removeUserFromRoom($chatroom_id, $username)
    {
        $result = array('code' => -1, 'msg' => '缺少参数');
        if (empty($username) || empty($chatroom_id)) {
            return $result;
        }
        $data = array();
        $url  = str_replace('{chatroom_id}', $chatroom_id, self::getInterface(__FUNCTION__));
        if (is_array($username)) {
            $tempUser = implode(',', $username);
            $url      = str_replace('{username}', $tempUser, $url);
        } else {
            $url = str_replace('{username}', $username, $url);
        }

        $res = self::urlRequest($url, 'delete', json_encode($data), array('Content-Type : application/json', self::getToken())
        );

        $res = json_decode($res, true);
        if (isset($res['error'])) {
            // Yii::error("删除聊天室成员异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"删除聊天室成员异常：".$res);
            $result = array('code' => -1, 'msg' => $res['error_description']);
        } else {
            $result = array('code' => 1, 'msg' => $res['data']);
        }
        return $result;
    }

    /**
     * 获取APP下所有聊天室
     * @return mixed
     */
    public static function getAllChatrooms($pageNum = 1, $pageSize = 20)
    {
        $url = self::getInterface(__FUNCTION__) . '?pagenum=' . $pageNum . '&pagesize=' . $pageSize;
        $res = self::urlRequest($url, 'get', '', array('Content-Type : application/json', self::getToken()));

        $res = json_decode($res, true);
        if (isset($res['error'])) {
            // Yii::error("获取APP下所有聊天室异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"获取APP下所有聊天室异常：".$res);
            $result = array('code' => -1, 'msg' => $res['error_description']);
        } else {
            $result = array('code' => 1, 'msg' => $res['data']);
        }
        return $result;
    }

    //获取聊天历史
    public static function getMessageHistory($time)
    {
        $ql       = urlencode('select * where timestamp>' . $time);
        $url      = self::getInterface(__FUNCTION__) . '?ql=' . $ql . '&limit=500';
        $res      = self::urlRequest($url, 'get', '', array('Content-Type : application/json', self::getToken()));
        $tempData = json_decode($res, true);
        if (isset($tempData['error'])) {
            // Yii::error("获取环信聊天记录发生异常:".$res , self::$exceptionName);
            // Email::sendEmail(self::$emailTitle,"获取环信聊天记录发生异常：".$res);
        }
        return $res;
    }

    //发送透传消息，事件名暂时定义以下几个 gag=禁言，updateCount=更新数（点赞数、在线数、虚拟数等），removeUser踢人
    public static function sendCmd($target, $action, $data)
    {
        $actionArray = ['gag', 'removeUser', 'updateCount'];
        if (!in_array($action, $actionArray)) {
            return false;
        } else {
            return self::sendMessage(array(strval($target)), 'chatrooms', $action, 'admin', 'cmd', $data);
        }
    }
}
