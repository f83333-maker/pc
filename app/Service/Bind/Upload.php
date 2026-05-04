<?php
declare(strict_types=1);

namespace App\Service\Bind;

use Kernel\Util\Date;
use Kernel\Util\File;

class Upload implements \App\Service\Upload
{

    public function add(string $path, string $type, ?int $userId = null): void
    {
        if (!is_file(BASE_PATH . $path)) {
            return;
        }
        $upload = new \App\Model\Upload();
        $upload->hash = md5_file(BASE_PATH . $path);
        $upload->type = $type;
        $upload->path = $path;
        $upload->create_time = Date::current();
        $userId && ($upload->user_id = $userId);
        $upload->save();
    }

    public function get(string $hash): ?string
    {
        return (\App\Model\Upload::query()->where("hash", $hash)->first())?->path;
    }

    public function remove(string $path): void
    {
        if (!is_file(BASE_PATH . $path)) {
            return;
        }

        $hash = md5_file(BASE_PATH . $path);
        \App\Model\Upload::query()->where("hash", $hash)->delete(); 
        File::remove(BASE_PATH . $path);
    }

    public function handle($upload, $dir, $type, int $size = 10000, string $fileName = ''): mixed
    {
        if (!is_array($upload)) {
            return "请选择文件";
        }

        if (count($upload) == count($upload, 1)) {
            $load = self::error($upload, $type, $size);
            if (is_array($load)) {

                return self::move($load, $dir, $fileName);
            } else {
                return $load;
            }
        } else {

            $list = array();

            for ($i = 0; $i < count($upload); $i++) {

                $load = self::error($upload[$i], $type, $size);
                if (is_array($load)) {

                    $move = self::move($load, $dir, $fileName);

                    if (is_array($move)) {
                        $list[] = $move;
                    }
                }

            }
            return $list;
        }
    }

    private static function error($upload, $type, $size)
    {

        if ($upload['error'] > 0) {
            switch ($upload['error']) {
                case 1:
                    $err_info = "文件上传失败";
                    break;
                case 2:
                    $err_info = "文件太大,无法上传";
                    break;
                case 3:
                    $err_info = "上传失败,文件可能损坏";
                    break;
                case 4:
                    $err_info = "上传失败,请选择需要上传的文件";
                    break;
                case 6:
                    $err_info = "上传失败,无写入权限";
                    break;
                case 7:
                    $err_info = "上传失败,文件写入失败";
                    break;
                default:
                    $err_info = "未知的上传错误";
                    break;
            }
            return $err_info;
        }

        $exp = explode(".", (string)$upload['name']);

        if (count($exp) < 2) return "文件无后缀无法识别";

        $fix = $exp[count($exp) - 1];
        if (!in_array(strtolower($fix), $type)) return '不支持该后缀的文件:' . $type;

        $upload_size = $upload['size'] / 1024;
        if ($upload_size > $size) return '文件太大';

        return array('tmp' => $upload['tmp_name'], 'size' => $upload_size, 'name' => $upload['name'], 'fix' => $fix);
    }

    private static function move($array, $dir, $file_name)
    {

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $names = date("YmdHis") . mt_rand(1000000, 9999999) . '.' . $array['fix'];
        if ($file_name != '') {
            $uniqueName = $dir . '/' . $file_name;
        } else {

            $uniqueName = $dir . '/' . $names;
        }
        if (move_uploaded_file($array['tmp'], $uniqueName)) {
            return array('dir' => $uniqueName, 'size' => $array['size'], 'name' => $array['name'], 'new_name' => $names, 'ext' => $array['fix']);
        } else {
            return '文件上传失败';
        }
    }
}