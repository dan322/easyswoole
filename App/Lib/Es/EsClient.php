<?php
	/**
	 * Created by PhpStorm.
	 * User: CHENDAN5
	 * Date: 2020/5/28
	 * Time: 上午9:02
	 */
namespace App\Lib\Es;

use EasySwoole\Component\Singleton;
use EasySwoole\ElasticSearch\Config;
use EasySwoole\ElasticSearch\ElasticSearch;
use http\Client;

class EsClient {
  use Singleton;

  public $es;

  /**
   * EsClient constructor.
   * @throws \Exception
   */
  public function __construct()
  {
    $config = \Yaconf::get('es');
    $esConf = new Config($config);
    try {
      $es = new ElasticSearch($esConf);
      $this->es = $es->client();
    } catch (\Exception $e) {
      throw new \Exception("elasticsearch cannot use");
    }
  }

  public function createIndex($bean) {
    return $this->es->indices()->create($bean);
  }

  /**
   * Name: __call
   * Desc:
   * User: CHENDAN5
   * Date: 2020/5/28
   * @param $name
   * @param $arguments
   * @return mixed
   */
  public function __call($name, $arguments)
  {
    return $this->es->$name(...$arguments);
  }
}
