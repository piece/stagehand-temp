A temporary files and directories utility for PHP
======

Generates a unique file/directory under the system temporary directory or specific directory.
And supports auto drop in shutdown sequence.

Usage
--------

`open()` creates a temporary file, and `mkdir()` creates a directory.
Any temporary file/directory are instance of Stagehand\Temp\FileInfo extends SplFileInfo.

    <?php
    use Stagehand\Temp\Temp;
    
    $temp = new Temp();
    $file = $temp->open();
    $dir  = $temp->mkdir();


In this case, creates a temp file under `/path/to/temporary` directory.

    $temp = new Temp('/path/to/temporary');
    $file = $temp->open();


If uses `open()` and `mkdir()` with prefix argument, temporaries will be name with prefix value.

    $temp = new Temp();
    $file = $temp->open('mytemporary');
