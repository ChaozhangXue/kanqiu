<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "free_watch".
 *
 * @property int $id
 * @property string $ip
 * @property string $create_time
 */
class FreeWatch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'free_watch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['create_time'], 'safe'],
            [['ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'create_time' => 'Create Time',
        ];
    }
}
