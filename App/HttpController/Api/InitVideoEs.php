<?php
	/**
	 * Created by PhpStorm.
	 * User: CHENDAN5
	 * Date: 2020/5/28
	 * Time: 下午12:59
	 */
namespace App\HttpController\Api;

use App\Model\VideoEs;
use EasySwoole\Http\Message\Status;

class InitVideoEs extends Base
{
  public function index()
  {
    $videoEs = new VideoEs();
    $result = $videoEs->createIndex();
    return $this->writeJson(Status::CODE_OK, $result, 'success');
  }
}
