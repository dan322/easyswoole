<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午6:08
 */
namespace App\Lib\Upload;

class Image extends Base
{

  public $maxSize = 6200;
  public $extensionTypes = ['jpg', 'png', 'gif', 'jpeg'];
  public $type = 'image';

}
