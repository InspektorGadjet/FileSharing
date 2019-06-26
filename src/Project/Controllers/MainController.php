<?php 

namespace Project\Controllers;

class MainController
{
	private $file;
	private $directory;

	public function __construct(\Slim\Http\UploadedFile $newfile, string $directory)
	{
		$this->file = $newfile;
		$this->directory = $directory;
	}

	public function main(\PDO $pdo)
	{
		$fileRecorder = new \Project\Models\FilesDataGateway($pdo);
		$fileManager = new \Project\Models\FileManager();
		$server_name = $fileManager->makeServerFileName();
		//Загрузка файла на сервер
		$fileManager->moveUploadedFile($this->directory, $this->file, $server_name);
		//Использование библиотеки getID3 для получения данных о файле
		$info = $fileManager->getInfoAboutFile($this->directory, $server_name);

		$fileParameters['real_name'] = $fileManager->getRealFileName($this->file);
		$fileParameters['server_name'] = $server_name;
		$fileParameters['mime_type'] = $info['mime_type'];
		$file = new \Project\Models\File($fileParameters);
		$fileRecorder->addFile($file);
		
		header('Location: /files');
	}
}