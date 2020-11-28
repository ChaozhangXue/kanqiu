<?php

namespace api\controllers;

/**
 * Common controller
 */
class CommonController extends BaseController
{
    public function actionFileTransfer()
    {
        try {
            $url = $this->parseFileOrUrl('file', 'common');
            if (!$url) {
                throw new \Exception('参数有误');
            }
            $this->success(['url' => $url]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}