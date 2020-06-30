<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/27
 * Time: 上午8:51
 */
namespace App\Task;

use App\Lib\Cache\VideoCache;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Task\TaskManager;

class VideoCacheTask extends AbstractCronTask
{
  private $_logType = 'VideoCache: ';

  public static function getRule(): string
  {
    return '*/2 * * * *';
  }

  public static function getTaskName(): string
  {
    return 'videoCacheTask';
  }

  public function run(int $taskId, int $workerIndex)
  {
    if ($workerIndex == 0) {
      $cache = new VideoCache();
      TaskManager::getInstance()->async(function () use ($cache) {
        $cache->setIndexVideo();
      });
    }
  }

  public function onException(\Throwable $throwable, int $taskId, int $workerIndex)
  {
    Logger::getInstance()->error($throwable->getMessage(), $this->_logType);
  }
}
