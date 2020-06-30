<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/26
 * Time: 下午7:37
 */
namespace App\HttpController\Api;

use EasySwoole\Http\Message\Status;

class Category extends Base
{
  public function index()
  {
    return $this->writeJson(Status::CODE_OK, \Yaconf::get('cats.cats'), 'success');
  }
}
