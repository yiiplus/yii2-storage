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
 * BlueimpTmplAsset 临时加载
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class BlueimpTmplAsset extends AssetBundle
{
    /**
     * @var string 资源路径
     */
    public $sourcePath = '@bower/blueimp-tmpl';

    /**
     * @var array js路径
     */
    public $js = [
        'js/tmpl.min.js'
    ];
}
