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
use Freyo\Flysystem\QcloudCOSv5\Adapter;
use Qcloud\Cos\Client;

/**
 * CosFilesystemBuilder cos文件系统
 *
 * @author Zhang Xu <zhangxu@himoca.com>
 * @since 2.0.0
 */
class CosFilesystemBuilder extends BaseObject implements \yiiplus\storage\filesystem\FilesystemBuilderInterface
{
    /**
     * @var 秘钥id
     */
    public $secretId;

    /**
     * @var appId
     */
    public $appId;

    /**
     * @var 秘钥key
     */
    public $secretKey;

    /**
     * @var host
     */
    public $host;

    /**
     * @var 地区
     */
    public $region;

    /**
     * @var 桶名
     */
    public $bucket;

    /**
     * @return Filesystem
     */
    public function build()
    {
        $config = [
            'region'          => $this->region,
            'credentials'     => [
                'appId'     => $this->appId,
                'secretId'  => $this->secretId,
                'secretKey' => $this->secretKey,
            ],
            'timeout'         => 60,
            'connect_timeout' => 60,
            'bucket'          => $this->bucket,
            'cdn'             => '',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
            'cdn_key'         => '',
        ];

        $client     = new Client($config);
        $adapter    = new Adapter($client, $config);
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }
}
