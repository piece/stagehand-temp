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

use Stagehand\Temp\FileInfo;

/**
 * @package    Stagehand_Temp
 * @copyright  2011 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 1.0.0
 */
class FileInfoTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $this->temporary = __DIR__ . '/temporary';
    }

    protected function tearDown()
    {
    }


    /**
     * @test
     */
    public function dropTemporaryFile()
    {
        $path = $this->temporary . '/file';
        touch($path);

        $file = new FileInfo($path);

        $this->assertTrue(file_exists($path));
        $this->assertTrue($file->isFile());

        $file->drop();

        $this->assertFalse(file_exists($path));
        $this->assertFalse($file->isFile());
    }

    /**
     * @test
     */
    public function dropTemporaryDirectory()
    {
        $path = $this->temporary . '/dir';
        mkdir($path, 0777, true);

        $dir = new FileInfo($path);

        $this->assertTrue(file_exists($dir));
        $this->assertTrue($dir->isDir());

        touch($path . '/file');
        mkdir($path . '/dir2');
        touch($path . '/dir2/file2');

        $dir->drop();

        $this->assertFalse(file_exists($path));
        $this->assertFalse($dir->isDir());
    }
}
