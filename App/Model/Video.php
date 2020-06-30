<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/26
 * Time: 下午12:17
 */
namespace App\Model;

class Video extends Base {
  public $tableName = 'video';

  /**
   * Name: getVideoData
   * Desc: get video data from database
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param array $condition
   * @param int $page
   * @param int $size
   * @return array
   * @throws \EasySwoole\Mysqli\Exception\Exception
   * @throws \Throwable
   */
  public function getVideoData($condition = [], $page = 1, $size = 10)
  {
    $startId = ($page - 1) * $size;
    if (!empty($condition['cat_id'])) {
      $this->db->queryBuilder()->where('id', $startId, '>')
        ->where('cat_id', $condition['cat_id'])
        ->get($this->tableName, $size, ['name', 'cat_id', 'content', 'uploader', 'create_time', 'image', 'video_id', 'video_duration']);
    } else {
      $this->db->queryBuilder()->where('id', $startId, '>')
        ->get($this->tableName, $size, ['id', 'name', 'cat_id', 'content', 'uploader', 'create_time', 'image', 'video_id', 'video_duration']);
    }
    $lists = $this->db->execBuilder();
    $this->db->queryBuilder()->where('id', $startId, '>')->withTotalCount()->getOne($this->tableName, 'count(*) as total');
    $count = $this->db->execBuilder()[0]['total'];

    // format time and duration
    foreach ($lists as &$list) {
      $list['create_time'] = date('Ymd H:i:s');
      $list['video_duration'] = gmstrftime('%H:%M:%s', $list['video_duration']);
      $list['video_id'] = rtrim($list['video_id']);
    }
    return [
      'lists' => $lists,
      'page_size' => $size,
      'page' => $page,
      'total' => ceil($count / $size)
    ];
  }

  /**
   * Name: getVideoCacheData
   * Desc: get video cache data
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $cat_id
   * @return bool|null
   * @throws \Throwable
   */
  public function getVideoCacheData($cat_id) {
    $this->db->queryBuilder()->where('cat_id', $cat_id)
      ->where('status', 1)
      ->get($this->tableName, null, ['id', 'name', 'cat_id', 'content', 'uploader', 'create_time', 'image', 'video_id', 'video_duration']);
    $lists = $this->db->execBuilder();
    foreach ($lists as &$list) {
      $list['create_time'] = date('Ymd H:i:s');
      $list['video_duration'] = gmstrftime('%H:%M:%s', $list['video_duration']);
      $list['video_id'] = rtrim($list['video_id']);
    }
    return $lists;
  }

  /**
   * Name: getNormalVideoById
   * Desc: get normal status video by id
   * User: CHENDAN5
   * Date: 2020/5/27
   * @param $id
   * @return bool|null
   * @throws \Throwable
   */
  public function getNormalVideoById($id) {
    $this->db->queryBuilder()->where('id', $id)->where('status', 1)->getOne($this->tableName);
    $data = $this->db->execBuilder();
    if (!empty($data)) {
      $data[0]['video_duration'] = gmstrftime('%H:%M:%S', $data[0]['video_duration']);
      return $data[0];
    }
    return $data;
  }
}
