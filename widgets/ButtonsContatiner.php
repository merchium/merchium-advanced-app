<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\db\ActiveRecord;

class ButtonsContatiner extends Widget
{
    public $model;
    
    public $footerWrapper = true;
    
    public $form = '';
    
    public $removeLink = true;

    public function run()
    {
        if ($this->footerWrapper) {
            echo Html::beginTag('div', ['class' => 'form-group panel-footer']);
        }
        
        $submit_options = [];
        if ($this->form) {
            $submit_options['form'] = $this->form;
        }

        if ($this->model instanceof ActiveRecord && $this->model->isNewRecord) {
            $submit_options['class'] = 'btn btn-success';
            echo Html::submitButton(__('Create'), $submit_options);
        } else {
            $submit_options['class'] = 'btn btn-primary';
            echo Html::submitButton(__('Update'), $submit_options);
            
            if ($this->removeLink) {
                echo ' ';
                echo Html::a(__('Delete'), ['delete', 'id' => $this->model->id], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => 'Are you sure you want to delete this item?',
                    'data-method' => 'post'
                ]);
            }
        }
        
        if ($this->footerWrapper) {
            echo Html::endTag('div');
        }
    }
}