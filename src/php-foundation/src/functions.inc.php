<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.06.16
     * Time: 17:42
     */
    


    use Gismo\Component\PhpFoundation\Accessor\FsFileAccessor;
    use Gismo\Component\PhpFoundation\Accessor\FsDirAccessor;
    use Gismo\Component\PhpFoundation\Accessor\IpAccessor;
    use Gismo\Component\PhpFoundation\Accessor\ObjectAccessor;
    use Gismo\Component\PhpFoundation\Accessor\PathAccessor;
    use Gismo\Component\PhpFoundation\Accessor\SomeAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StringAccessor;
    use Gismo\Component\PhpFoundation\Accessor\StructAccessor;
    use Gismo\Component\PhpFoundation\Accessor\TextAccessor;
    use Gismo\Component\PhpFoundation\Accessor\UrlAccessor;


    /**
     * @param $fileName
     * @return FsFileAccessor
     */
    function goFsFile($fileName) : FsFileAccessor {
        return new FsFileAccessor($fileName);
    }

    /**
     * @param $directory
     * @return FsDirAccessor
     */
    function goFsDir($directory) : FsDirAccessor {
        return new FsDirAccessor($directory);
    }

    /**
     * @param $data
     * @return SomeAccessor
     */
    function goSome(&$data) : SomeAccessor {
        return new SomeAccessor($data);
    }

    /**
     * @param $data
     * @return StructAccessor
     */
    function goStruct ($data) : StructAccessor {
        return new StructAccessor($data);
    }

    function goText ($data=null) : TextAccessor {
        return new TextAccessor($data, false, true);
    }

    /**
     * @param $path
     * @return PathAccessor
     */
    function goPath($path) {
        return new PathAccessor($path);
    }


    function goIp($ipAddress) : IpAccessor {
        return new IpAccessor($ipAddress);
    }


    /**
     * @param $url
     * @return UrlAccessor
     */
    function goUrl($url) : UrlAccessor {
        return new UrlAccessor($url);
    }

    /**
     * @param $string
     * @param bool $allowNull
     * @param int $maxLength
     * @return StringAccessor
     */
    function goString($string, $allowNull=true, $maxLength=StringAccessor::MAX_LENGTH) {
        return new StringAccessor($string, $allowNull, $maxLength);
    }

    /**
     * @param $data
     * @return ObjectAccessor
     */
    function goObject($data) {
        return new ObjectAccessor($data);
    }

    function goKey() {
        return new ExpectKeyArgument();    
    }
    
    function goValue() {
        return new ExpectValueArgument();
    }
    
    function goParam($index) {
        return new ExpectParameterArgument($index);
    }
    
    function goErrors () {
        return new ExpectExceptionsArgument();
    }
    
    