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

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use yii\base\BaseObject;
use yii\base\Component;
use Xxtime\Flysystem\Aliyun\OssAdapter;

/**
 * OssFilesystemBuilder 阿里云文件系统
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class OssFilesystemBuilder extends BaseObject implements \yiiplus\storage\filesystem\FilesystemBuilderInterface
{
    /**
     * @var 密钥id
     */
    public $accessId;

    /**
     * @var 密钥secret
     */
    public $accessSecret;

    /**
     * @var 桶名
     */
    public $bucket;

    /**
     * @var 节点
     */
    public $endpoint;

    /**
     * @return Filesystem
     */
    public function build()
    {
        $adapter = new OssAdapter([
            'access_id'       => $this->accessId,
            'access_secret'   => $this->accessSecret,
            'bucket'         => $this->bucket,
            'endpoint'       => $this->endpoint,
        ]);
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }
}
