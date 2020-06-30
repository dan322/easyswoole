<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 上午11:11
 */
namespace App\HttpController\Api;

use App\Lib\Cache\VideoCache;
use EasySwoole\Http\Message\Status;

class Index extends Base {

  /**
   * Name: lists
   * Desc: get video data for index list
   * User: CHENDAN5
   * Date: 2020/5/27
   * @return bool
   * @throws \Throwable
   */
  public function lists() {
    $params = $this->request()->getRequestParam();

    //method 1 get data from database
//    $videoModel = new \App\Model\Video();
//    $data = $videoModel->getVideoData($params);

    //method 2 get data from cache
    $page = empty($params['page']) ? 1: $params['page'];
    $size = empty($params['size']) ? 10: $params['size'];
    $cat_id = empty($params['cat_id']) ? 0: $params['cat_id'];
    try {
      $data = (new VideoCache())->getCacheVideoData($cat_id, $page, $size);
    } catch (\Exception $e) {
      return $this->writeJson(Status::CODE_BAD_REQUEST, [], $e->getMessage());
    }
    return $this->writeJson(Status::CODE_OK, $data, 'success');
  }

  public function video() {
    return $this->writeJson(200, ['hello' => 'ann'], 'success');
  }

  public function getRedis() {
    $redis = new \Redis();
    $redis->connect('127.0.0.1', 6379, 5);
    $result = $redis->get('hello');
    return $this->writeJson(200, $result, 'success');
  }

}
