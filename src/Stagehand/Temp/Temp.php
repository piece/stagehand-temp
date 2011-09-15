<?php
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
use Stagehand\Temp\Exception;

/**
 * @package    Stagehand_Temp
 * @copyright  2011 KUMAKURA Yousuke <kumatch@gmail.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @version    Release: @package_version@
 * @since      File available since Release 1.0.0
 */
class Temp
{
    /**
     * @var boolean
     */
    public static $autoDrop = true;

    /**
     * @var string
     */
    protected $_temporaryDirectory;

    /**
     * @var array
     */
    protected static $_temporaries = array();

    /**
     * Constructor
     *
     * @param $temporaryDirectory
     * @throws \Stagehand\Temp\Exception
     */
    public function __construct($temporaryDirectory = null)
    {
        if (is_null($temporaryDirectory)) {
            $this->_temporaryDirectory = sys_get_temp_dir();
        } else {
            $temporaryDirectory = realpath($temporaryDirectory);
            if (!$temporaryDirectory) {
                throw new Exception();
            }

            if (!file_exists($temporaryDirectory)
                || !is_dir($temporaryDirectory)
                ) {
                throw new Exception();
            }

            $this->_temporaryDirectory = $temporaryDirectory;
        }
    }



    /**
     * Open new temporary file.
     *
     * @param string $prefix
     * @return string
     */
    public function open($prefix = null)
    {
        $path = $this->_generateUniquePath($prefix);
        if (!touch($path)) {
            throw new Exception('Permission denied [' . $this->_temporaryDirectory . ']');
        }

        chmod($path, 0600);

        $tmp = new FileInfo($path);
        array_push(self::$_temporaries, $tmp);

        return $tmp;
    }

    /**
     * Make new temporary directory.
     *
     * @param string $prefix
     * @return string
     */
    public function mkdir($prefix = null)
    {
        $path = $this->_generateUniquePath($prefix);
        if (!mkdir($path, 0700, true)) {
            throw new Exception('Permission denied [' . $this->_temporaryDirectory . ']');
        }

        $tmp = new FileInfo($path);
        array_push(self::$_temporaries, $tmp);

        return $tmp;
    }


    /**
     * Generates a unique path.
     *
     * @param string $prefix
     * @return string
     */
    public function _generateUniquePath($prefix = null)
    {
        $unique = false;

        while (!$unique) {
            $path = $this->_temporaryDirectory . '/' . $this->_generateName($prefix);
            if (!file_exists($path)) {
                $unique = true;
            }
        }

        return $path;
    }


    /**
     * Generates a random name.
     *
     * @param string $prefix
     * @return string
     */
    protected function _generateName($prefix = null)
    {
        $a = base_convert(microtime(true), 10, 36);
        $b = base_convert((mt_rand() / mt_getrandmax()), 10, 36);
        $c = base_convert((mt_rand() / mt_getrandmax()), 10, 36);

        return $prefix . $a . $b . $c;
    }
}
