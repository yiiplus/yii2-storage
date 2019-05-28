<?php

namespace yiiplus\storage\events;

use yii\base\Event;

/**
 * Class StorageEvent
 * @package yiiplus\storage\events
 * @author Eugene Terentev <eugene@terentev.net>
 */
class StorageEvent extends Event
{
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    public $filesystem;
    /**
     * @var string
     */
    public $path;
}
