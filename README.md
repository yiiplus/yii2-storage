# storage storage
该套件基于yiiplus，旨在自动执行上传文件，保存和存储的常规过程,存储集成本地存储、阿里云OSS、腾讯云COS
## 版本
|版本 |时间|
| ----- | ----- |
|1.0| 2019年5月1日
## 特点
- 文件上传小部件（基于Blueimp文件上传）
- 用于存储文件的组件（构建在flysystem之上）
- 上传方式包括通过接口文件上传，图片base64上传

## 平台支持
|支持平台 | 资费 |
| ----- | ----- |
| 本地 | 无 |
| 阿里云 |  [费用](https://common-buy.aliyun.com/?spm=5176.7933691.1309819..58f92a66AqB8vY&commodityCode=ossbag&request=%7B%22region%22%3A%22china-common%22%7D#/buy)  |
| 腾讯云 | [费用](https://buy.cloud.tencent.com/cos) |


## 安装
通过composer安装

```php
php composer.phar require --prefer-dist yiiplus/yii2-storage "^1.1.0"
```
或添加配置到项目目录下的composer.json

```
"require": {
...
"yiiplus/yii2-storage": "^1.1.0",
...
}
```

## 配置
在组件配置中配置

- 本地上传

```php
'storage'=>[
'class' => 'yiiplus\storage\Storage',
'baseUrl' => '@web/uploads',  //本地用文件在项目存储目录 三方用可访问到文件的域名
'basePath' => 'image', //配置上传根目录
'openDirIndex' => 1, //是否开启文件索引 可不配置
'filesystem'=> [
        'class' => 'yiiplus\storage\filesystem\LocalFilesystemBuilder',  //文件处理方式
        'path' => '@root/web/backend/uploads/'    //上传路径
    ]
],
```

- 阿里云上传

```php
'storage'=>[
'class' => 'yiiplus\storage\Storage',
'basePath' => 'image', //配置上传根目录
'baseUrl' => '@web/uploads',  //本地用文件在项目存储目录 三方用可访问到文件的域名
'filesystem'=> [
        'class' => 'yiiplus\storage\filesystem\LocalFilesystemBuilder',  //文件处理方式
        'path' => '@root/web/backend/uploads/'    //上传路径
        'accessId' => '',  //密钥id
        'accessSecret' => '', //密钥key
        'bucket' => '', //桶名
        'endpoint' => '' //节点
    ]
],
```

- 腾讯云上传

```php
'storage'=>[
'class' => 'yiiplus\storage\Storage',
'basePath' => 'image', //配置上传根目录
'baseUrl' => '@web/uploads',  //本地用文件在项目存储目录 三方用可访问到文件的域名
'filesystem'=> [
        'class' => 'yiiplus\storage\filesystem\LocalFilesystemBuilder',  //文件处理方式
        'path' => '@root/web/backend/uploads/'    //上传路径
        'secretId' => '',//cos秘钥id
        'secretKey' => '',//秘钥key
        'bucket' => '',//桶名
        'appId' => '',//appId
        'region' => '',//地区
    ]
],
```

# 后台上传
## 动作
| 可选动作 | 说明 |
| ----- | ----- |
| upload | 上传 |
| delete | 删除 |
| view | 下载 |

### 上传动作

```php
public function actions(){
    return [
        'upload'=>[
            //上传类空间
            'class'=>'yiiplus\storage\actions\UploadAction',
            //是否返回多个文件
            'multiple' => true,
            //是否禁用csrf
            'disableCsrf' => true,
            //删除路由
            'deleteRoute' => 'delete',
            'sessionKey' => '_uploadedFiles',
            'allowChangeFilestorage' => false,
            //校验规则
            'validationRules' => [['file', 'integer']],
            //保存成功后处理
            'on afterSave' => function($event) {
                $file = $event->file;
            }
        ]
    ];
}
```

### 删除动作

```php
public function actions(){
    return [
        'delete'=>[
            'class'=>'yiiplus\storage\actions\DeleteAction',
        ]
    ];
}
```

### 详情动作

```php
public function actions(){
    return [
        'view'=>[
            'class'=>'yiiplus\storage\actions\ViewAction',
        ]
    ];
}
```

## 上传组件
- 独立使用

```php
echo yiiplus\storage\widget\Upload::widget([
    //模型
    'model' => $searchModel,
    //字段名
    'attribute' => 'username',
    //提交路径
    'url' => ['upload'],
    //上传文件路径
    'uploadPath' => 'subfolder',
    //是否排序
    'sortable' => true,
    //文件最大限制 10M
    'maxFileSize' => 10 * 1024 * 1024,
    //文件最小限制
    'minFileSize' => 1kb,
    //文件数量 默认1个
    'maxNumberOfFiles' => 1,
    //文件后缀限制 正则
    'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
    //是否显示文件预览名
    'showPreviewFilename' => true,
    //其他blueimp选项
    'clientOptions' => []
]);
```

- 独立使用 不使用模型

```php
echo yiiplus\storage\widget\Upload::widget([
    'name' => 'filename',
    'hiddenInputId' => 'filename',
    'url' => ['upload'],
    'uploadPath' => 'subfolder',
    'sortable' => true,
    'maxFileSize' => 10 * 1024 * 1024,
    'minFileSize' => 1,
    'maxNumberOfFiles' => 3,
    'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
    'showPreviewFilename' => false,
    'clientOptions' => []
]);
```

- activeForm中使用

data

```php
$model->files = [
  [
      //文件名称(可选)
      'name' => '123.jpg',
      //文件大小(可选)
      'size' => '9090',
      //文件路径(可选)
      'url' => '',
      //缩略图路径(可选)
      'thumbnailUrl' => '',
      //删除路由(可选)
      'deleteUrl' => 'delete',
      //删除类型(可选)
      'deleteType' => 'DELETE',
  ]
];
```

view

```php
echo $form->field($model, 'files')->widget(
    'yiiplus\storage\widget\Upload',
    [
        'url' => ['upload'],
        'uploadPath' => 'subfolder',
        'sortable' => true,
        'maxFileSize' => 10 * 1024 * 1024, // 10 MiB
        'maxNumberOfFiles' => 3,
        'clientOptions' => []
    ]
);
```
### 上传Widget事件
上传小部件会触发一些内置的blueimp事件：
您可以直接使用它们，也可以在选项中添加自定义处理程序

```php
'clientOptions' => [
    'start' => new JsExpression('function(e, data) { ... do something ... }'),//开始
    'done' => new JsExpression('function(e, data) { ... do something ... }'),//完成
    'fail' => new JsExpression('function(e, data) { ... do something ... }'),//失败
    'always' => new JsExpression('function(e, data) { ... do something ... }'),//总是
]
```


# API
- 文件方式

```php
$file = UploadedFile::getInstanceByName('file');
$s = Yii::$app->storage;
$result = $s->save($file, 'image');
return Yii::$app->storage->baseUrl . $result;
```
