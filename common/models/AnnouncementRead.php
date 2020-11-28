<?php

namespace common\models;

/**
 * This is the model class for table "{{%announcement_read}}".
 *
 * @property int $id
 * @property int $message_id 公告ID
 * @property int $customer_id 会员ID
 * @property int $create_time 创建时间
 */
class AnnouncementRead extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%announcement_read}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message_id', 'customer_id', 'create_time'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'customer_id' => 'Customer ID',
            'create_time' => 'Create Time',
        ];
    }
}
