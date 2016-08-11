<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 17:41
     */

    namespace Gismo\Component\PhpFoundation\Accessor;


   
    use Gismo\Component\PhpFoundation\Accessor\Ex\ExpectationFailedException;
    use Gismo\Component\PhpFoundation\Exception\PfException;

    class FsFileAccessor extends AbstractAccessor {


        private $mFileHandle = NULL;


        /**
         * FsFileAccessor constructor.
         * @param string $filename
         */
        public function __construct(string $filename) {
            parent::__construct($filename);
        }

        public function __destruct() {
            if ($this->mFileHandle !== NULL) {
                $this->fclose();
            }
        }

        /**
         * @return FsFileAccessor
         * @throws ExpectationFailedException
         */
        public function expectFileExists() :self {
            if ( ! $this->isFileExisting()) {
                throw new ExpectationFailedException(["File ? was expected to exist.", $this->reference]);
            }
            return $this;
        }

        /**
         * @return bool
         */
        public function isFileExisting() :bool {
            return file_exists($this->reference);
        }

        /**
         * @return $this
         * @throws ExpectationFailedException
         */
        public function expectFileReadable() {
            $this->expectFileExists();
            if ( ! $this->isFileReadable()) {
                throw new ExpectationFailedException(["File ? was expected to be readable.", $this->reference]);
            }
            return $this;
        }

        /**
         * @return bool
         */
        public function isFileReadable() :bool {
            if ( ! $this->isFileExisting()) {
                return FALSE;
            }
            return is_readable($this->reference);
        }

        /**
         * @return $this
         * @throws ExpectationFailedException
         */
        public function expectFileWritable() {
            $this->expectFileExists();
            if ( ! $this->isFileReadable()) {
                throw new ExpectationFailedException(["File ? was expected to be writable.", $this->reference]);
            }
            return $this;
        }

        /**
         * @return bool
         */
        public function isFileWritable() :bool {
            if ( ! $this->isFileExisting()) {
                $directory = basename($this->reference);
                if ( ! is_writable($directory)) {
                    return FALSE;
                }
            }
            return is_writable($this->reference);
        }

        /**
         * @param $mode
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fopen($mode) :self {
            if ($this->mFileHandle !== NULL) {
                throw new PfException(["File ? was already opened using fopen().", $this->reference]);
            }
            $handle = fopen($this->reference, $mode);
            if ($handle === NULL || $handle === FALSE) {
                throw new PfException(["Cannot fopen file ? (Mode: ?)", $this->reference, $mode], NULL, $this->reference);
            }
            $this->mFileHandle = $handle;
            return $this;
        }

        /**
         * @return FsFileAccessor
         * @throws ExpectationFailedException
         */
        public function expectFileOpened() :self {
            if ($this->mFileHandle === NULL) {
                throw new ExpectationFailedException(["File ? was expected to be opened.", $this->reference]);
            }
            return $this;
        }

        /**
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fclose() :self {
            $this->expectFileOpened();
            if ( ! fclose($this->mFileHandle)) {
                throw new PfException(["Cannot fclose file ?", $this->reference]);
            }
            $this->mFileHandle = NULL;
            return $this;
        }

        /**
         * @return FsFileAccessor
         * @throws ExpectationFailedException
         */
        public function expectFileClosed() :self {
            if ($this->mFileHandle !== NULL) {
                throw new ExpectationFailedException(["File ? was expected to be closed.", $this->reference]);
            }
            return $this;
        }

        /**
         * @return FsFileAccessor
         * @throws PfException
         */
        public function unlink() :self {
            $this->expectFileExists();
            $this->expectFileClosed();
            if ( ! unlink($this->reference)) {
                throw new PfException(["Cannot unlink file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * @param string $destination
         * @param resource|NULL $context
         * @return FsFileAccessor
         * @throws PfException
         */
        public function copy(string $destination, resource $context=NULL) :self {
            $destination = new self($destination);
            $destination->expectFileWritable();
            $this->expectFileExists();
            $this->expectFileClosed();
            if ( ! copy($this->reference, $destination, $context)) {
                throw new PfException(["Cannot copy file ? to destination ?", $this->reference, $destination]);
            }
            return $this;
        }

        /**
         * @param string $newFileName
         * @param resource|NULL $context
         * @return FsFileAccessor
         * @throws PfException
         */
        public function rename(string $newFileName, resource $context=NULL) :self {
            $this->expectFileExists();
            $this->expectFileClosed();
            if ( ! rename($this->reference, $newFileName, $context)) {
                throw new PfException(["Cannot copy file ? to destination ?", $this->reference, $newFileName]);
            }
            $this->reference = $newFileName;
            return $this;
        }

        /**
         * @return string
         * @throws PfException
         */
        public function getContents() :string {
            $this->expectFileExists();
            $this->expectFileClosed();
            $content = file_get_contents($this->reference);
            if ($content === FALSE) {
                throw new PfException(["Cannot get contents of file ?", $this->reference]);
            }
            return $content;
        }

        /**
         * @param string $data
         * @return FsFileAccessor
         * @throws PfException
         */
        public function putContents(string $data) :self {
            $this->expectFileWritable();
            $this->expectFileClosed();
            if ( ! file_put_contents($this->reference, $data)) {
                throw new PfException(["Cannot put contents to file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * Read raw data
         * @param int $length
         * @return string
         * @throws PfException
         */
        public function fread(int $length) :string {
            $this->expectFileReadable();
            $this->expectFileOpened();
            if (($line = fread($this->mFileHandle, $length)) === FALSE) {
                throw new PfException(["Cannot put contents to file ?", $this->reference]);
            }
            return $line;
        }

        /**
         * Read a line
         * @return string
         * @throws PfException
         */
        public function fgets() :string {
            $this->expectFileReadable();
            $this->expectFileOpened();
            if (($line = fgets($this->mFileHandle)) === FALSE) {
                throw new PfException(["Cannot get a line of file ?", $this->reference]);
            }
            return $line;
        }

        /**
         * @param string $string
         * @param int|NULL $length
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fwrite(string $string, int $length=NULL) :self {
            $this->expectFileWritable();
            $this->expectFileOpened();
            if ( ! fwrite($this->mFileHandle, $string, $length)) {
                throw new PfException(["Cannot write string to file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * @param string $string
         * @param int $length
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fputs(string $string, int $length) :self {
            $this->expectFileWritable();
            $this->expectFileOpened();
            if ( ! fputs($this->mFileHandle, $string, $length)) {
                throw new PfException(["Cannot write string to file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * @param int $length
         * @param string $delimiter
         * @param string $enclosure
         * @param string $escapeChar
         * @return array
         */
        public function fgetcsv(int $length=0, string $delimiter=",", string $enclosure='"', string $escapeChar = "\\") :array {
            $this->expectFileReadable();
            $this->expectFileOpened();
            if (($array = fgetcsv($this->mFileHandle, $length, $delimiter, $enclosure, $escapeChar)) === FALSE) {
                $array = [];
            }
            return $array;
        }

        /**
         * @param array $fields
         * @param string $delimiter
         * @param string $enclosure
         * @param string $escapeChar
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fputcsv(array $fields, string $delimiter=",", string $enclosure='"', string $escapeChar = "\\") :self {
            $this->expectFileWritable();
            $this->expectFileOpened();
            if ( ! fputcsv($this->mFileHandle, $fields, $delimiter, $enclosure, $escapeChar)) {
                throw new PfException(["Cannot write string to file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * @param int $offset
         * @return $this
         * @throws PfException
         */
        public function fseek(int $offset) {
            $this->expectFileReadable();
            $this->expectFileOpened();
            if (fseek($this->mFileHandle, $offset) != 0) {
                throw new PfException(["Cannot seek to offset ? in file ?", $offset, $this->reference]);
            }
            return $this;
        }

        /**
         * @return bool
         */
        public function feof() :bool {
            $this->expectFileReadable();
            $this->expectFileOpened();
            return feof($this->mFileHandle);
        }

        /**
         * @return FsFileAccessor
         * @throws PfException
         */
        public function fflush() :self {
            $this->expectFileWritable();
            if ( ! fflush($this->mFileHandle)) {
                throw new PfException(["Cannot flush file ?", $this->reference]);
            }
            return $this;
        }

        /**
         * @return float
         * @throws PfException
         */
        public function filesize() :float {
            $this->expectFileExists();
            if (($bytes = filesize($this->reference)) === FALSE) {
                throw new PfException(["Cannot determine size of file ?", $this->reference]);
            }
            return (float)$bytes;
        }

        /**
         * @return int
         */
        public function filemtime() :int {
            $this->expectFileExists();
            return filemtime($this->reference);
        }

    }
