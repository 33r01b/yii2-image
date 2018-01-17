<?php

namespace zeroonebeatz\image;

use yii\helpers\FileHelper;

class Path
{
  private $uploadFolder = 'uploads';

  public function absolute()
  {
    return Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $this->uploadFolder;
  }

  public function web()
  {
    return Yii::getAlias('@web') . DIRECTORY_SEPARATOR . $this->uploadFolder;
  }

  public function getFolder($folderName)
  {
    $folder = $this->absolute() . DIRECTORY_SEPARATOR . $folderName;

    if(!is_dir($folder)){
      FileHelper::createDirectory($folder);
    }

    return $folder;
  }

  public function getUploadFolder()
  {
    return $this->uploadFolder;
  }
}
