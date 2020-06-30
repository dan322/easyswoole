<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午5:52
 */
namespace App\HttpController\Api;

use App\Lib\AliyunSdk\AliVod;
use App\Lib\ClassArr;

class Upload extends Base
{

  public function file() {
    $request = $this->request();
    $files = $request->getSwooleRequest()->files;
    $type = array_keys($files)[0];
    if (empty($type)) {
      return $this->writeJson(400, [], 'params is illegal');
    }
    try {
      $classObj = new ClassArr();
      $upload = $classObj->initClass($type, 'uploadClassStat', [$request]);
      $result = $upload->upload();
      if (!$result)
        return $this->writeJson(400, [], 'upload failed');
    } catch (\Exception $e) {
        return $this->writeJson(400, [], $e->getMessage());
    }
    return $this->writeJson(200, ['file' => $result], 'upload success');
  }

  public function testAli() {
    $aliObj = new AliVod();
    $result = $aliObj->createUploadVideo('搞笑', 'video1.mp4');
    $uploadAuth = json_decode(base64_decode($result->UploadAuth), true);
    $uploadAddress = json_decode(base64_decode($result->UploadAddress), true);
    $aliObj->initOssClient($uploadAuth, $uploadAddress);
    $aliObj->uploadLocalFile($uploadAddress, EASYSWOOLE_ROOT . '/webroot/video/2020/05/8c7d650f6ba54fb5.mp4');
    var_dump($aliObj);
  }
}
