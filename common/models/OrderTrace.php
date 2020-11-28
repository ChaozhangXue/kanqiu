<?php

namespace common\models;

/**
 * This is the model class for table "order_trace".
 *
 * @property int $id
 * @property int $order_id
 * @property string $detail
 * @property string $created_at
 * @property string $updated_at
 */
class OrderTrace extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_trace';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['detail'], 'required'],
            [['detail'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'detail' => 'Detail',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
