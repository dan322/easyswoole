<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 下午12:41
 */
namespace App\Lib\Redis;

use EasySwoole\Component\Singleton;

class Redis {
    use Singleton;
    public $redis = null;

    private function __construct()
    {
        try {
            $redisConf = \Yaconf::get('redis');
            $this->redis = new \Redis();
            $this->redis->connect($redisConf['host'], $redisConf['port'], $redisConf['timeOut']);
        } catch (\Throwable $exception) {
            throw new \Exception('redis server error');
        }
    }

    public function __call($name, $arguments)
    {
      if (empty($arguments[0])) {
        return '';
      }
      return $this->redis->$name(...$arguments);
      /*
       if (count($arguments) == 2) {
        return $this->redis->$name($arguments[0], $arguments[1]);
      } elseif (count($arguments) == 1) {
        return $this->redis->$name($arguments[0]);
      } elseif (count($arguments) == 3) {
        return $this->redis->$name($arguments[0], $arguments[1], $arguments[2]);
      }
      */
    }

//    public function zrevrange($key, $start, $end, $type) {
//      return $this->redis->zRevRange($key, $start, $end, true);
//    }
}
