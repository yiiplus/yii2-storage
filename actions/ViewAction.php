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

use yii\web\HttpException;

/**
 * ViewAction 详情动作
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class ViewAction extends BaseAction
{
    /**
     * @var string 路径
     */
    public $pathParam = 'path';

    /**
     * @var bool 行内
     */
    public $inline = false;

    /**
     * @return static
     * @throws HttpException
     * @throws \HttpException
     */
    public function run()
    {
        $path = \Yii::$app->request->get($this->pathParam);
        $filesystem = $this->getFileStorage()->getFilesystem();
        if ($filesystem->has($path) === false) {
            throw new HttpException(404);
        }
        return \Yii::$app->response->sendStreamAsFile(
            $filesystem->readStream($path),
            pathinfo($path, PATHINFO_BASENAME),
            [
                'mimeType' => $filesystem->getMimetype($path),
                'inline' => $this->inline
            ]
        );
    }
}
