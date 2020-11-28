<?php

namespace common\models;

/**
 * This is the model class for table "suggestion".
 *
 * @property int $id
 * @property string $name 建议人
 * @property string $phone 电话号码
 * @property string $detail 建议明细
 * @property string $remark 备注
 * @property int $status 受理状态
 * @property int $create_time
 * @property int $update_time
 * @property string $feedback_msg
 */
class Suggestion extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'suggestion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail', 'remark', 'feedback_msg','name', 'phone'], 'string'],
            [['detail'], 'required'],
            [['customer_id', 'status', 'create_time', 'update_time'], 'integer'],
            [['remark', 'feedback_msg'], 'default', 'value' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => '客户ID',
            'name' => '建议人',
            'phone' => '电话号码',
            'detail' => '建议明细',
            'remark' => '备注',
            'status' => '受理状态 ',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'feedback_msg' => '回复内容',
        ];
    }
}
