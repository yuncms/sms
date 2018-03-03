<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\tests\web;

use yuncms\web\Request;
use yuncms\tests\TestCase;

class RequestTest extends TestCase
{
    public function getHostInfoDataProvider()
    {
        return [
            // empty
            [
                [],
                [null, null]
            ],
            // normal
            [
                [
                    'HTTP_HOST' => 'example1.com',
                    'SERVER_NAME' => 'example2.com',
                ],
                [
                    'http://example1.com',
                    'example1.com',
                ]
            ],
            // HTTP header missing
            [
                [
                    'SERVER_NAME' => 'example2.com',
                ],
                [
                    'http://example2.com',
                    'example2.com',
                ]
            ],
            // forwarded from untrusted server
            [
                [
                    'HTTP_X_FORWARDED_HOST' => 'example3.com',
                    'HTTP_HOST' => 'example1.com',
                    'SERVER_NAME' => 'example2.com',
                ],
                [
                    'http://example1.com',
                    'example1.com',
                ]
            ],
            // forwarded from trusted proxy
            [
                [
                    'HTTP_X_FORWARDED_HOST' => 'example3.com',
                    'HTTP_HOST' => 'example1.com',
                    'SERVER_NAME' => 'example2.com',
                    'REMOTE_ADDR' => '192.168.0.1',
                ],
                [
                    'http://example3.com',
                    'example3.com',
                ]
            ],
        ];
    }

    /**
     * @dataProvider getHostInfoDataProvider
     * @param array $server
     * @param array $expected
     */
    public function testGetHostInfo($server, $expected)
    {
        $original = $_SERVER;
        $_SERVER = $server;
        $request = new Request([
            'trustedHosts' => [
                '192.168.0.0/24',
            ],
        ]);

        $this->assertEquals($expected[0], $request->getHostInfo());
        $this->assertEquals($expected[1], $request->getHostName());
        $_SERVER = $original;
    }


    public function testSetHostInfo()
    {
        $request = new Request();

        unset($_SERVER['SERVER_NAME'], $_SERVER['HTTP_HOST']);
        $this->assertNull($request->getHostInfo());
        $this->assertNull($request->getHostName());

        $request->setHostInfo('http://servername.com:80');
        $this->assertSame('http://servername.com:80', $request->getHostInfo());
        $this->assertSame('servername.com', $request->getHostName());
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testGetScriptFileWithEmptyServer()
    {
        $request = new Request();
        $_SERVER = [];

        $request->getScriptFile();
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testGetScriptUrlWithEmptyServer()
    {
        $request = new Request();
        $_SERVER = [];

        $request->getScriptUrl();
    }

    public function testGetServerName()
    {
        $request = new Request();

        $_SERVER['SERVER_NAME'] = 'servername';
        $this->assertEquals('servername', $request->getServerName());

        unset($_SERVER['SERVER_NAME']);
        $this->assertNull($request->getServerName());
    }

    public function testGetServerPort()
    {
        $request = new Request();

        $_SERVER['SERVER_PORT'] = 33;
        $this->assertEquals(33, $request->getServerPort());

        unset($_SERVER['SERVER_PORT']);
        $this->assertNull($request->getServerPort());
    }

    public function isSecureServerDataProvider()
    {
        return [
            [['HTTPS' => 1], true],
            [['HTTPS' => 'on'], true],
            [['HTTPS' => 0], false],
            [['HTTPS' => 'off'], false],
            [[], false],
            [['HTTP_X_FORWARDED_PROTO' => 'https'], false],
            [['HTTP_X_FORWARDED_PROTO' => 'http'], false],
            [[
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'REMOTE_HOST' => 'test.com',
            ], false],
            [[
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'REMOTE_HOST' => 'othertest.com',
            ], false],
            [[
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'REMOTE_ADDR' => '192.168.0.1',
            ], true],
            [[
                'HTTP_X_FORWARDED_PROTO' => 'https',
                'REMOTE_ADDR' => '192.169.0.1',
            ], false],
            [['HTTP_FRONT_END_HTTPS' => 'on'], false],
            [['HTTP_FRONT_END_HTTPS' => 'off'], false],
            [[
                'HTTP_FRONT_END_HTTPS' => 'on',
                'REMOTE_HOST' => 'test.com',
            ], false],
            [[
                'HTTP_FRONT_END_HTTPS' => 'on',
                'REMOTE_HOST' => 'othertest.com',
            ], false],
            [[
                'HTTP_FRONT_END_HTTPS' => 'on',
                'REMOTE_ADDR' => '192.168.0.1',
            ], true],
            [[
                'HTTP_FRONT_END_HTTPS' => 'on',
                'REMOTE_ADDR' => '192.169.0.1',
            ], false],
        ];
    }

    /**
     * @dataProvider isSecureServerDataProvider
     * @param array $server
     * @param bool $expected
     */
    public function testGetIsSecureConnection($server, $expected)
    {
        $original = $_SERVER;
        $request = new Request([
            'trustedHosts' => [
                '192.168.0.0/24',
            ],
        ]);
        $_SERVER = $server;

        $this->assertEquals($expected, $request->getIsSecureConnection());
        $_SERVER = $original;
    }

    public function getUserIPDataProvider()
    {
        return [
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'https',
                    'HTTP_X_FORWARDED_FOR' => '123.123.123.123',
                    'REMOTE_ADDR' => '192.168.0.1',
                ],
                '123.123.123.123',
            ],
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'https',
                    'HTTP_X_FORWARDED_FOR' => '123.123.123.123',
                    'REMOTE_ADDR' => '192.169.1.1',
                ],
                '192.169.1.1',
            ],
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'https',
                    'HTTP_X_FORWARDED_FOR' => '123.123.123.123',
                    'REMOTE_HOST' => 'untrusted.com',
                    'REMOTE_ADDR' => '192.169.1.1',
                ],
                '192.169.1.1',
            ],
            [
                [
                    'HTTP_X_FORWARDED_PROTO' => 'https',
                    'HTTP_X_FORWARDED_FOR' => '192.169.1.1',
                    'REMOTE_HOST' => 'untrusted.com',
                    'REMOTE_ADDR' => '192.169.1.1',
                ],
                '192.169.1.1',
            ],
        ];
    }

    /**
     * @dataProvider getUserIPDataProvider
     * @param array $server
     * @param string $expected
     */
    public function testGetUserIP($server, $expected)
    {
        $original = $_SERVER;
        $_SERVER = $server;
        $request = new Request([
            'trustedHosts' => [
                '192.168.0.0/24',
            ],
        ]);

        $this->assertEquals($expected, $request->getUserIP());
        $_SERVER = $original;
    }

    public function getMethodDataProvider()
    {
        return [
            [
                [
                    'REQUEST_METHOD' => 'DEFAULT',
                    'HTTP_X-HTTP-METHOD-OVERRIDE' => 'OVERRIDE',
                ],
                'OVERRIDE',
            ],
            [
                [
                    'REQUEST_METHOD' => 'DEFAULT',
                ],
                'DEFAULT',
            ],
        ];
    }

    /**
     * @dataProvider getMethodDataProvider
     * @param array $server
     * @param string $expected
     */
    public function testGetMethod($server, $expected)
    {
        $original = $_SERVER;
        $_SERVER = $server;
        $request = new Request();

        $this->assertEquals($expected, $request->getMethod());
        $_SERVER = $original;
    }
}
