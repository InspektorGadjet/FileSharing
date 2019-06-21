<?php 

namespace Project\Controllers;

class MainController
{
	private $file;
	private $app;
	private $directory;

	public function __construct(\Slim\Http\UploadedFile $newfile, $app, $directory)
	{
		$this->file = $newfile;
		$this->app = $app;
		$this->directory = $directory;
	}

	public function main()
	{
		$uploader = new \Project\Models\Uploader($this->file, $this->app);
		$uploader->upload($this->directory, $this->file);
	}
}