<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ips".
 *
 * @property int $id
 * @property string $ip
 * @property int $url_id
 * @property string|null $created_at
 *
 * @property Link $url
 */
class Ips extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'url_id'], 'required'],
            [['url_id'], 'integer'],
            [['created_at'], 'safe'],
            [['ip'], 'string', 'max' => 12],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Link::class, 'targetAttribute' => ['url_id' => 'id']],
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
            'url_id' => 'Url ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Url]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUrl()
    {
        return $this->hasOne(Link::class, ['id' => 'url_id']);
    }

}
