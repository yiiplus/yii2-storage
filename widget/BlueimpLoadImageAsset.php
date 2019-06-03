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
 * BlueimpLoadImageAsset 加载图片引入
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class BlueimpLoadImageAsset extends AssetBundle
{
    /**
     * @var string 资源路径
     */
    public $sourcePath = '@bower/blueimp-load-image';

    /**
     * @var array 加载js文件路径
     */
    public $js = [
        'js/load-image.all.min.js'
    ];
}
