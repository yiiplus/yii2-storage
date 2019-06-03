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

namespace yiiplus\storage\filesystem;

/**
 * FilesystemBuilderInterface 文件系统接口
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
interface FilesystemBuilderInterface
{
    /**
     * @return mixed
     */
    public function build();
}
