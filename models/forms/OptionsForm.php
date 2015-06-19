<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Store;
use app\models\Option;

class OptionsForm extends Model
{
    public $snowfall_enable;
    public $welcome_popup_enable;
    public $welcome_popup_title;
    public $welcome_popup_content;
    public $add_to_cart_counter_enable;
    public $payment_enable;

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
            'snowfall_enable' => __('Enable snowfall on storefront'),
            'welcome_popup_enable' => __('Enable welcome popup'),
            'welcome_popup_title' => __('Welcome popup title'),
            'welcome_popup_content' => __('Welcome popup content'),
            'add_to_cart_counter_enable' => __('Enable "add to cart" counter'),
            'payment_enable' => __('Enable payment'),
        ];
    }

    public function init()
    {
        parent::init();

        // Setting default values
        $this->welcome_popup_title = 'Welcome';
        $this->welcome_popup_content = 'Welcome content';
    }

    public function setStore(Store $store)
    {
        $this->store = $store;
        
        $attributes = $this->attributes();
        $options = $store->options;
        foreach ($options as $option) {
            if (in_array($option->name, $attributes)) {
                $this->{$option->name} = $option->value;
            }
        }
    }

    public function saveOptions()
    {
        if ($this->store->id) {
            $options = $this->store->getOptionsByName();

            foreach ($this->attributes() as $attribute) {
                if (!empty($options[$attribute])) {
                    $option = $options[$attribute];
                } else {
                    $option = new Option;
                    $option->name = $attribute;
                    $option->link('store', $this->store);
                }
                $option->value = $this->{$attribute};
                $option->save();
            }

            return true;
        } 
        return false;
    }

}
