<?php

namespace zeroonebeatz\image;

use yii\imagine\Image as Imagine;
use yii\web\UploadedFile;

class File
{
  const CUT_NAME_PATTERN = '/([\w\d]+[\/])|([\.]+[\w\d]+)/i';

  private $path;
  private $dir;
  private $image;

  public function __construct($dir)
  {
    $this->path = new Path();
    $this->dir = $dir;
  }

  public function upload($file)
  {
    if ($file && $file instanceof UploadedFile) {
      $this->image = $file;
      $fileName = $this->generateFileName();
      $file->saveAs($this->path->getFolder($this->dir) . DIRECTORY_SEPARATOR . $fileName);
      return $this->dir . DIRECTORY_SEPARATOR . $fileName;
    } else {
        return false;
    }
  }

  private function generateFileName()
  {
      return strtolower(
        md5(uniqid($this->image->baseName)) . DIRECTORY_SEPARATOR . $this->image->extension
      );
  }

  public function delete($fileName)
  {
    $folder = $this->path->absolute() . DIRECTORY_SEPARATOR;
    @unlink($folder . $fileName);

    //delete thumbs
    $imageName = preg_replace(self::CUT_NAME_PATTERN, '', $fileName);
    $thumbs = $folder . 'thumbs' . DIRECTORY_SEPARATOR . $imageName . '-*';
    array_map('unlink', glob($thumbs));
  }

  public function thumb($filename, $width = null, $height = null, $crop = true) //TODO разбить на части, передать создание файлов и директорий
  {
    if ($filename && is_file($filename = ($this->path->absolute() . DIRECTORY_SEPARATOR . $filename))) {
      $info = pathinfo($filename);

      $thumbName = sprintf('%s-%s.%s',
          $info['filename'],
          md5(@filemtime($filename) . (int)$width . (int)$height . (int)$crop),
          $info['extension']
      );

      $thumbFile = $this->path->absolute() . '/thumbs/' . $thumbName;
      $thumbWebFile = $this->path->web() . '/thumbs/' . $thumbName;

      if (file_exists($thumbFile)) {
          return $thumbWebFile;
      } elseif (FileHelper::createDirectory(dirname($thumbFile), 0777) &&
          Imagine::thumbnail($filename, $width, $height)->save($thumbFile , ['quality' => 90])
      ) {
          return $thumbWebFile;
      }
    }
    return '';
  }

  public function uploadPath($path)
  {
    return DIRECTORY_SEPARATOR . $this->path->getUploadFolder() . DIRECTORY_SEPARATOR . $path;
  }
}
