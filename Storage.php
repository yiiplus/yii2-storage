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

namespace yiiplus\storage;

use Yii;
use League\Flysystem\FilesystemInterface;
use yiiplus\storage\events\StorageEvent;
use yiiplus\storage\filesystem\FilesystemBuilderInterface;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 * Storage 文件上传核心类
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class Storage extends Component
{
    /**
     * 删除前事件
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * 保存前事件
     */
    const EVENT_BEFORE_SAVE = 'beforeSave';

    /**
     * 删除后事件
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * 保存后事件
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var 文件域名
     */
    public $baseUrl;

    /**
     * @var 文件系统组件
     */
    public $filesystemComponent;

    /**
     * @var 文件系统
     */
    protected $filesystem;

    /**
     * @var int 目录最大文件数 -1无限制
     */
    public $maxDirFiles = 65535;

    /**
     * @var int 文件索引
     */
    private $_dirindex = 1;

    /**
     * 初始化
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->baseUrl !== null) {
            $this->baseUrl = Yii::getAlias($this->baseUrl);
        }

        if ($this->filesystemComponent !== null) {
            $this->filesystem = Yii::$app->get($this->filesystemComponent);
        } else {
            $this->filesystem = Yii::createObject($this->filesystem);
            if ($this->filesystem instanceof FilesystemBuilderInterface) {
                $this->filesystem = $this->filesystem->build();
            }
        }
    }

    /**
     * 获取文件系统
     *
     * @return mixed
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * 设置文件系统
     *
     * @param $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * 上传文件
     *
     * @param file   $file             上传文件
     * @param string $pathPrefix       目录名
     * @param bool   $preserveFileName 是否保留文件名
     * @param bool   $overwrite        新建还是更改
     * @param array  $config           其他请求头配置
     *
     * @return bool|string
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function save($file, $pathPrefix = '', $preserveFileName = false, $overwrite = false, $config = [])
    {
        $pathPrefix = FileHelper::normalizePath($pathPrefix);
        $fileObj = File::create($file);
        $dirIndex = $this->getDirIndex($pathPrefix);

        if ($preserveFileName === false) {
            do {
                $filename = implode('.', [
                    Yii::$app->security->generateRandomString(),
                    $fileObj->getExtension()
                ]);
                $implodeArr = !empty($pathPrefix) ? [$pathPrefix, $dirIndex, $filename] : [$dirIndex, $filename];
                $path = implode(DIRECTORY_SEPARATOR, $implodeArr);
            } while ($this->getFilesystem()->has($path));
        } else {
            $filename = $fileObj->getPathInfo('filename');
            $implodeArr = !empty($pathPrefix) ? [$pathPrefix, $dirIndex, $filename] : [$dirIndex, $filename];
            $path = implode(DIRECTORY_SEPARATOR, $implodeArr);
        }

        $this->beforeSave($fileObj->getPath(), $this->getFilesystem());

        $stream = fopen($fileObj->getPath(), 'rb+');
        $config = array_merge(['ContentType' => $fileObj->getMimeType()], $config);
        if ($overwrite) {
            $success = $this->getFilesystem()->putStream($path, $stream, $config);
        } else {
            $success = $this->getFilesystem()->writeStream($path, $stream, $config);
        }

		if (is_resource($stream)) {
			fclose($stream);
		}

        if ($success) {
            $this->afterSave($path, $this->getFilesystem());
            return $this->baseUrl . '/' . $path;
        }

        return false;
    }

    /**
     * 上传base64文件
     *
     * @param string $data       base64格式图片
     * @param string $pathPrefix 上传路径
     * @param string $extension  扩展
     *
     * @return bool|string
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function saveBase64($data, $pathPrefix = '', $extension = 'jpg')
    {
        if (empty($data)) {
            return false;
        }
        $pathPrefix = FileHelper::normalizePath($pathPrefix);
        $data = base64_decode(str_replace('data:image/png;base64,', '', $data));
        $dirIndex = $this->getDirIndex($pathPrefix);

        do {
            $filename = implode('.', [
                Yii::$app->security->generateRandomString(),
                $extension
            ]);
            $implodeArr = !empty($pathPrefix) ? [$pathPrefix, $dirIndex, $filename] : [$dirIndex, $filename];
            $path = implode(DIRECTORY_SEPARATOR, $implodeArr);
        } while ($this->getFilesystem()->has($path));

        $this->beforeSave($path, $this->getFilesystem());

        $success = $this->getFilesystem()->write($path, $data);
        if ($success) {
            $this->afterSave($path, $this->getFilesystem());
            return $this->baseUrl . '/' . $path;
        }
        return false;
    }

    /**
     * 批量保存
     *
     * @param file   $file             上传文件
     * @param string $pathPrefix       目录名
     * @param bool   $preserveFileName 是否保留文件名
     * @param bool   $overwrite        新建还是更改
     * @param array  $config           其他请求头配置
     *
     * @return array
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function saveAll($files, $pathPrefix, $preserveFileName = false, $overwrite = false, array $config = [])
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->save($file, $pathPrefix, $preserveFileName, $overwrite, $config);
        }
        return $paths;
    }

    /**
     * 删除文件
     *
     * @param string $path 文件路径
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function delete($path)
    {
        if ($this->getFilesystem()->has($path)) {
            $this->beforeDelete($path, $this->getFilesystem());
            if ($this->getFilesystem()->delete($path)) {
                $this->afterDelete($path, $this->getFilesystem());
                return true;
            };
        }
        return false;
    }

    /**
     * 批量删除
     *
     * @param array $files 文件路径数组
     *
     * @throws InvalidConfigException
     */
    public function deleteAll($files)
    {
        foreach ($files as $file) {
            $this->delete($file);
        }

    }

    /**
     * 获取文件索引
     *
     * @param string $path 文件路径
     *
     * @return int
     */
    protected function getDirIndex($path = '')
    {
        $normalizedPath = '.dirindex';
        if (isset($path)) {
            $normalizedPath = $path . DIRECTORY_SEPARATOR . '.dirindex';
        }

        if (!$this->getFilesystem()->has($normalizedPath)) {
            $this->getFilesystem()->write($normalizedPath, (string) $this->_dirindex);
        } else {
            $this->_dirindex = $this->getFilesystem()->read($normalizedPath);
            if ($this->maxDirFiles !== -1) {
                $filesCount = count($this->getFilesystem()->listContents($this->_dirindex));
                if ($filesCount > $this->maxDirFiles) {
                    $this->_dirindex++;
                    $this->getFilesystem()->put($normalizedPath, (string) $this->_dirindex);
                }
            }
        }

        return $this->_dirindex;
    }

    /**
     * 触发文件系统保存前事件
     *
     * @param string $path       文件路径
     * @param null   $filesystem 文件系统
     *
     * @throws InvalidConfigException
     */
    public function beforeSave($path, $filesystem = null)
    {
        $event = Yii::createObject([
            'class' => StorageEvent::className(),
            'path' => $path,
            'filesystem' => $filesystem
        ]);
        $this->trigger(self::EVENT_BEFORE_SAVE, $event);
    }

    /**
     * 触发文件系统保存后事件
     *
     * @param string $path       文件路径
     * @param null   $filesystem 文件系统
     *
     * @throws InvalidConfigException
     */
    public function afterSave($path, $filesystem)
    {
        $event = Yii::createObject([
            'class' => StorageEvent::className(),
            'path' => $path,
            'filesystem' => $filesystem
        ]);
        $this->trigger(self::EVENT_AFTER_SAVE, $event);
    }

    /**
     * 触发文件系统删除前事件
     *
     * @param string $path       文件路径
     * @param null   $filesystem 文件系统
     *
     * @throws InvalidConfigException
     */
    public function beforeDelete($path, $filesystem)
    {
        $event = Yii::createObject([
            'class' => StorageEvent::className(),
            'path' => $path,
            'filesystem' => $filesystem
        ]);
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
    }

    /**
     * 触发文件系统删除后事件
     *
     * @param string $path       文件路径
     * @param null   $filesystem 文件系统
     *
     * @throws InvalidConfigException
     */
    public function afterDelete($path, $filesystem)
    {
        $event = Yii::createObject([
            'class' => StorageEvent::className(),
            'path' => $path,
            'filesystem' => $filesystem
        ]);
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
    }
}
