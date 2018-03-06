<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\filesystem\adapters;

use League\Flysystem\Config;
use yuncms\filesystem\Adapter;
use yuncms\filesystem\adapters\CosV5Adapter;
use yuncms\tests\TestCase;

class CosV5AdapterTest extends TestCase
{
    public function Provider()
    {
        $config = [
            'appId' => '',
            'accessId' => '',
            'accessSecret' => '',
            'bucket' => 'cosv5test',
            'domain' => '',
            'region' => 'gz',
            'timeout' => 60,
            'connectTimeout' => 10,
            'cdn' => 'http://cosv5test-1252025751.file.myqcloud.com',
        ];
        $adapter = (new CosV5Adapter($config));
        return [
            [$adapter]
        ];
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testWrite(Adapter $filesystem)
    {
        $this->assertTrue((bool)$filesystem->write('foo/foo.md', 'content'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testWriteStream(Adapter $filesystem)
    {
        $temp = tmpfile();
        fwrite($temp, 'writing to tempfile');
        $this->assertTrue((bool)$filesystem->writeStream('foo/bar.md', $temp));
        fclose($temp);
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testUpdate(Adapter $adapter)
    {
        $this->assertTrue((bool)$adapter->getAdapter()->update('foo/bar.md', uniqid(), new Config()));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testUpdateStream(Adapter $adapter)
    {
        $temp = tmpfile();
        fwrite($temp, 'writing to tempfile');
        $this->assertTrue((bool)$adapter->getAdapter()->updateStream('foo/bar.md', $temp, new Config()));
        fclose($temp);
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testRename(Adapter $adapter)
    {
        $this->assertTrue($adapter->rename('foo/foo.md', '/foo/rename.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testCopy(Adapter $adapter)
    {
        $this->assertTrue($adapter->copy('foo/bar.md', '/foo/copy.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testDelete(Adapter $adapter)
    {
        $this->assertTrue($adapter->delete('foo/rename.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testCreateDir(Adapter $adapter)
    {
        $this->assertTrue((bool)$adapter->getAdapter()->createDir('bar', new Config()));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testDeleteDir(Adapter $adapter)
    {
        $this->assertTrue($adapter->deleteDir('bar'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testSetVisibility(Adapter $adapter)
    {
        $this->assertTrue($adapter->setVisibility('foo/copy.md', 'private'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testHas(Adapter $adapter)
    {
        $this->assertTrue($adapter->has('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testRead(Adapter $adapter)
    {
        $this->assertArrayHasKey('contents', $adapter->read('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     *
     * @deprecated
     */
    public function testGetUrl(Adapter $adapter)
    {
        $this->assertContains(
            '/foo/bar.md',
            $adapter->getAdapter()->getUrl('foo/bar.md')
        );
    }

    /**
     * @dataProvider Provider
     *
     * @deprecated
     */
    public function testReadStream(Adapter $adapter)
    {
        $this->assertSame(
            stream_get_contents(fopen($adapter->getAdapter()->getUrl('foo/bar.md'), 'rb', false)),
            stream_get_contents($adapter->getAdapter()->readStream('foo/bar.md')['stream'])
        );
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testListContents(Adapter $adapter)
    {
        $this->assertArrayHasKey('Contents', $adapter->listContents('foo'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testGetMetadata(Adapter $adapter)
    {
        $this->assertArrayHasKey('ContentLength', $adapter->getMetadata('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testGetSize(Adapter $adapter)
    {
        $this->assertArrayHasKey('size', $adapter->getSize('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testGetMimetype(Adapter $adapter)
    {
        $this->assertNotSame(['mimetype' => ''], $adapter->getMimetype('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testGetTimestamp(Adapter $adapter)
    {
        $this->assertNotSame(['timestamp' => 0], $adapter->getTimestamp('foo/bar.md'));
    }

    /**
     * @dataProvider Provider
     * @expectedException \Qcloud\Cos\Exception\ServiceResponseException
     */
    public function testGetVisibility(Adapter $adapter)
    {
        $this->assertSame(['visibility' => 'private'], $adapter->getVisibility('foo/copy.md'));
    }
}
