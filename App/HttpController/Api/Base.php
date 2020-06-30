<?php
/**
 * Created by PhpStorm.
 * User: CHENDAN5
 * Date: 2020/5/25
 * Time: 上午11:09
 */
namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class Base extends Controller {

    public function index()
    {
    }

    public function onException(\Throwable $throwable): void
    {
        $this->writeJson('500', null, $throwable->getMessage());
    }
}

