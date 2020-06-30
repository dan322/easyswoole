<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午12:54
 */
namespace App\Lib\Mysql;

use EasySwoole\Component\Singleton;
use EasySwoole\Mysqli\Config;

class Mysql {
  use Singleton;

  public $config;

  private function __construct()
  {
    $this->config = new Config(\Yaconf::get('mysql'));
  }
}
