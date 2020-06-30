<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午6:08
 */
namespace App\Lib\Upload;

use App\Lib\AliyunSdk\AliVod;
use App\Lib\Utils;

abstract class Base {

  public $type;
  public $size;
  public $maxSize;
  public $clientFileName;
  public $clientMediaType;
  public $extensionTypes = ['mp4', 'flv'];
  public $fileName;
  private $request;

  public function __construct($request)
  {
    $this->request = $request;
  }

  public function upload()
  {
    $files = $this->request->getUploadedFile($this->type);
    $this->size = $files->getSize();
    $this->checkSize();
    $this->clientFileName = $files->getClientFileName();
    $this->clientMediaType = $files->getClientMediaType();
    $this->checkMediaType();
//    $file = $this->getFile();
//    $files->moveTo($file);

    $aliObj = new AliVod();
    $result = $aliObj->createUploadVideo($this->type, $this->clientFileName);
    $uploadAuth = json_decode(base64_decode($result->UploadAuth), true);
    $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);
    $aliObj->initOssClient($uploadAuth, $uploadAddress);
    $aliObj->uploadLocalFile($uploadAddress, $files->getTempName());
    return $result->VideoId;
  }

  public function checkSize() {
    if (empty($this->size) || $this->size > $this->maxSize) {
      throw new \Exception('file size is illegal');
    }
  }

  public function checkMediaType() {
    $clientMediaType = explode('/', $this->clientMediaType);
    $clientMediaType = $clientMediaType[1] ?? '';
    if (empty($clientMediaType) || !in_array($clientMediaType, $this->extensionTypes)) {
      throw new \Exception('上传文件不合法 ' . $clientMediaType);
    }
  }

  public function getFile() {
    $pathInfo = pathinfo($this->clientFileName);
    $extension = $pathInfo['extension'];

    $baseDir = EASYSWOOLE_ROOT . '/webroot';
    $fileDir = '/' . $this->type . '/' . date('Y') . '/' . date('m');
    if (!is_dir($baseDir . $fileDir)) {
      mkdir($baseDir . $fileDir, 0777, true);
    }
    $newFileName = Utils::getFileKey($this->clientFileName) . '.'. $extension;
    $this->fileName = $fileDir . '/' . $newFileName;
    return $baseDir . $this->fileName;
  }

}
