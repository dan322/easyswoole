<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/26
 * Time: 下午12:17
 */
namespace App\Model;

use EasySwoole\Component\Di;

class Base {
  public $tableName;
  protected $db;

  public function __construct()
  {
    if (empty($this->tableName)) {
      throw new \Exception($this->tableName . ' is not exist');
    }
    $db = Di::getInstance()->get('Mysql');
    if ($db instanceof \EasySwoole\Mysqli\Client) {
      $this->db = $db;
    } else {
      throw new \Exception('connect db error');
    }
  }

  public function add($data) {
    if (empty($data) || !is_array($data)) {
      return false;
    }
    $this->db->queryBuilder()->insert($this->tableName, $data);

    //错误原因日志记录 todo
    return $this->db->execBuilder();
  }
}
