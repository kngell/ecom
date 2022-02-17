<?php

declare(strict_types=1);

class ImageManager
{
    protected $img_name;
    protected string $sourcePath;
    protected string $destinationPath;
    protected string $img;
    protected array $infos;

    /**
     * Init Image Manager
     * ============================================================================================.
     * @param string $img_file
     * @param string $source
     * @param string $destination
     * @return self
     */
    public function init(string $img_name = '', string $source = '', string $destination = '') : self
    {
        $this->img_name = $img_name;
        $this->img = $source != '' ? $source : $img_name;
        $this->sourcePath = dirname($this->img);
        $this->destinationPath = $destination == '' ? $this->sourcePath : $destination;
        if (exif_imagetype($this->img)) {
            $this->infos = getimagesize($this->img);
        }

        return $this;
    }

    public function get_infos()
    {
        return $this->infos;
    }

    public function resizeImage(string $width = '', string $height = '', bool $crop = false) : bool
    {
        if (!list($w, $h) = $this->infos) {
            return 'Unsupported image type!';
        }
        $type = strtolower(substr(strrchr($this->img_name, '.'), 1));
        if ($type == 'jpeg') {
            $type = 'jpg';
        }
        $sourceImg = $this->open_img();
        $width = $width != '' ? $width : $this->infos[0];
        $height = $height != '' ? $height : $this->infos[1];
        list($newImg, $x, $w, $h) = $this->resize($width, $height, $w, $h, $crop);
        if ($type == 'gif' or $type == 'png') {
            imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
            imagealphablending($newImg, false);
            imagesavealpha($newImg, true);
        }
        imagecopyresampled($newImg, $sourceImg, 0, 0, $x, 0, $width, $height, $w, $h);
        $result = $this->save_image($newImg);
        $this->destroyImage($sourceImg);
        $this->destroyImage($newImg);

        return $result;
    }

    public function cropImage(string $width = '', string $height = '') : bool
    {
        $sourceImg = $this->open_img();
        $newImg = imagecrop($sourceImg, [
            'x' => 0,
            'y' => 0,
            'width' => $width != '' ? $width : $this->infos[0],
            'height' => $height != '' ? $height : $this->infos[1],
        ]);
        $result = $this->save_image($newImg);
        $this->destroyImage($newImg);

        return $result;
    }

    public function RotateImage(int $rotang = 0) : bool
    {
        $sourceImg = $this->open_img();
        imagealphablending($sourceImg, false);
        imagesavealpha($sourceImg, true);
        $newImg = imagerotate($sourceImg, $rotang, imagecolorallocatealpha($sourceImg, 0, 0, 0, 127));
        $result = $this->save_image($newImg);
        $this->destroyImage($newImg);
        $this->destroyImage($sourceImg);

        return $result;
    }

    //=======================================================================
    //Image Destroy
    //=======================================================================
    public function destroyImage(?GdImage &$img = null)
    {
        if (isset($img)) {
            return imagedestroy($img);
        }

        return true;
    }

    //=======================================================================
    //Get Assets
    //=======================================================================
    public static function asset_img($img = '')
    {
        return ASSET_SERVICE_PROVIDER ? ASSET_SERVICE_PROVIDER . US . IMG . $img : IMG . $img;
    }

    private function save_image(GdImage $newImg)
    {
        if (!file_exists(dirname($this->destinationPath . DS . $this->img_name))) {
            mkdir(dirname($this->destinationPath), 0777, true);
        } else {
            chmod($this->destinationPath, 0777);
        }
        if (isset($this->infos) && !file_exists($this->destinationPath . DS . $this->img_name)) {
            switch ($this->infos['mime']) {
            case 'image/png':
                return imagepng($newImg, $this->destinationPath . DS . $this->img_name);
                break;
            case 'image/jpeg':
                return imagejpeg($newImg, $this->destinationPath . DS . $this->img_name);
                break;
            case 'image/gif':
                return imagegif($newImg, $this->destinationPath . DS . $this->img_name);
                break;
            default:
                return false;
                break;
            }
        }
    }

    private function resize(int $width, int $height, int $w, int $h, bool $crop = false) : array
    {
        if ($crop) {
            if ($w < $width or $h < $height) {
                return 'Picture is too small!';
            }
            $ratio = max($width / $w, $height / $h);
            $h = $height / $ratio;
            $x = ($w - $width / $ratio) / 2;
            $w = $width / $ratio;
        } else {
            if ($w < $width and $h < $height) {
                return 'Picture is too small!';
            }
            $ratio = min($width / $w, $height / $h);
            $width = $w * $ratio;
            $height = $h * $ratio;
            $x = 0;
        }

        return [imagecreatetruecolor($width, $height), $x, $w, $h];
    }

    private function open_img()
    {
        if (isset($this->infos)) {
            switch ($this->infos['mime']) {
            case 'image/png':
                return imagecreatefrompng($this->img);
                break;
            case 'image/jpeg':
                return imagecreatefromjpeg($this->img);
                break;
            case 'image/gif':
                return imagecreatefromgif($this->img);
                break;
            default:
                return false;
                break;
            }
        }
    }
}