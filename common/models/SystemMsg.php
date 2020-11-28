<?php

namespace common\models;

/**
 * This is the model class for table "system_msg".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $content 内容
 * @property string $publish_time 发布时间
 * @property string $exec_time 执行时间
 * @property string $type 类型
 * @property int $customer_type 类型
 * @property string $status 状态
 * @property string $publisher 发布人
 * @property string $receive_id 接收对象
 * @property int $urgency_level 紧急程度： 1:低 2：中 3：高
 * @property int $create_time
 * @property int $update_time
 */
class SystemMsg extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_msg';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content', 'response'], 'string'],
            [['title', 'content'], 'required'],
            [['publish_time', 'exec_time'], 'safe'],
            [['create_time', 'update_time', 'type', 'receive_id','customer_type'], 'integer'],
            [['title', 'publisher'], 'string', 'max' => 50],
            [['status'], 'default', 'value' => 1],
            [['title', 'content', 'response'], 'default', 'value' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'customer_type' => '用户类型',
            'title' => '标题',
            'content' => '内容',
            'publish_time' => '发布时间',
            'publisher' => '发布人',
            'receive_id' => '接收对象',
            'status' => '状态',
            'response' => '推送结果',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
