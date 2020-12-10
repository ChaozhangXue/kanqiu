<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invite".
 *
 * @property int $id
 * @property int $inviter_id 邀请人
 * @property string $invite_code 邀请码
 * @property string $create_time
 */
class Invite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invite';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inviter_id'], 'integer'],
            [['create_time'], 'safe'],
            [['invite_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inviter_id' => 'Inviter ID',
            'invite_code' => 'Invite Code',
            'create_time' => 'Create Time',
        ];
    }
}
