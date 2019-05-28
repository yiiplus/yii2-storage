<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace yiiplus\storage\widget;

use yii\web\AssetBundle;

class UploadAsset extends AssetBundle
{

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yiiplus\storage\widget\BlueimpFileuploadAsset'
    ];

    public $sourcePath = __DIR__ . '/assets';
    
    public $css = [
        YII_DEBUG ? 'css/upload-kit.css' : 'css/upload-kit.min.css'
    ];

    public $js = [
        YII_DEBUG ? 'js/upload-kit.js' : 'js/upload-kit.min.js'
    ];
}
