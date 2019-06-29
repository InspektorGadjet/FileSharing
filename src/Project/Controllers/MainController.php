<?php 

namespace Project\Controllers;

class MainController
{
	private $file;
	private $directory;

	public function __construct(\Slim\Http\UploadedFile $newfile, string $directory, string $copy_directory)
	{
		$this->file = $newfile;
		$this->directory = $directory;
		$this->copy_directory = $copy_directory;
	}

	public function main(\PDO $pdo)
	{
		$fileRecorder = new \Project\Models\FilesDataGateway($pdo);
		$fileManager = new \Project\Models\FileManager();
		$server_name = $fileManager->makeServerFileName();
		//Загрузка файла на сервер
		$fileManager->moveUploadedFile($this->directory, $this->file, $server_name);
		//Использование библиотеки getID3 для получения данных о файле
		$source_path = $this->directory . DIRECTORY_SEPARATOR . $server_name;
		$copy_path = $this->copy_directory . DIRECTORY_SEPARATOR . $server_name;
		
		$fileManager->resize($source_path, $copy_path, 100);
		$info = $fileManager->getInfoAboutFile($this->directory, $server_name);
		$fileParameters['real_name'] = $fileManager->getRealFileName($this->file);
		$fileParameters['server_name'] = $server_name;
		$fileParameters['mime_type'] = $info['mime_type'];
		$file = new \Project\Models\File($fileParameters);
		$fileRecorder->addFile($file);
		
		header('Location: /files');
	}
}