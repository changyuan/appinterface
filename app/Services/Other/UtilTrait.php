<?php

namespace App\Services\Other;

trait UtilTrait
{

    // 传多图
    public function uploadMorePics($params, $target_path, $one_more = 1)
    {
        $pic_arr = array();
        if (!empty($params)) {

            if (1 == $one_more) {
                $temp_count = strrpos($params['name'], ".");
                if (false !== $temp_count) {
                    $type = substr($params['name'], $temp_count);
                }
                if (!in_array($type, array('.gif', '.jpg', '.jpeg', '.png'))) {
                    return false;
                }
                $new_pic_name = date("YmdHis") . rand(100, 999) . $type;
                $pic_path     = $target_path . $new_pic_name;
                if (move_uploaded_file($params['tmp_name'], $pic_path)) {
                    $pic_arr[] = $this->uploadPicToYaolanCdn(realpath($pic_path));
                    @unlink(realpath($pic_path));
                }
            } else {
                foreach ($params['name'] as $key => $value) {
                    $temp_count = strrpos($value, ".");
                    if (false !== $temp_count) {
                        $type = substr($value, $temp_count);
                    }
                    if (!in_array($type, array('.gif', '.jpg', '.jpeg', '.png'))) {
                        return false;
                    }
                    $new_pic_name = date("YmdHis") . rand(100, 999) . $type;
                    $pic_path     = $target_path . $new_pic_name;
                    if (move_uploaded_file($params['tmp_name'][$key], $pic_path)) {
                        $pic_arr[$key] = $this->uploadPicToYaolanCdn(realpath($pic_path));
                        @unlink(realpath($pic_path));
                    }
                }
            }
        }
        return $pic_arr;
    }

    // 摇篮img_cdn
    public function uploadPicToYaolanCdn($file = '', $data = array("type" => "otmls", "makelogo" => "1"), $posturl = "http://photo.yaolan.com/insertPic.do")
    {
        $url = parse_url($posturl);
        if (!$url) {
            return "couldn't parse url";
        }

        if (!isset($url['port'])) {
            $url['port'] = "";
        }

        if (!isset($url['query'])) {
            $url['query'] = "";
        }

        $boundary   = "---------------------------" . substr(md5(rand(0, 32000)), 0, 10);
        $boundary_2 = "--$boundary";
        $content    = $encoded    = "";
        if ($data) {
            while (list($k, $v) = each($data)) {
                $encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"" . rawurlencode($k) . "\"\r\n\r\n";
                $encoded .= rawurlencode($v) . "\r\n";
            }
        }
        if ($file) {
            $ext  = strrchr($file, ".");
            $type = "image/jpeg";
            switch ($ext) {
                case '.gif':
                    $type = "image/gif";
                    break;
                case '.jpg':
                    $type = "image/jpeg";
                    break;
                case '.png':
                    $type = "image/png";
                    break;
            }
            $encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"file\"; filename=\"$file\"\r\nContent-Type: $type\r\n\r\n";
            $content = join("", file($file));
            $encoded .= $content . "\r\n";
        }

        $encoded .= "\r\n" . $boundary_2 . "--\r\n\r\n";
        $length = strlen($encoded);
        $fp     = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
        if (!$fp) {
            return "Failed to open socket to $url[host]";
        }

        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
        fputs($fp, "Host: $url[host]\r\n");
        fputs($fp, "Content-type: multipart/form-data; boundary=$boundary\r\n");
        fputs($fp, "Content-length: " . $length . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $encoded);
        $line = fgets($fp, 1024);

        if (!preg_match("/^HTTP\/1\.1 200/i", $line)) {
            return null;
        }

        $results  = "";
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\r\n" || $line == "\r\r\n")) {
                $inheader = 0;
            } elseif (!$inheader) {
                $results .= $line;
            }
        }
        fclose($fp);

        $xml = simplexml_load_string($results);

        return $xml;
    }

}
