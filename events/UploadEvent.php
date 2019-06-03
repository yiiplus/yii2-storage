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

namespace yiiplus\storage\events;
use yii\base\Event;

/**
 * UploadEvent 上传事件
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class UploadEvent extends Event
{
    /**
     * @var mixed 文件系统
     */
    public $filesystem;

    /**
     * @var string 路径
     */
    public $path;

    /**
     * @var 文件
     */
    public $file;
}
