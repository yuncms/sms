广播
========

> 注意：本节正在开发中。

YUNCMS 支持组成和发送广播。然而，该框架提供的只有内容组成功能和基本接口。
实际的广播发送机制可以通过扩展提供，
因为不同的项目可能需要不同的实现方式，
它通常取决于外部服务和库。

大多数情况下你可以使用 [broadcast-aliyun](https://github.com/yuncms/broadcast-aliyun) 官方扩展。


配置
-------

广播组件配置取决于你所使用的扩展。
一般来说你的应用程序配置应如下：

```php
return [
    //....
    'components' => [
        'broadcast' => [
            'class' => 'yuncms\broadcast\aliyun\Broadcast',
            'endPoint' => 'http://sixiaabcng.mns.cn-hangzhou.aliyuncs.com/',
            'topicName' => 'sixiaabcng',
            'accessId' => '654314312',
            'accessKey' => '123456',
        ],
    ],
];
```


基本用法
---------

一旦 “broadcast” 组件被配置，可以使用下面的代码来发送广播：

```php
Yii::$app->broadcast->createMessage([
        'key'=>'value'
    ])->send();
```

在上面的例子中所述的 `createMessage()` 方法创建了广播消息，这是填充和发送的一个实例。

> 注意：每个 “broadcast” 的扩展也有两个主要类别：“Broadcast” 
  和 “Message”。 “Broadcast” 总是知道类名和具体的 “Message”。
  不要试图直接实例 “Message” 对象 - 而是始终使用 `createMessage()` 方法。

你也可以一次发送几个广播消息：

```php
$messages = [];
foreach ($users as $user) {
    $messages[] = Yii::$app->broadcast->createMessage(['key'=>'value']);
}
Yii::$app->broadcast->sendMultiple($messages);
```

一些特定的扩展可能会受益于这种方法，使用单一的网络消息等。

测试和调试
-----------

开发人员常常要检查一下，有什么广播是由应用程序发送的，他们的内容是什么等。
这可通过 `yuncms\broadcast\BaseBroadcast::useFileTransport` 来检查。
如果开启这个选项，会把广播信息保存在本地文件而不是发送它们。
这些文件保存在 `yuncms\broadcast\BaseBroadcast::fileTransportPath` 中，默认在 '@runtime/broadcast' 。

> 提示：你可以保存这些信息到本地文件或者把它们发送出去，但不能同时两者都做。

广播信息文件可以在一个普通的文本编辑器中打开，这样你就可以浏览实际的广播内容。
这种机制可以用来调试应用程序或运行单元测试。

> 提示：该广播信息文件是会被 `\yuncms\broadcast\MessageInterface::toString()` 转成字符串保存的，
  它依赖于实际在应用程序中使用的广播扩展。


创建自己的广播解决方案
-------------------

为了创建你自己的-----解决方案，你需要创建两个类，一个用于 “Broadcast”，另一个用于 “Message”。
你可以使用 `yuncms\broadcast\BaseBroadcast` 和 `yuncms\broadcast\BaseMessage` 作为基类。
这些类已经实现了基本的逻辑，这在本指南中有介绍。
然而，它们的使用不是必须的，
它实现了 `yuncms\broadcast\BroadcastInterface` 和 `yuncms\broadcast\MessageInterface` 接口。
然后，你需要实现所有 abstract 方法来构建解决方案。
