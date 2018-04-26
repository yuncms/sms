## Notification 通知

此模块提供了一种通过各种传送通道发送通知的方式，包括邮件，屏幕，SMS等。通知也可以存储在数据库中，以便它们可以显示在Web界面中。

### 组件配置

```php
[
      'components' => [
          'notification' => [
              'channels' => [//通知渠道配置
                  'mail' => [//系统内置的
                      'class' => 'yuncms\notifications\channels\EmailChannel',
                      'from' => 'admin@example.com',
                  ],//自定义配置
                  'cloudPushChannel' => [
                      'class' => 'yuncms\notifications\channels\CloudPushChannel',
                      'appKey' => 'abcd'
                  ],
                  'sms' => [//系统内置短信渠道
                      'class' => 'yuncms\notifications\channels\SmsChannel',
                  ],
              ],
          ],
      ],
]
```

### Create A Notification 创建通知

每个通知都由一个类表示（通常存储在 `app/notifications` 目录中）。

```php
namespace app\notifications;

use Yii;
use yuncms\notifications\Notification;

class AccountNotification extends Notification
{
    const KEY_NEW_ACCOUNT = 'new_account';

    const KEY_RESET_PASSWORD = 'reset_password';

    /**
     * @var \yii\web\User the user object
     */
    public $user;

    /**
     * @inheritdoc
     */
    public function getTitle(){
        switch($this->key){
            case self::KEY_NEW_ACCOUNT:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->user->id]);
            case self::KEY_RESET_PASSWORD:
                return Yii::t('app', 'Instructions to reset the password');
        }
    }
}
```

### Send A Notification 发送通知

一旦创建通知，您可以按如下方式发送通知：

```php

$user = User::findOne(123);

AccountNotification::create(AccountNotification::KEY_RESET_PASSWORD, ['user' => $user])->send();
```

### Specifying Delivery Channels 指定传送通道

每个通知类都有一个 `shouldSend($channel)` 方法，用于确定通知将传递到哪种类型的键和通道。 在本例中，通知将在除“screen”或键“new_account”之外的所有频道中传送：

```php
/**
 * Get the notification's delivery channels.
 * @return boolean
 */
public function shouldSend($channel)
{
    if($channel->id == 'screen'){
        if(!in_array($this->key, [self::KEY_NEW_ACCOUNT])){
            return false;
        }
    }
    return true;
}
```

### Specifying The Send For Specific Channel 指定发送特定频道

每个频道都有一个接收通知实例的发送方法，并定义该频道将发送通知的方式。 但是您可以通过在通知类中定义 `toMail（"to"+ [Channel ID]）` 来覆盖 `send` 方法。 这个例子展示了如何做到这一点：

```php
/**
 * 覆盖发送到电子邮件频道
 *
 * @param $channel the email channel
 * @return void
 */
public function toEmail($channel){
    switch($this->key){
        case self::KEY_NEW_ACCOUNT:
            $subject = 'Welcome to MySite';
            $template = 'newAccount';
            break;
        case self::KEY_RESET_PASSWORD:
            $subject = 'Password reset for MySite';
            $template = 'resetPassword';
            break;
    }

    $message = $channel->mailer->compose($template, [
        'user' => $this->user,
        'notification' => $this,
    ]);
    Yii::configure($message, $channel->message);

    $message->setTo($this->user->email);
    $message->setSubject($subject);
    $message->send($channel->mailer);
}
```

### Custom Channels 自定义渠道

该模块有一些预先建立的频道，但您可能想编写自己的频道来发送通知。 要做到这一点，你需要定义一个包含发送方法的类：

```php
namespace app\channels;

use yuncms\notifications\Channel;
use yuncms\notifications\Notification;

class VoiceChannel extends Channel
{
    /**
     * Send the given notification.
     *
     * @param Notification $notification
     * @return void
     */
    public function send(Notification $notification)
    {
        // use $notification->getTitle() ou $notification->getDescription();
        // Send your notification in this channel...
    }

}

```

你也应该在你的应用程序配置中配置频道:

```php
[
    'modules' => [
        'notifications' => [
            'class' => 'yuncms\notifications\Module',
            'channels' => [
                'voice' => [
                    'class' => 'app\channels\VoiceChannel',
                ],
                //...
            ],
        ],
    ],
]
```

### Database Channel  保存到数据库

此频道用于显示上述图片预览中的小通知。 通知将存储在数据库中，您可以在应用布局中调用Notifications小部件以显示生成的通知:

```php
<div class="header">
    ...
    <?php echo \yuncms\notifications\widgets\Notifications::widget() ?>
</div>
```