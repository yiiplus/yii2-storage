<?php

namespace yiiplus\storage;

use Yii;

class Module extends \yii\base\Module
{
    // 控制器名称空间
    public $controllerNamespace = 'yiiplus\storage\controllers';

    // 源语言
    public $sourceLanguage = 'en';

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册翻译文件
     *
     * @return void
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['yiiplus/storage'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => $this->sourceLanguage,
            'basePath' => '@yiiplus/storage/messages',
            'fileMap' => [
                'yiiplus/storage' => 'storage.php',
            ],
        ];
    }

    /**
     * 多语言翻译
     *
     * @param string  $message  消息
     * @param array   $params   参数
     * @param string  $language 语言
     * 
     * @return string 翻译结果
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('yiiplus/storage', $message, $params, $language);
    }
}
