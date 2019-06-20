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

namespace yiiplus\storage\actions;

use yiiplus\storage\Storage;
use yii\base\Action;
use yii\di\Instance;

/**
 * BaseAction 基础动作
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
abstract class BaseAction extends Action
{
    /**
     * @var string 文件组件名称
     */
    public $fileStorage = 'storage';

    /**
     * @var string 文件组件参数名称
     */
    public $fileStorageParam = 'storage';

    /**
     * @var string 上传文件session key
     */
    public $sessionKey = '_uploadedFiles';

    /**
     * @var bool 是否允许用户通过传递参数更改组件名称
     */
    public $allowChangeFilestorage = false;

    /**
     * 获取storage实例
     *
     * @return array|mixed|object|string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFileStorage()
    {
        $fileStorage = $this->fileStorage;
        $fileStorage = Instance::ensure($fileStorage, Storage::className());

        return $fileStorage;
    }
}
