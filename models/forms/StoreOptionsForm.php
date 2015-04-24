<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Store;
use app\models\StoreOption;

class StoreOptionsForm extends Model
{
    public $enable_snow;
    public $welcome_popup_enable;
    public $welcome_popup_title;
    public $welcome_popup_content;

    protected $store;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [$this->attributes(), 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'enable_snow' => __('Enable snow on storefront'),
            'welcome_popup_enable' => __('Enable welcome popup'),
            'welcome_popup_title' => __('Welcome popup title'),
            'welcome_popup_content' => __('Welcome popup content'),
        ];
    }

    public function init()
    {
        parent::init();

        // set default values
        $this->welcome_popup_title = 'Welcome';
        $this->welcome_popup_content = 'Welcome content';
    }

    public function setStore(Store $store)
    {
        $this->store = $store;
        
        $options = $store->storeOptions;
        foreach ($options as $option) {
            $this->{$option->name} = $option->value;
        }
    }

    public function saveOptions()
    {
        if ($this->store->id) {
            $options = $this->store->getStoreOptionsByName();

            foreach ($this->attributes() as $attribute) {
                if (!empty($options[$attribute])) {
                    $option = $options[$attribute];
                } else {
                    $option = new StoreOption;
                    $option->link('store', $this->store);
                    $option->name = $attribute;
                }
                $option->value = $this->{$attribute};
                $option->save();
            }

            return true;
        } 
        return false;
    }

}
