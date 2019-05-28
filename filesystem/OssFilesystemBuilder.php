<?php
namespace yiiplus\storage\filesystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use yii\base\BaseObject;
use yii\base\Component;
use Xxtime\Flysystem\Aliyun\OssAdapter;

/**
 * Class LocalFilesystemBuilder
 * @author Eugene Terentev <eugene@terentev.net>*
 *
 */
class OssFilesystemBuilder extends BaseObject implements \yiiplus\storage\filesystem\FilesystemBuilderInterface
{
    /*
     * 密钥id
     */
    public $accessId;

    /*
     * 密钥secret
     */
    public $accessSecret;

    /*
     * 桶名
     */
    public $bucket;

    /*
     * 节点
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
            // 'timeout'        => 3600,
            // 'connectTimeout' => 10,
            // 'isCName'        => false,
            // 'token'          => '',
        ]);
        $filesystem = new Filesystem($adapter);
        return $filesystem;
    }
}
