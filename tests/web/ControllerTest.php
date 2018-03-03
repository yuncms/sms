<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\web;

use Yii;
use yii\base\InlineAction;
use yuncms\tests\TestCase;
use yuncms\web\Response;

class ControllerTest extends TestCase
{
    /**
     * @var FakeController
     */
    protected $controller;

    protected function setUp()
    {
        parent::setUp();
        $this->controller = new FakeController('fake', new \yuncms\web\Application([
            'id' => 'app',
            'basePath' => __DIR__,

            'components' => [
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                ],
            ],
        ]));
        $this->mockWebApplication(['controller' => $this->controller]);
    }

    public function testBindActionParams()
    {
        $aksi1 = new InlineAction('aksi1', $this->controller, 'actionAksi1');

        $params = ['fromGet' => 'from query params', 'q' => 'd426', 'validator' => 'avaliable'];
        list($fromGet, $other) = $this->controller->bindActionParams($aksi1, $params);
        $this->assertEquals('from query params', $fromGet);
        $this->assertEquals('default', $other);

        $params = ['fromGet' => 'from query params', 'q' => 'd426', 'other' => 'avaliable'];
        list($fromGet, $other) = $this->controller->bindActionParams($aksi1, $params);
        $this->assertEquals('from query params', $fromGet);
        $this->assertEquals('avaliable', $other);
    }

    public function testAsJsonP()
    {
        $data = [
            'clallback' => 'example',
            'data' => ['test' => 123,
                'example' => 'data',
            ],
        ];
        $result = $this->controller->asJsonP($data);
        $this->assertInstanceOf('yii\web\Response', $result);
        $this->assertSame(Yii::$app->response, $result, 'response should be the same as Yii::$app->response');
        $this->assertEquals(Response::FORMAT_JSONP, $result->format);
        $this->assertEquals($data, $result->data);
    }
}