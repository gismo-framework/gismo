<?php
/**
 * Created by PhpStorm.
 * User: laurenz
 * Date: 25.07.16
 * Time: 12:31
 */

namespace Gismo\Test\Component;




use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
use Gismo\Component\PhpFoundation\Accessor\FsFileAccessor;

class FsFileAccessorTest extends \PHPUnit_Framework_TestCase {

    const EXISTING_FILE_NAME = "/tmp/existingFile";
    const EXISTING_FILE_CONTENTS = "Lorem ipsum dolor sit amet,\n consetetur sadipscing elitr ...";
    const NOT_EXISTING_FILE_NAME = "/tmp/notExistingFile";
    const EXISTING_DIR_WITH_NO_PERMISSIONS = "/tmp/notWritableDirectory";
    const EXISTING_FILE_WITH_NO_PERMISSIONS = self::EXISTING_DIR_WITH_NO_PERMISSIONS ."/someFile";


    public function setUp() {
        file_put_contents(self::EXISTING_FILE_NAME, self::EXISTING_FILE_CONTENTS);
        mkdir(self::EXISTING_DIR_WITH_NO_PERMISSIONS);
        touch(self::EXISTING_FILE_WITH_NO_PERMISSIONS);
        chmod(self::EXISTING_FILE_WITH_NO_PERMISSIONS, "0000");
    }

    public function tearDown() {
        if (file_exists(self::EXISTING_FILE_NAME)) {
            //unlink(self::EXISTING_FILE_NAME);
        }
        unlink(self::EXISTING_FILE_WITH_NO_PERMISSIONS);
        rmdir(self::EXISTING_DIR_WITH_NO_PERMISSIONS);
        parent::tearDown();
    }


    public function testWorksOnExistingFile() {
        
        $obj = goFsFile(self::EXISTING_FILE_NAME)->expectFileExists();
        $this->assertInstanceOf(FsFileAccessor::class, $obj);
    }


    public function testThrowsExceptionOnNotExistingFile() {
        $f = self::NOT_EXISTING_FILE_NAME;
        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to exist.");
        goFsFile($f)->expectFileExists();
    }


    public function testThrowsExceptionOnReadingOrWritingFileWhileNotExisting() {
        $f = self::NOT_EXISTING_FILE_NAME;

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to exist.");
        goFsFile($f)->expectFileReadable();

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to exist.");
        goFsFile($f)->expectFileWritable();
    }


    public function testThrowsExceptionOnReadingOrWritingFileWithNoPermissions() {
        $f = self::EXISTING_FILE_WITH_NO_PERMISSIONS;

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be readable.");
        goFsFile($f)->expectFileReadable();

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be writable.");
        goFsFile($f)->expectFileWritable();
    }


    public function testThrowsExceptionOnNotOpenedFile() {
        $f = self::EXISTING_FILE_NAME;
        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be opened.");
        goFsFile($f)->expectFileOpened();
    }


    public function testOpenFileCorrectly() {
        $f = self::EXISTING_FILE_NAME;
        goFsFile($f)->fopen("r")->expectFileOpened();
    }


    public function testCloseFileCorrectly() {
        $f = self::EXISTING_FILE_NAME;
        goFsFile($f)
            ->fopen("r")->expectFileOpened()
            ->fclose()->expectFileClosed();
    }


    public function testThrowsExceptionWhileClosingOnNotOpenedFile() {
        $f = self::EXISTING_FILE_NAME;

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be opened.");
        goFsFile($f)->fclose();

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be opened.");
        goFsFile($f)->fopen("r")->fclose()->fclose();
    }


    public function testUnlinkFile() {
        $f = self::EXISTING_FILE_NAME;
        \goFsFile($f)->unlink();

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to exist.");
        \goFsFile($f)->expectFileExists();

        $this->setExpectedException(ExpectationFailedException::class, "File '{$f}' was expected to be closed.");
        \goFsFile($f)->fopen("c")->unlink();
    }


    public function testFileWritingAndReading() {
        $file = \goFsFile(self::EXISTING_FILE_NAME);
        $this->assertEquals(self::EXISTING_FILE_CONTENTS, $file->getContents());

        $file->putContents("Bla und Blubb");
        $this->assertEquals("Bla und Blubb", $file->getContents());

        $length = strlen(self::EXISTING_FILE_CONTENTS);
        $file->fopen("w+"); // opens for reading and writing, content is truncated

        $file->fwrite(self::EXISTING_FILE_CONTENTS, $length)->fseek(0);
        $this->assertEquals(self::EXISTING_FILE_CONTENTS, $file->fread($length));

        $file->fputs(self::EXISTING_FILE_CONTENTS, $length)->fseek(0);
        $this->assertEquals(self::EXISTING_FILE_CONTENTS . self::EXISTING_FILE_CONTENTS, $file->fread($length*2));
        $this->assertEquals("Lorem ipsum dolor sit amet,\n", $file->fseek(0)->fgets());
    }


    public function testFileEndOFile() {
        $file = \goFsFile(self::EXISTING_FILE_NAME);
        $file->fopen("r");

        $this->assertFalse($file->feof());
        $file->fread(strlen(self::EXISTING_FILE_CONTENTS) + 1); // Carriage Return and/or New Line ;)
        $this->assertTrue($file->feof());
    }


    public function testDetermineFileModificationTime() {
        $file = \goFsFile(self::EXISTING_FILE_NAME);
        $this->assertEquals(time(), $file->filemtime());
    }


    public function testDetermineFileSize() {
        $file = \goFsFile(self::EXISTING_FILE_NAME);
        $this->assertEquals($length = strlen(self::EXISTING_FILE_CONTENTS), $file->filesize());

    }


    public function testPutAndGetCsvData() {
        $list = [
            ['aaa', 'bbb', 'ccc', 'dddd'],
            ['123', '456', '789'],
            ['"aaa"', '"bbb"']
        ];

        $file = \goFsFile(self::EXISTING_FILE_NAME);
        $file->fopen("w+");

        foreach ($list as $fields) {
            $file->fputcsv($fields);
        }

        $csvFileContentRaw = <<<EOT
aaa,bbb,ccc,dddd
123,456,789
"""aaa""","""bbb"""

EOT;
        $file->fseek(0);
        $this->assertEquals($csvFileContentRaw, $file->fread(strlen($csvFileContentRaw)));

        $file->fseek(0);
        $results = [ $file->fgetcsv(), $file->fgetcsv(), $file->fgetcsv(), $file->fgetcsv() ];

        $this->assertEquals(count($list[0]), count($results[0]));
        $this->assertEquals(count($list[1]), count($results[1]));
        $this->assertEquals(count($list[2]), count($results[2]));
        $this->assertEquals(0,               count($results[3]));

        $this->assertEquals(implode(";", $list[0]), implode(";", $results[0]));
        $this->assertEquals(implode(";", $list[1]), implode(";", $results[1]));
        $this->assertEquals(implode(";", $list[2]), implode(";", $results[2]));
    }

}
