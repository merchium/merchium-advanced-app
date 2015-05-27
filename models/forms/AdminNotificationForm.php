<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Store;

class AdminNotificationForm extends Model
{
    public $store_ids;

    public $type;
    public $title;
    public $message;
    public $message_state;

    public function rules()
    {
        return [
            [['store_ids', 'message'], 'required'],
            [['type'], 'in', 'range' => ['E', 'W', 'N', 'I']],
            [['message_state'], 'in', 'range' => ['', 'S', 'K', 'I']],
            [['title'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => __('Type'),
            'title' => __('Title'),
            'message' => __('Message'),
            'message_state' => __('Message state'),
        ];
    }

    public function send()
    {
        if ($this->validate()) {
            if ($stores = Store::findAll(['id' => $this->store_ids])) {
                foreach ($stores as $store) {
                    $store->sendAdminNotification($this->type, $this->message, $this->title, $this->message_state);
                }
                return true;
            }
        } else {
            return false;
        }
    }

}
