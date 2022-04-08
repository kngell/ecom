<?php

declare(strict_types=1);
class AssetsController extends Controller
{
    public function download($file)
    {
        // if ($this->request->exists('post')) {
        $file = basename($this->request->get('url'));
        $filePath = FILES . 'download' . DS . $file;
        if (!empty($file) && file_exists($filePath)) {
            //Define Headers
            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $file . '');
            header('Content-Transfer-Encoding: binary');
            header('Content-Type: binary/octet-stream');

            readfile($filePath);
            exit;
        }
        // }
    }

    public function upload($data)
    {
        $filename = array_pop($data);
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $pathRoot = in_array($file_ext, ['js', 'css']) ? LAZYLOAD_ROOT : UPLOAD_ROOT;
        $path = array_pop($data);
        $fileToGet = empty($path) ? $pathRoot . $filename : $pathRoot . $path . DS . $filename;
        if (!empty($filename) && file_exists($fileToGet) && !in_array($file_ext, ['png', 'jpg', 'jpeg', 'gif'])) {
            header('Content-Type: binary/octet-stream');
            header('Content-Length: ' . filesize($fileToGet));
            readfile($fileToGet);
            exit;
        }
    }

    public function getAsset(array $data)
    {
        $file = array_pop($data);
        $path = array_pop($data);
        $path1 = '';
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $path1 .= $v . DS;
            }
        }
        $path = $path1 . $path;
        $fileToGet = '';
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        switch (true) {
            case in_array($ext, ['png', 'ico', 'jpg']):
                $fileToGet = empty($path) ? IMAGE_ROOT . $file : IMAGE_ROOT . $path . DS . $file;
                $type = mime_content_type($fileToGet);
                break;
            default:
                $fileToGet = empty($path) ? ASSET . $file : ASSET . $path . DS . $file;
                $type = 'application/x-font-ttf';
                break;
        }
        return $this->read_asset($fileToGet, $type);
    }

    public function acme($data)
    {
        $filename = array_pop($data);
        $fileToGet = ACME_ROOT . $filename;
        if (!empty($filename) && file_exists($fileToGet)) {
            header('Content-Type: binary/octet-stream');
            header('Content-Length: ' . filesize($fileToGet));
            readfile($fileToGet);
            exit;
        }
    }

    public function dropzoneupload()
    {
    }

    private function read_asset($fileToGet, $type)
    {
        if (!empty($fileToGet) && file_exists($fileToGet)) {
            header('Content-type: ' . $type);
            header('Content-Length: ' . filesize($fileToGet));

            $handle = fopen($fileToGet, 'rb');
            $buffer = '';
            while (!feof($handle)) {
                $buffer = fread($handle, 4096);
                echo $buffer;
                ob_flush();
                flush();
            }
            fclose($handle);
            // echo file_get_contents($fileToGet);
            // readfile($fileToGet);
            exit;
        }
    }
}