<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property int $id
 * @property string $url_real
 * @property string $url_short
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Link extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_real', 'url_short'], 'required'],
            [['url_real', 'url_short'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['url_short'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url_real' => 'Url Real',
            'url_short' => 'Url Short',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
