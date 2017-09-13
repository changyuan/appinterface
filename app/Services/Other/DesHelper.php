<?php
namespace App\Services\Other;

class DesHelper {
	/**
	 *加密函数
	 *@para input 待加密内容
	 *@key 对称密钥
	 * @param $isEncode,返回的结果是否再次转为64位
	 */
	public static function des_encrypt($input, $key, $isEncode = false) {
		$size = mcrypt_get_block_size('des', 'ecb');
		$input = self::pkcs5_pad($input, $size);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		if ($isEncode) {
			$data = self::SetToHexString($data);
		}
		return $data;
	}

	/**
	 *解密函数
	 *@para input 待解密内容
	 *@key 对称密钥
	 * @param $isEncode,返回的结果是否再次转为64位
	 */
	public static function des_decrypt($encrypted, $key, $isEncode = false) {
		if ($isEncode) {
			$encrypted = self::UnsetFromHexString($encrypted);
		}
		$encrypted = base64_decode($encrypted);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		//使用MCRYPT_DES算法,cbc模式

		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		//初始处理
		$decrypted = mdecrypt_generic($td, $encrypted);
		//解密
		mcrypt_generic_deinit($td);
		//结束
		mcrypt_module_close($td);
		$y = self::pkcs5_unpad($decrypted);
		return $y;
	}

	public static function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}

	public static function pkcs5_unpad($text) {
		$pad = ord($text{strlen($text) - 1});
		if ($pad > strlen($text))
			return false;
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
			return false;
		return substr($text, 0, -1 * $pad);
	}



	private static function SingleDecToHex($dec) {
		$tmp = "";
		$dec = $dec % 16;
		if ($dec < 10)
			return $tmp . $dec;
		$arr = array("a", "b", "c", "d", "e", "f");
		return $tmp . $arr[$dec - 10];
	}

	private static function SingleHexToDec($hex) {
		$v = ord($hex);
		if (47 < $v & $v < 58)
			return $v - 48;
		if (96 < $v & $v < 103)
			return $v - 87;
	}

	/**
	 * 字符串变成16进制
	 */
	private static function SetToHexString($str) {
		if (!$str)
			return false;
		$tmp = "";
		for ($i = 0; $i < strlen($str); $i++) {
			$ord = ord($str[$i]);
			$tmp .= self::SingleDecToHex(($ord - $ord % 16) / 16);
			$tmp .= self::SingleDecToHex($ord % 16);
		}
		return $tmp;
	}
	/**
	 * 16进制变成字符串
	 */
	private static function UnsetFromHexString($str) {
		if (!$str)
			return false;
		$tmp = "";
		for ($i = 0; $i < strlen($str); $i += 2) {
			$tmp .= chr(self::SingleHexToDec(substr($str, $i, 1)) * 16 + self::SingleHexToDec(substr($str, $i + 1, 1)));
		}
		return $tmp;
	}

}
