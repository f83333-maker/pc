<?php
declare(strict_types=1);

namespace App\Util;

use Kernel\Util\Session;

class Captcha
{
    
    public static function generate(string $sessionName)
    {
        $w = 50;
        $h = 24;
        $num = 4;
        $code = "";
        for ($i = 0; $i < $num; $i++) {
            $code .= rand(0, 9);
        }

        Session::set($sessionName, $code);
        
        Header("Content-type: image/PNG");
        $im = imagecreate($w, $h);
        $black = imagecolorallocate($im, 250, 133, 203);
        $gray = imagecolorallocate($im, 245, 248, 243);
        imagefill($im, 0, 0, $gray);

        imagerectangle($im, 0, 0, $w - 1, $h - 1, $black);

        $style = array(
            $black,
            $black,
            $black,
            $black,
            $black,
            $gray,
            $gray,
            $gray,
            $gray,
            $gray
        );
        
        $y1 = rand(0, $h);
        $y2 = rand(0, $h);
        $y3 = rand(0, $h);
        $y4 = rand(0, $h);
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

        

        
        $strx = rand(3, 8);
        
        for ($i = 0; $i < $num; $i++) {
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(8, 12);
        }

        imagepng($im);
        imagedestroy($im);
    }

    
    public static function check(int $code, string $sessionName): bool
    {
        if ($code == 0) {
            return false;
        }
        if (Session::get($sessionName) != $code) {
            return false;
        }
        return true;
    }

    public static function destroy(string $sessionName): void
    {
        Session::remove($sessionName);
    }
}