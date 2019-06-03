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
 * BlueimpFileuploadAsset 上传引入文件
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class BlueimpFileuploadAsset extends AssetBundle
{
    /**
     * @var string 资源路径
     */
    public $sourcePath = '@bower/blueimp-file-upload';

    /**
     * @var array 加载css文件路径
     */
    public $css = [
        'css/jquery.fileupload.css'
    ];

    /**
     * @var array 加载js文件路径
     */
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-image.js',
        'js/jquery.fileupload-validate.js'
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yiiplus\storage\widget\BlueimpLoadImageAsset'
    ];
}
