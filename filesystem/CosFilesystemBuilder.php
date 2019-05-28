<?php
namespace yiiplus\storage\filesystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use yii\base\BaseObject;
use yii\base\Component;
use Freyo\Flysystem\QcloudCOSv5\Adapter;
use Qcloud\Cos\Client;

/**
 * 腾讯云上传
 *
 * Class CosFilesystemBuilder
 * @package yiiplus\storage\filesystem
 */
class CosFilesystemBuilder extends BaseObject implements \yiiplus\storage\filesystem\FilesystemBuilderInterface
{
    /*
     * 秘钥id
     */
    public $secretId;

    /*
     * appId
     */
    public $appId;

    /*
     * 秘钥key
     */
    public $secretKey;

    /*
     * host
     */
    public $host;

    /*
     * 地区
     */
    public $region;

    /*
     * 桶名
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