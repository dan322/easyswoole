<?php
	/**
	 * Created by PhpStorm.
	 * User: CHENDAN5
	 * Date: 2020/5/28
	 * Time: 上午9:02
	 */
namespace App\Model;

use EasySwoole\Component\Di;
use EasySwoole\ElasticSearch\RequestBean\Indices\Create;

class EsBase {
  public $esClient = null;
  protected $index;
  protected $type;

  public function __construct()
  {
    $this->esClient = Di::getInstance()->get('Es');
  }

  public function createIndex()
  {
    $bean = new Create();
    $bean->setIndex($this->index);
    $bean->setBody($this->getIndexBody());
    $response = $this->esClient->createIndex($bean);
    var_dump($response);
  }

  public function getIndexBody()
  {
    $body = [
      'index' => $this->index,
      'body' => [
        'settings' => [
          'number_of_shards' => 5,
          'number_of_replicas' => 1
        ],
        'mappings' => [
          'my_type' => [
            '_source' => [
              'enabled' => true,
            ]
          ],
          'properties' => [
            'id' => [
              'type' => 'long',
            ],
            'name' => [
              'type' => 'text'
            ],
            'video_id' => [
              'type' => 'keyword',
            ],
            'image' => [
              'type' => 'text',
            ],
            'name' => [
              'type' => 'text',
              'analyzer' => 'standard',
            ],
            'content' => [
              'type' => 'text',
              'analyzer' => 'standard'
            ],
            'create_time' => [
              'type' => 'short',
            ],
            'update_time' => [
              'type' => 'short'
            ],
            'status' => [
              'type' => 'byte'
            ],
            'type' => [
              'type' => 'byte'
            ],
            'cat_id' => [
              'type' => 'short'
            ],
            'uploader' => [
              'type' => 'keyword'
            ]
          ]
        ]
      ]
    ];
    return $body;
  }

}
