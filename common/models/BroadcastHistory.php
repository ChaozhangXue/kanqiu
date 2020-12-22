<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "broadcast_history".
 *
 * @property int $id
 * @property string $title 视频标题
 * @property string $preview_url 预览视频url
 * @property string $pan_url 百度云或其他云盘url
 * @property string $pan_pass 百度云或其他云盘提取码
 * @property int $price 资源售价，默认单位元
 * @property string $create_time 创建时间
 * @property string $modify_time 修改时间
 */
class BroadcastHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'broadcast_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price'], 'integer'],
            [['create_time', 'modify_time'], 'safe'],
            [['title', 'preview_url', 'pan_url'], 'string', 'max' => 100],
            [['pan_pass'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'preview_url' => 'Preview Url',
            'pan_url' => 'Pan Url',
            'pan_pass' => 'Pan Pass',
            'price' => 'Price',
            'create_time' => 'Create Time',
            'modify_time' => 'Modify Time',
        ];
    }
}
