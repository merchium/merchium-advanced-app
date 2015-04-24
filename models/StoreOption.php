<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "store_option".
 *
 * @property integer $id
 * @property integer $store_id
 * @property string $name
 * @property string $value
 *
 * @property Store $store
 */
class StoreOption extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_option';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id'], 'integer'],
            [['name'], 'required'],
            [['name', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => __('ID'),
            'store_id' => __('Store ID'),
            'name' => __('Name'),
            'value' => __('Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

}
