<?php
declare(strict_types=1);

namespace Kernel\Context\Abstract;

use Kernel\Exception\JSONException;
use Kernel\Exception\NotFoundException;
use Kernel\Plugin\Const\Plugin as PGI;
use Kernel\Plugin\Const\Point;
use Kernel\Plugin\Plugin;
use Kernel\Plugin\Usr;
use Kernel\Util\Str;

abstract class File implements \Kernel\Context\Interface\File
{

    protected array $files = [];

    protected string $fileName;

    protected string $mime;

    
    protected string $tmp;

    
    protected int $error;

    protected int $size;

    
    protected string $suffix;

    
    protected string $name;

    public function __construct()
    {
        if (!isset($this->files[$this->name])) {
            throw new JSONException("没有任何文件被上传");
        }
        $file = $this->files[$this->name];
        $this->name = $file['name'];
        $this->mime = $file['type'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->tmp = $file['tmp_name'];
        $ext = (array)explode(".", $this->name);
        if (count($ext) < 2) {
            throw new JSONException("您的文件后缀无法识别，请选择其他文件在进行上传");
        }
        $this->suffix = end($ext);
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getMime(): string
    {
        return $this->mime;
    }

    public function getTmp(): string
    {
        return $this->tmp;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    
    public function save(string $path, array $ext = ['jpg', 'png', 'jpeg', 'bmp', 'webp', 'ico', 'gif', 'mp4', 'zip', 'woff', 'woff2', 'ttf', 'otf'], int $size = 10240, string $dir = BASE_PATH): string
    {
        if ($this->getError() > 0) {
            throw new JSONException("文件上传失败，代码：" . $this->getError(), $this->getError());
        }

        if (!in_array(strtolower($this->getSuffix()), $ext)) {
            throw new JSONException("您上传的文件类型不支持");
        }
        if ($size < $this->getSize() / 1024) {
            throw new JSONException("您的文件过大，当前上传限制：" . $size . "KB");
        }

        $_tmpDir = $dir . $path . date("Y-m-d/", time());
        $unique = $path . date("Y-m-d/") . Str::generateRandStr(32) . "." . $this->getSuffix();

        if ($hook = Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::HTTP_UPLOAD_SAVE_READY, PGI::HOOK_TYPE_PAGE, $this, $unique, $dir)) return $hook;

        if (!is_dir($_tmpDir)) {
            mkdir($_tmpDir, 0777, true);
        }

        if (!copy(from: $this->getTmp(), to: $dir . $unique)) {
            throw new JSONException("文件上传失败，服务器出错原因：{$path} 无写入权限");
        }

        if ($hook = Plugin::instance()->unsafeHook(Usr::inst()->getEnv(), Point::HTTP_UPLOAD_SAVE_COMPLETE, PGI::HOOK_TYPE_PAGE, $this, $unique, $dir)) return $hook;

        return $unique;
    }

}