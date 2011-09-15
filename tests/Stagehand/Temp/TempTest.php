<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * PHP version 5
 *
 * Copyright (c) 2011 KUMAKURA Yousuke <kumatch@gmail.com>,
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Stagehand_Temp
 * @copyright  2011 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 1.0.0
 */

namespace Stagehand\Temp;

use Stagehand\Temp\Temp;

/**
 * @package    Stagehand_Temp
 * @copyright  2011 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 1.0.0
 */
class TempTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }


    /**
     * @test
     */
    public function openNewTemporaryFile()
    {
        $temp = new Temp();
        $file1 = $temp->open();

        $this->assertInstanceOf('\Stagehand\Temp\FileInfo', $file1);
        $this->assertTrue(file_exists($file1));
        $this->assertTrue(is_file($file1));
        $this->assertEquals(filesize($file1), 0);

        $file2 = $temp->open();

        $this->assertInstanceOf('\Stagehand\Temp\FileInfo', $file2);
        $this->assertTrue(file_exists($file2));
        $this->assertTrue(is_file($file2));
        $this->assertEquals(filesize($file2), 0);

        $this->assertNotSame($file1, $file2);
        $this->assertNotEquals($file1->getPathname(), $file2->getPathname());
    }

    /**
     * @test
     */
    public function MakeNewTemporaryDirectory()
    {
        $temp = new Temp();
        $dir1 = $temp->mkdir();

        $this->assertInstanceOf('\Stagehand\Temp\FileInfo', $dir1);
        $this->assertTrue(file_exists($dir1));
        $this->assertTrue(is_dir($dir1));

        $dir2 = $temp->mkdir();

        $this->assertInstanceOf('\Stagehand\Temp\FileInfo', $dir2);
        $this->assertTrue(file_exists($dir2));
        $this->assertTrue(is_dir($dir2));

        $this->assertNotSame($dir1, $dir2);
        $this->assertNotEquals($dir1->getPathname(), $dir2->getPathname());
    }

    /**
     * @test
     */
    public function createTemporaryOnSpecificDirectory()
    {
        $specificDirectory = __DIR__ . '/temporary';

        $temp = new Temp($specificDirectory);
        $file = $temp->open();
        $dir = $temp->mkdir();

        $this->assertTrue(file_exists($file));
        $this->assertTrue(file_exists($dir));
        $this->assertEquals(dirname($file), $specificDirectory);
        $this->assertEquals(dirname($dir), $specificDirectory);
    }

    /**
     * @test
     * @expectedException \Stagehand\Temp\Exception
     */
    public function raiseExceptionIfSpecificDirectoryIsNotExists()
    {
        $specificDirectory = __DIR__ . '/invalid_path';
        if (file_exists($specificDirectory)) {
            throw new \Exception();
        }

        $temp = new Temp($specificDirectory);
    }

    /**
     * @test
     */
    public function createTemporaryWithSpecificPrefix()
    {
        $prefix = 'temptest';

        $temp = new Temp();
        $file = $temp->open($prefix);
        $dir = $temp->mkdir($prefix);

        $this->assertTrue(file_exists($file));
        $this->assertTrue(file_exists($dir));
        $this->assertEquals(strpos(basename($file), $prefix), 0);
        $this->assertEquals(strpos(basename($dir), $prefix), 0);
    }
}
