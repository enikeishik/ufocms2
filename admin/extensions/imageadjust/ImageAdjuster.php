<?php
/**
 * @copyright
 */

namespace Ufocms\Backend\Extensions;

class ImageAdjuster
{
    use \Ufocms\Frontend\ToolsPath;
    
    /**
     * Права на загружаемые файлы.
     * @var int
     */
    const FILE_MODE = 0666;
    
    /**
     * Права на создаваемые папки.
     * @var int
     */
    const FOLDER_MODE = 0777;
    
    /**
     * Суфикс уменьшенных изображений.
     * @var string
     */
    const THUMBNAIL_SUFFIX = '_th';
    
    /**
     * Перезаписывать файл, если уже существует.
     */
    const OVERWRITE_THUMBNAIL = false;
    
    /**
     * Ссылка на объект конфигурации.
     * @var Config
     */
    protected $config = null;
    
    /**
     * Конструктор.
     */
    public function __construct(&$config)
    {
        $this->config =& $config;
    }
    
    /**
     * Проверка источника запроса.
     * @return boolean
     */
    protected function checkSource()
    {
        if (!isset($_GET['key'])) {
            return false;
        }
        return $_GET['key'] == $this->getKey();
    }
    
    /**
     * Возвращает временный ключ.
     * @return string
     */
    public function getKey()
    {
        return md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . date('Ymd'));
    }
    
    /**
     * Создание уменьшенного изображения из исходного.
     * @param array $image
     * @return string
     * @throws \Exception
     */
    public function adjust($image)
    {
        if (!$this->checkSource()) {
            throw new \Exception('Incorrect source');
        }
        
        /* DEBUG echo '<pre>'; var_dump($image); echo '</pre>'; */
        if (!$this->isPath($image['path'], false)) {
            throw new \Exception('Bad path');
        }
        $imagePath = $this->config->rootPath . $image['path'];
        $pathParts = pathinfo($imagePath);
        $dir = $pathParts['dirname'] . '/';
        $name = $pathParts['filename'];
        $ext = $pathParts['extension'];
        $thumbnailPath = $dir . $name . self::THUMBNAIL_SUFFIX . '.' . $ext;
        if (!self::OVERWRITE_THUMBNAIL && file_exists($thumbnailPath)) {
            throw new \Exception('Thumbnail already exists, overwrite forbidden');
        }
        
        $size = @getimagesize($imagePath);
        if (false === $size) {
            throw new \Exception('File not found');
        }
        $type = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        switch ($type) {
            case 'jpeg':
                $imgSrc = @imagecreatefromjpeg($imagePath);
                $imgDst = @imagecreatetruecolor($image['dstw'], $image['dsth']);
                break;
            case 'gif':
                $imgSrc = @imagecreatefromgif($imagePath);
                $imgDst = @imagecreate($image['dstw'], $image['dsth']);
                break;
            case 'png':
                $imgSrc = @imagecreatefrompng($imagePath);
                $imgDst = @imagecreatetruecolor($image['dstw'], $image['dsth']);
                break;
            default:
                throw new \Exception('File type «' . $type . '» not supported');
        }
        if (!$imgSrc || !$imgDst) {
            throw new \Exception('Image creation failed');
        }
        
        if (!@imagecopyresampled(
                $imgDst, 
                $imgSrc, 
                0, 
                0, 
                $image['srcx'], 
                $image['srcy'], 
                $image['dstw'], 
                $image['dsth'], 
                $image['srcw'], 
                $image['srch']
            )
        ) {
            throw new \Exception('Image resampling failed');
        }
        
        switch ($type) {
            case 'jpeg':
                if (!@imagejpeg($imgDst, $thumbnailPath, $image['jpegQquality'])) {
                    throw new \Exception('Function imagejpeg failed');
                }
                break;
            case 'gif':
                if (!@imagegif($imgDst, $thumbnailPath)) {
                    throw new \Exception('Function imagegif failed');
                }
                break;
            case 'png':
                if (!@imagepng($imgDst, $thumbnailPath)) {
                    throw new \Exception('Function imagepng failed');
                }
                break;
        }
        
        //устанавливаем права на файл
        @chmod($thumbnailPath, self::FILE_MODE);
        return substr($thumbnailPath, strlen($this->config->rootPath));
    }
}
