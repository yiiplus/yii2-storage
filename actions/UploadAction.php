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

use League\Flysystem\FilesystemInterface;
use yiiplus\storage\events\UploadEvent;
use League\Flysystem\File as FlysystemFile;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UploadAction 上传动作
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class UploadAction extends BaseAction
{
    /**
     * 保存后事件
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var string 文件参数
     */
    public $fileparam = 'file';

    /**
     * @var bool 多个上传
     */
    public $multiple = true;

    /**
     * @var bool 禁用csrf
     */
    public $disableCsrf = true;

    /**
     * @var string 返回数据格式
     */
    public $responseFormat = Response::FORMAT_JSON;

    /**
     * @var string 返回路径
     */
    public $responsePathParam = 'path';

    /**
     * @var string 返回基础地址
     */
    public $responseBaseUrlParam = 'base_url';

    /**
     * @var string 返回地址
     */
    public $responseUrlParam = 'url';

    /**
     * @var string 返回删除地址
     */
    public $responseDeleteUrlParam = 'delete_url';

    /**
     * @var string 返回类型
     */
    public $responseMimeTypeParam = 'type';

    /**
     * @var string 返回名称
     */
    public $responseNameParam = 'name';

    /**
     * @var string 返回大小
     */
    public $responseSizeParam = 'size';

    /**
     * @var string 删除路由
     */
    public $deleteRoute = 'delete';

    /**
     * @var 校验规则
     */
    public $validationRules;

    /**
     * @var string 存储路径
     */
    public $uploadPath = '';

    /**
     * 初始化
     */
    public function init()
    {
        \Yii::$app->response->format = $this->responseFormat;

        if (\Yii::$app->request->get('fileparam')) {
            $this->fileparam = \Yii::$app->request->get('fileparam');
        }

        if (\Yii::$app->request->get('upload-path')) {
            $this->uploadPath = \Yii::$app->request->get('upload-path');
        }

        if ($this->disableCsrf) {
            \Yii::$app->request->enableCsrfValidation = false;
        }
    }

    /**
     * @return array
     * @throws \HttpException
     */
    public function run()
    {
        $result = [];
        $uploadedFiles = UploadedFile::getInstancesByName($this->fileparam);

        foreach ($uploadedFiles as $uploadedFile) {
            /* @var \yii\web\UploadedFile $uploadedFile */
            $output = [
                $this->responseNameParam => Html::encode($uploadedFile->name),
                $this->responseMimeTypeParam => $uploadedFile->type,
                $this->responseSizeParam => $uploadedFile->size,
                $this->responseBaseUrlParam =>  $this->getFileStorage()->baseUrl
            ];
            if ($uploadedFile->error === UPLOAD_ERR_OK) {
                $validationModel = DynamicModel::validateData(['file' => $uploadedFile], $this->validationRules);
                if (!$validationModel->hasErrors()) {
                    $path = $this->getFileStorage()->save($uploadedFile, $this->uploadPath, false, false, []);

                    if ($path) {
                        $output[$this->responsePathParam] = $path;
                        $output[$this->responseUrlParam] = $path;
                        $output[$this->responseDeleteUrlParam] = Url::to([$this->deleteRoute, 'path' => $path]);
                        $paths = \Yii::$app->session->get($this->sessionKey, []);
                        $paths[] = $path;
                        \Yii::$app->session->set($this->sessionKey, $paths);
                        $this->afterSave($path);

                    } else {
                        $output['error'] = true;
                        $output['errors'] = [];
                    }

                } else {
                    $output['error'] = true;
                    $output['errors'] = $validationModel->getFirstError('file');
                }
            } else {
                $output['error'] = true;
                $output['errors'] = $this->resolveErrorMessage($uploadedFile->error);
            }

            $result['files'][] = $output;
        }
        return $this->multiple ? $result : array_shift($result);
    }

    /**
     * @param string $path 保存路径
     */
    public function afterSave($path)
    {
        $file = null;
        $fs = $this->getFileStorage()->getFilesystem();
        if ($fs instanceof FilesystemInterface) {
            $file = new FlysystemFile($fs, $path);
        }
        $this->trigger(self::EVENT_AFTER_SAVE, new UploadEvent([
            'path' => $path,
            'filesystem' => $fs,
            'file' => $file
        ]));
    }

    /**
     * 返回异常信息
     *
     * @param string $value 异常常量
     *
     * @return bool|null|string
     */
    protected function resolveErrorMessage($value)
    {
        switch ($value) {
            case UPLOAD_ERR_OK:
                return false;
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = 'The uploaded file was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = 'No file was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = 'Missing a temporary folder.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = 'Failed to write file to disk.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = 'A PHP extension stopped the file upload.';
                break;
            default:
                return null;
                break;
        }
        return $message;
    }
}
