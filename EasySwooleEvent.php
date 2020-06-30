<?php
namespace EasySwoole\EasySwoole;


use App\Lib\Cache\VideoCache;
use App\Lib\Es\EsClient;
use App\Lib\Mysql\Mysql;
use App\Lib\Redis\Redis;
use EasySwoole\Component\Di;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
//        self::loadConf(EASYSWOOLE_ROOT . '/Config');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        Di::getInstance()->set('Mysql', \EasySwoole\Mysqli\Client::class, Mysql::getInstance()->config);
        Di::getInstance()->set('Redis', Redis::getInstance());
        Di::getInstance()->set('Es', EsClient::getInstance());

        //定时任务
//        Crontab::getInstance()->addTask(VideoCacheTask::class);
      // timer
      $cacheVideo = new VideoCache();
      Timer::getInstance()->loop(1000 * 5, function () use ($cacheVideo) {
        $cacheVideo->setIndexVideo();
      });

      // two
      /*
       $register->add('workerStart', function ($server, $workId) use ($cacheVideo) {
        if ($workId != 0) return;
        Timer::getInstance()->loop(1000 * 3, function () use ($cacheVideo) {
          $cacheVideo->setIndexVideo();
        });
      });
      */
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    /**
     * Name: loadConf
     * Desc:  config 数组形式加载配置文件 （性能不高）
     * User: CHENDAN5
     * Date: 2020/5/25
     * @param $confPath
     */
    public static function loadConf($confPath) {
        $conf = Config::getInstance();
        $files = File::scanDirectory($confPath);
        foreach ($files['files'] as $file) {
            $data = require_once $file;
            $conf->setConf(strtolower(basename($file, '.php')), (array) $data);
        }
    }
}
