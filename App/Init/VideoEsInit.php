<?php
	/**
	 * Created by PhpStorm.
	 * User: CHENDAN5
	 * Date: 2020/5/28
	 * Time: 上午9:01
	 */
namespace App\Init;

use App\Model\VideoEs;

class VideoEsInit {

  /**
   * Name: initVideo
   * Desc:
   * User: CHENDAN5
   * Date: 2020/5/28
   */
  public function initVideo()
  {
    $model = new VideoEs();
    $model->createIndex();
  }

}
