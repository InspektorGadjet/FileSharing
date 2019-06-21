<?php

namespace Project\Models;

class Uploader
{	
	private $app;

	public function __construct($file, $app)
	{
		$this->app = $app;
	}

	public function getName()
	{
		return $this->file->name;
	}

	public function upload($directory, $file)
	{
		$filename = $this->moveUploadedFile($directory, $file);
		return $filename;
	}

	public function moveUploadedFile($directory, $uploadedFile)
	{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
	}
}