<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "watch_list".
 *
 * @property int $id
 * @property string $game_date
 * @property string $game_time
 * @property string $team1 比赛团队1
 * @property string $team2 比赛团队2
 * @property string $game_link json形式 key=中文名 value=连接地址
 * @property int $round
 * @property string $create_time
 * @property string $update_time
 * @property string $expire_time 过期时间 超过默认不显示
 */
class WatchList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'watch_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['game_date', 'game_time', 'create_time', 'update_time', 'expire_time'], 'safe'],
            [['game_link'], 'string'],
            [['round'], 'integer'],
            [['team1', 'team2'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_date' => 'Game Date',
            'game_time' => 'Game Time',
            'team1' => 'Team1',
            'team2' => 'Team2',
            'game_link' => 'Game Link',
            'round' => 'Round',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'expire_time' => 'Expire Time',
        ];
    }
}
