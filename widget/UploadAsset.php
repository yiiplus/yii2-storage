<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\storage\widget;

use yii\web\AssetBundle;

/**
 * UploadAsset 上传加载文件
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class UploadAsset extends AssetBundle
{
    /**
     * @var array 依赖
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yiiplus\storage\widget\BlueimpFileuploadAsset'
    ];

    /**
     * @var string 资源路径
     */
    public $sourcePath = __DIR__ . '/assets';

    /**
     * @var array css文件路径
     */
    public $css = [
        YII_DEBUG ? 'css/upload-kit.css' : 'css/upload-kit.min.css'
    ];

    /**
     * @var array js文件路径
     */
    public $js = [
        YII_DEBUG ? 'js/upload-kit.js' : 'js/upload-kit.min.js'
    ];
}
