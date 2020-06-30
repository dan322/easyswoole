<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/26
 * Time: 下午10:58
 */
namespace App\Lib\Cache;

use App\Model\Video;
use EasySwoole\Component\Di;
use EasySwoole\Component\TableManager;
use SebastianBergmann\CodeCoverage\Report\PHP;

class VideoCache {
  private $_cacheType;

  public function __construct()
  {
    $this->_cacheType = \Yaconf::get('base.indexCacheType');
  }

  /**
   * Name: setIndexVideo
   * Desc: set video data cache accroding to cache type in base.ini
   * User: CHENDAN5
   * Date: 2020/5/27
   * @throws \Throwable
   */
  public function setIndexVideo() {
    $categoryDataAll = [];
    $categories = \Yaconf::get('cats.cats');
    $videoMode = new Video();
    switch ($this->_cacheType) {
      case 'file':
        $method = 'cacheFile';
        break;
      case 'table':
        $method = 'cacheTable';
        break;
      case 'redis':
        $method = 'cacheRedis';
        break;
      default:
        throw new \Exception('illegal request');
    }
    foreach ($categories as $key => $category) {
      $data = $videoMode->getVideoCacheData($key);
      $this->$method($data, $key);
      $categoryDataAll = array_merge($categoryDataAll, $data);
    }
    $this->$method($categoryDataAll, 0);
  }

  /**
   * Name: cacheFile
   * Desc: cache data in a file
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $data
   * @param $category
   */
  public function cacheFile($data, $category) {
    $file = EASYSWOOLE_ROOT . '/webroot/video/json/' . $category . '.json';
    if (!file_exists($file)) {
      mkdir(dirname($file));
      touch($file);
    }
    file_put_contents($file, json_encode(['lists' => $data, 'total' => count($data)]));
  }

  /**
   * Name: cacheTable
   * Desc: cache data in swoole table
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $data
   * @param $category
   */
  public function cacheTable($data, $category) {
    TableManager::getInstance()->add($category, ['lists' => $data, 'total' => count($data)]);
  }

  /**
   * Name: cacheRedis
   * Desc: cache data in redis hashtable
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $data
   * @param $category
   * @throws \Throwable
   */
  public function cacheRedis($data, $category) {
    Di::getInstance()->get('Redis')->hset('videoCache', $category, json_encode(['lists' => $data, 'total' => count($data)]));
  }

  /**
   * Name: getCacheVideoData
   * Desc: get cache video data according to cache type in config
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $category
   * @param int $page
   * @param int $size
   * @return array
   * @throws \Throwable
   */
  public function getCacheVideoData($category, $page = 1, $size = 10) {
    $data = ['lists' => [], 'total' => 0];
    switch ($this->_cacheType) {
      case 'file':
        $fileName = EASYSWOOLE_ROOT . '/webroot/video/json/' . $category . '.json';
        if (file_exists($fileName)) {
          $dataJson = file_get_contents($fileName);
          $data = json_decode($dataJson, true);
        }
        break;
      case 'table':
        $data = TableManager::getInstance()->get($category);
        break;
      case 'redis':
        $dataJson = Di::getInstance()->get('Redis')->hget('videoCache', $category);
        if (!empty($dataJson)) {
          $data = json_decode($dataJson, true);
        }
        break;
      default:
        throw new \Exception('illegal request');
    }
    $lists = array_slice($data['lists'], ($page - 1) * $size, $size);
    return [
      'lists' => $lists,
      'total' => ceil($data['total'] / $size),
      'page' => $page,
      'size' => $size
    ];
  }
}
