<?php 

namespace Project\Controllers;

class MainController
{
	private $file;
	private $directory;

	public function __construct(\Slim\Http\UploadedFile $newfile, $directory)
	{
		$this->file = $newfile;
		$this->directory = $directory;
	}

	public function main(\PDO $pdo)
	{
		$fileRecorder = new \Project\Models\FilesDataGateway($pdo);
		$fileManager = new \Project\Models\FileManager();
		$server_name = $fileManager->makeServerFileName();
		$fileManager->moveUploadedFile($this->directory, $this->file, $server_name);
		$info = $fileManager->getInfoAboutFile($this->directory, $server_name);

		$fileParameters['real_name'] = $fileManager->getRealFileName($this->file);
		$fileParameters['server_name'] = $server_name;
		$fileParameters['mime_type'] = $info['mime_type'];
		$file = new \Project\Models\File($fileParameters);
		return $file;
	}
}