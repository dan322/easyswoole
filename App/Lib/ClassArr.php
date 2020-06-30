<?php
namespace App\Lib;

/**
 * 做一些反射机制有关的 处理
 */
class ClassArr {

  public function uploadClassStat() {
    return [
      'image' => '\App\Lib\Upload\Image',
      'video' => '\App\Lib\Upload\Video',
    ];
  }

  /**
   * Name: initClass
   * Desc:
   * User: CHENDAN5
   * Date: 2020/5/26
   * @param $type
   * @param $supportClass
   * @param array $params
   * @param bool $needInstance
   * @return bool|mixed|object
   * @throws \ReflectionException
   */
  public function initClass($type, $supportClass, $params = [], $needInstance = true) {
    if (!isset($this->$supportClass()[$type])) {
      return false;
    }
    $className = $this->$supportClass()[$type];
    return $needInstance
      ? (new \ReflectionClass($className))->newInstanceArgs($params)
      : $className;
  }

}
