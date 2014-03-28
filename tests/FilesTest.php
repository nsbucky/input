<?php

use Input\Files,
    Input\File;

class FilesTest extends PHPUnit_Framework_TestCase {

    public $sampleFilesDirectory;
    public $sampleMoveDirectory;

    /**
     * @var \Input\Files
     */
    public $files;

    public function setUp()
    {
        $this->sampleFilesDirectory = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'files';
        $this->sampleMoveDirectory  = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'move';

        $_FILES['one'] = [
            'name'     => 'hero.jpg',
            'tmp_name' => $this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'hero.jpg',
            'error'    => UPLOAD_ERR_OK,
            'size'     => filesize($this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'hero.jpg'),
            'type'     => 'image/jpeg'
        ];

        $_FILES['bad'] = [
            'name'     => 'hero.jpg',
            'tmp_name' => $this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'hero.jpg',
            'error'    => UPLOAD_ERR_INI_SIZE,
            'size'     => filesize($this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'hero.jpg'),
            'type'     => 'image/jpeg'
        ];

        $_FILES['many'] = [
            'name' => [
                'micro.jpg',
                'sample.pdf',
            ],
            'tmp_name' => [
                $this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'micro.jpg',
                $this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'sample.pdf',
            ],
            'error' => [
                UPLOAD_ERR_OK,
                UPLOAD_ERR_OK
            ],
            'type' => [
                'image/jpeg',
                'application/pdf'
            ],
            'size' => [
                filesize($this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'micro.jpg'),
                filesize($this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'sample.pdf'),
            ]
        ];

        $_FILES['validate'] = [
            'name'     => 'posting-doc.csv',
            'tmp_name' => $this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'posting-doc.csv',
            'error'    => UPLOAD_ERR_OK,
            'size'     => filesize($this->sampleFilesDirectory . DIRECTORY_SEPARATOR . 'posting-doc.csv'),
            'type'     => 'text/csv'
        ];

        $this->files = new Files();
    }

    public function testSingleFile()
    {

        $this->assertTrue( $this->files->has('one') );

        $this->assertInstanceOf("\\SplFileInfo", $this->files->get('one'));
    }

    public function testMultipleFiles()
    {

        $this->assertTrue( $this->files->has('many') );

        $upload = $this->files->get('many');

        $this->assertTrue( (bool) is_array( $upload ) );

        $this->assertEquals( 2, count($upload) );
    }

    public function testExtension()
    {

        $file = $this->files->get('validate');

        $this->assertEquals( 'csv', $file->getExtension() );

        $this->assertTrue( $file->hasExtension(['csv']) );

    }

    public function testMimeType()
    {

        $file = $this->files->get('validate');

        $this->assertTrue( $file->hasMimeType(['text/csv']) );
    }

    public function testHasCorrectSize()
    {
        $file = $this->files->get('validate');
        $this->assertTrue( $file->hasCorrectSize( '1M' ) );
    }

    public function testHasError()
    {
        $file = $this->files->get('bad');
        $this->assertTrue( $file->hasError() );
        #echo $file->getErrorMessage();
    }

    public function testMoveFile()
    {
        $file = $this->files->get('one');

        $newFile = $file->move( $this->sampleMoveDirectory, null, true );

        $this->assertInstanceOf("\\SplFileInfo", $newFile );

        $this->assertEquals( $this->sampleMoveDirectory . DIRECTORY_SEPARATOR . $file->getUploadedName(), $newFile->getPathname() );

        $this->assertTrue( file_exists( $newFile->getPathname() ));

        echo $newFile->getPathname();

        @unlink($newFile->getPathname());
    }

    public function testMoveFileWithNewName()
    {
        $file = $this->files->get('one');

        $newFile = $file->move( $this->sampleMoveDirectory, 'nutballs.jpg', true );

        $this->assertInstanceOf("\\SplFileInfo", $newFile );

        $this->assertEquals( $this->sampleMoveDirectory . DIRECTORY_SEPARATOR . 'nutballs.jpg', $newFile->getPathname() );

        $this->assertTrue( file_exists( $newFile->getPathname() ));

        @unlink($newFile->getPathname());
    }
}