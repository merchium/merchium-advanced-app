<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $domain
 * @property string $access_token
 */
class Store extends ActiveRecord
{
    public static function tableName()
    {
        return 'store';
    }

    public function behaviors()
    {
        return [
            'app\behaviors\MerchiumClientBehavior',
            'yii\behaviors\TimestampBehavior',
        ];
    }

    public function rules()
    {
        return [
            [['domain', 'access_token'], 'required'],
            [['domain'], 'string', 'max' => 128],
            [['access_token'], 'string', 'max' => 64],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => __('ID'),
            'domain' => __('Domain'),
            'access_token' => __('Access token'),
            'created_at' => __('Created At'),
            'updated_at' => __('Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreOptions()
    {
        return $this->hasMany(StoreOption::className(), ['store_id' => 'id']);
    }

    public function getStoreOptionsByName()
    {
        $options_by_name = [];
        foreach ($this->storeOptions as $option) {
            $options_by_name[$option->name] = $option;
        }
        return $options_by_name;
    }
    
}
