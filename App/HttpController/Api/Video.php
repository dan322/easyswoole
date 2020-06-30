<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/26
 * Time: 下午12:01
 */
namespace App\HttpController\Api;

use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Task\TaskManager;
use EasySwoole\Http\Message\Status;
use EasySwoole\Validate\Validate;
use App\Model\Video as VideoModel;

class Video extends Base
{
   private $_logType = 'video';

  /**
   * Name: add
   * Desc:
   * User: CHENDAN5
   * Date: 2020/5/27
   * @return bool
   */
   public function add()
   {
     $params = $this->request()->getRequestParam();

     //日志记录
     Logger::getInstance()->log($this->_logType . ' | add' . json_encode($params));

     //数据校验
     $validate = new Validate();
     $validate->addColumn('name')->required()->lengthMax(20)->lengthMin(2);
     $validate->addColumn('url')->required();
     $validate->addColumn('image')->required();
     $validate->addColumn('content')->required();
     $validate->addColumn('cat_id')->required();
     if (!$validate->validate($params)) {
       return $this->writeJson(Status::CODE_BAD_REQUEST, [], $validate->getError()->__toString());
     }

     $params['create_time'] = time();
     $params['status'] = 1;
     $params['uploader'] = 'cc';
     try {
       $model = new VideoModel();
       $videoId = $model->add($params);
     } catch (\Exception $e) {
       return $this->writeJson(Status::CODE_BAD_REQUEST, [], $e->getMessage());
     }
     if (empty($videoId)) {
       return $this->writeJson(Status::CODE_BAD_REQUEST, [], 'upload video failed');
     }
     return $this->writeJson(Status::CODE_OK, [], 'success');
   }

  /**
   * Name: index
   * Desc: get video detail
   * User: CHENDAN5
   * Date: 2020/5/27
   * @return bool|void
   * @throws \Throwable
   */
   public function index()
   {
     $params = $this->request()->getRequestParam();
     $id = intval($params['id']);
     $videoModel = new VideoModel();
     $data = $videoModel->getNormalVideoById($id);
     if (empty($data)) {
       return $this->writeJson(Status::CODE_BAD_REQUEST, [], 'video is not exist');
     }
     //add play amount use task
     TaskManager::getInstance()->async(function () use ($id) {
       $redis = Di::getInstance()->get('Redis');
       $redis->zincrBy('video_play_today', 1, $id);
       $redis->zincrBY('video_play_week', 1, $id);
       $redis->zincrBY('video_play_month', 1, $id);
       $redis->zincrBY('video_play', 1, $id);
     }, function () {
       echo "finish" . PHP_EOL;
     });
     return $this->writeJson(Status::CODE_OK, $data, 'success');
   }

  /**
   * Name: rank
   * Desc: return rank of video of today //todo (should return video detail)
   * User: CHENDAN5
   * Date: 2020/5/27
   * @return bool
   * @throws \Throwable
   */
   public function rank()
   {
     $result = Di::getInstance()->get('Redis')->zrevrange('video_play_today', 0, -1, true);
     return $this->writeJson(Status::CODE_OK, $result, 'success');
   }
}
