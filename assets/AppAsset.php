<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\helpers\Json;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/core.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public static function customJs()
    {
        $translates = [];
        
        $langs = [
            'No items selected',
        ];
        foreach ($langs as $lang) {
            $translates[$lang] = __($lang);
        }

        return "window.yii.merchium = " . Json::encode([
            'langs' => $translates,
        ]);
    }

}
