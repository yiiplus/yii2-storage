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
use League\Flysystem\FilesystemInterface;
use yiiplus\storage\events\UploadEvent;
use League\Flysystem\File as FlysystemFile;

/**
 * DeleteAction 删除动作
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class DeleteAction extends BaseAction
{
    /**
     * 删除后事件
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * @var string 路径参数
     */
    public $pathParam = 'path';

    /**
     * @return bool
     * @throws HttpException
     * @throws \HttpException
     */
    public function run()
    {
        $path = \Yii::$app->request->get($this->pathParam);
        $paths = \Yii::$app->session->get($this->sessionKey, []);
        if (in_array($path, $paths, true)) {
            $success = $this->getFileStorage()->delete($path);
            if (!$success) {
                throw new HttpException(400);
            } else {
                $this->afterDelete($path);
            }
            return $success;
        } else {
            throw new HttpException(403);
        }
    }
    
    /**
     * 删除后事件
     *
     * @param string $path 删除文件路径
     */
    public function afterDelete($path)
    {
        $file = null;
        $fs = $this->getFileStorage()->getFilesystem();
        if ($fs instanceof FilesystemInterface) {
            $file = new FlysystemFile($fs, $path);
        }
        $this->trigger(self::EVENT_AFTER_DELETE, new UploadEvent([
            'path' => $path,
            'filesystem' => $fs,
            'file' => $file
        ]));
    }
}
