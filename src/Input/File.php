<?php namespace Input;

/**
 * Class File
 * some parts copied from symfony because they did it right.
 * Symfony\Component\HttpFoundation\File
 *
 * @package Input
 */
class File extends \SplFileInfo {

    /**
     * @var string
     */
    protected $uploadedName;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int
     */
    protected $error;

    /**
     * @var
     */
    public $newName;

    public function __construct($path, $uploadedName, $mimeType = null, $size = null, $error = null)
    {
        $this->uploadedName = $uploadedName;
        $this->mimeType     = $mimeType ?: 'application/octet-stream';
        $this->size         = $size;
        $this->error        = $error ?: UPLOAD_ERR_OK;
        parent::__construct($path);
    }

    /**
     * @param $filePath
     * @param null $newName
     * @param boolean $test
     * @throws \Exception
     * @return \SplFileInfo
     */
    public function move( $filePath, $newName = null, $test=false )
    {
        if( $this->hasError() ) {
            throw new \Exception( $this->getErrorMessage() );
        }

        if( $newName ) {
            $this->newName = $newName;
        }

        if( isset( $newName ) ) {
            $filePath .= DIRECTORY_SEPARATOR . $this->newName;
        } else {
            $filePath .= DIRECTORY_SEPARATOR . $this->getUploadedName();
        }

        if( $test ) {
            if (!@copy($this->getPathname(), $filePath )) {
                $error = error_get_last();
                throw new \Exception(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $filePath, strip_tags($error['message'])));
            }
            return new \SplFileInfo($filePath);
        }

        if( ! @move_uploaded_file( $this->getPathname(), $filePath ) ) {
            $error = error_get_last();
            throw new \Exception( sprintf('Could not move the file "%s" to "%s" (%s)',
                $this->getPathname(),
                $filePath,
                strip_tags($error['message'])
            ) );
        }

        return new \SplFileInfo($filePath);
    }

    /**
     * @param $name
     */
    public function setName( $name )
    {
        $this->newName = $name;
    }

    /**
     * @return int|null
     */
    public function getUploadedSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getUploadedMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getDetectedMimeType()
    {
        if( function_exists('mime_content_type') ) {
            return mime_content_type( $this->__toString() );
        }

        return null;
    }

    /**
     * @return string
     */
    public function getUploadedName()
    {
        return $this->uploadedName;
    }

    /**
     * @return string
     */
    public function getUploadedExtension()
    {
        return pathinfo($this->uploadedName, PATHINFO_EXTENSION);
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        if( $this->error === 0 ) {
            return false;
        }

        if( $this->error > 0 ) {
            return true;
        }

        return ! is_uploaded_file( $this->getPathname() );
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        static $errors = array(
            UPLOAD_ERR_INI_SIZE   => 'The file "%s" exceeds your upload_max_filesize ini directive (limit is %d kb).',
            UPLOAD_ERR_FORM_SIZE  => 'The file "%s" exceeds the upload limit defined in your form.',
            UPLOAD_ERR_PARTIAL    => 'The file "%s" was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_CANT_WRITE => 'The file "%s" could not be written on disk.',
            UPLOAD_ERR_NO_TMP_DIR => 'File could not be uploaded: missing temporary directory.',
            UPLOAD_ERR_EXTENSION  => 'File upload was stopped by a PHP extension.',
        );

        $errorCode   = $this->error;
        $maxFilesize = $errorCode === UPLOAD_ERR_INI_SIZE ? self::getMaxFilesize() / 1024 : 0;
        $message     = isset( $errors[$errorCode] ) ? $errors[$errorCode] : 'The file "%s" was not uploaded due to an unknown error.';

        return sprintf( $message, $this->getUploadedName(), $maxFilesize );
    }

    /**
     * Returns the maximum size of an uploaded file as configured in php.ini
     *
     * @param mixed $size
     * @return int The maximum size of an uploaded file in bytes
     */
    public static function getMaxFilesize( $size = null )
    {
        if( $size &&
            ( ctype_digit( $size ) || is_int( $size ) )
        ) {
            return $size;
        }


        if( $size ) {
            $iniMax = strtolower( $size );
        } else {
            $iniMax = strtolower( ini_get( 'upload_max_filesize' ) );

            if( '' === $iniMax ) {
                return PHP_INT_MAX;
            }
        }

        $max = ltrim( $iniMax, '+' );
        if( 0 === strpos( $max, '0x' ) ) {
            $max = intval( $max, 16 );
        } elseif( 0 === strpos( $max, '0' ) ) {
            $max = intval( $max, 8 );
        } else {
            $max = intval( $max );
        }

        switch( substr( $iniMax, -1 ) ) {
            case 't':
                $max *= 1024;
            case 'g':
                $max *= 1024;
            case 'm':
                $max *= 1024;
            case 'k':
                $max *= 1024;
        }

        return $max;
    }

    /**
     * @param array $extensions
     * @return bool
     */
    public function hasExtension( $extensions )
    {
        $ext = $this->getExtension();

        return in_array( $ext, (array) $extensions );
    }

    /**
     * @param array $mimeTypes
     * @return bool
     */
    public function hasMimeType( $mimeTypes )
    {
        $mimeType = $this->getDetectedMimeType();

        if( ! $mimeType ) {
            $mimeType = $this->getUploadedMimeType();
        }

        return in_array( $mimeType, (array) $mimeTypes );
    }

    /**
     * @param $size
     * @return bool
     */
    public function hasCorrectSize( $size )
    {
        return $this->getSize() <= self::getMaxFilesize($size);
    }

}