<?php

declare(strict_types=1);

class UploaderFactory
{
    private ContainerInterface $container;

    public function __construct(private array $filesAry = [])
    {
    }

    public function create(Model $model) : array
    {
        $uploaders = [];
        $paths = [];
        foreach ($this->filesAry as $fileAry) {
            if (empty($fileAry['name'])) {
                $mediakey = $model->getEntity()->getFieldWithDoc('media');
                if ($mediakey == 'profileImage') {
                    $paths[] = 'users' . US . 'avatar.png';
                }
            }
            $fileType = $this->content_type($fileAry);
            if ($fileType != '') {
                $rv = match (true) {
                    str_contains($fileType, 'image') => $uploaders[] = $this->container->make(UploaderInterface::class, [
                        'fm' => $this->container->make(ImageManager::class, [
                            'imgAry' => $fileAry,
                            'sourcePath' => $fileAry['tmp_name'],
                            'destination' => $model->getTableName(),
                        ]),
                        'fileSytem' => $this->container->make(FilesSystemInterface::class),
                        'model' => $model,
                    ]),
                };
            }
        }
        return [$uploaders, $paths];
    }

    private function content_type(array $fileAry)
    {
        if (!function_exists('mime_content_type')) {
            $mime_types = [

                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            ];
            $r = explode('.', $fileAry['name']);
            $ext = strtolower(array_pop(explode('.', $fileAry['name'])));
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            }
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $fileAry['name']);
                finfo_close($finfo);
                return $mimetype;
            } else {
                return 'application/octet-stream';
            }
        }
        return !empty($fileAry['tmp_name']) ? mime_content_type($fileAry['tmp_name']) : '';
    }
}