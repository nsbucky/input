<?php namespace Input;

require_once 'AccessTrait.php';

/**
 * file upload class.
 * Class Files
 * @package Input
 */
class Files implements InputInterface {

    use AccessTrait;

    public function __construct( array $files = [] )
    {
        $files = count( $files ) > 0 ? : $_FILES;
        $this->processFiles( $files );
    }

    /**
     * @param $uploadedFiles
     */
    protected function processFiles( $uploadedFiles )
    {
        foreach( $uploadedFiles as $key => $fileInfo ) {

            if( is_array( $fileInfo['name'] ) ) {
                // how many files are there?
                $numFiles          = count( $fileInfo['name'] );
                $this->input[$key] = [ ];
                for( $i = 0; $i < $numFiles; $i++ ) {
                    $this->input[$key][] = new File( $fileInfo['tmp_name'][$i], $fileInfo['name'][$i], $fileInfo['type'][$i], $fileInfo['size'][$i], $fileInfo['error'][$i] );
                }

                continue;
            }

            $this->input[$key] = new File( $fileInfo['tmp_name'], $fileInfo['name'], $fileInfo['type'], $fileInfo['size'], $fileInfo['error'] );
        }

    }

}