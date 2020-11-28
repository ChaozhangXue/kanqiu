<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;


class BaseController extends Controller
{

    public function init()
    {
        $user = User::findOne(['id' => 1]);
        \Yii::$app->user->setIdentity($user);
        parent::init();
    }

    public function lock($lock_file)
    {
        $lock_file = \Yii::$app->runtimePath . '/lock/' . $lock_file;
        $path = dirname($lock_file);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        if (!file_exists($lock_file)) {
            touch($lock_file);
        }
        $lock_file_handle = fopen($lock_file, 'w');
        if ($lock_file_handle === false){
            die("Can not create lock file $lock_file\n");
        }
        if (!flock($lock_file_handle, LOCK_EX + LOCK_NB)) {
            die(date("Y-m-d H:i:s") . " Process already exists.\n");
        }
    }

}