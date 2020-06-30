<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午6:08
 */
namespace App\Lib\Upload;

class Video extends Base
{

  public $maxSize = 64000000;
  public $extensionTypes = ['mp4', 'flv'];
  public $type = 'video';

}
