<?php

namespace App\Services\Other;

trait McryptTrait
{

    private static $key = "nyw6euajYeDEElcA5I3ncQbi8uM0Wsi30T06x08puwE=";
    private static $iv  = "y5v8s/N6PHurb/tqcwt4uw==";

    /**
     * cookie解密
     * @author zhaozhongyi
     * $encryptedData 二进制的密文;
     */
    public static function Decrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            return $encryptedData;
        }
        $encryptedData = base64_decode($encryptedData);

        $keyv = base64_decode(self::$key);
        $ivv  = base64_decode(self::$iv);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $keyv, $encryptedData, MCRYPT_MODE_CBC, $ivv);
        $data = self::stripPKSC7Padding($data);
        return $data;
    }

    /**
     * cookie加密
     * @author zhaozhongyi
     * $encryptedData 需加密字符;
     */
    public static function Ecrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            return $encryptedData;
        }
        $encryptedText = self::paddingPKCS7($encryptedData);
        $keyv          = base64_decode(self::$key);
        $ivv           = base64_decode(self::$iv);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $keyv, $encryptedText, MCRYPT_MODE_CBC, $ivv));
    }

    /**
     * PKSC7解密算法
     */
    private static function stripPKSC7Padding($string)
    {
        if (empty($string)) {
            return $string;
        }
        $slast  = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    /**
     * PKSC7加密算法
     */
    private static function paddingPKCS7($data)
    {

        if (empty($data)) {
            return $data;
        }

        $block_size   = mcrypt_get_block_size('rijndael-128', 'cbc');
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    public static function getSign($data, $key)
    {
        if (empty($data)) {
            return array();
        }
        $paramArr   = $data;
        $nonceStr   = self::createNonceStr();
        $paramArr[] = $nonceStr;
        sort($paramArr, SORT_NATURAL);
        $str = implode('|', $paramArr);
        $str .= '|' . $key;
        $sign  = md5($str);
        $token = substr($sign, 3, 10);
        return ['nonceStr' => $nonceStr, 'sign' => $token];
    }

    private static function createNonceStr()
    {
        $str  = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return md5(time() . $uuid);
    }

}
