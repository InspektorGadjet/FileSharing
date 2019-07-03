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

	public function main(\PDO $pdo): void
	{
		$fileRecorder = new \Project\Models\FilesDataGateway($pdo);
		$fileManager = new \Project\Models\FileManager();
		$server_name = $fileManager->makeServerFileName();

		//Загрузка файла на сервер
		$fileManager->moveUploadedFile($this->directory, $this->file, $server_name);

		//Использование библиотеки getID3 для получения данных о файле
		$file_name = $fileManager->getRealFileName($this->file);

		$info = $fileManager->getInfoAboutFile($this->directory, $server_name);
	
		if ($info['fileformat'] == 'jpg' || $info['fileformat'] == 'png') {
			$copy_path = $this->copy_directory . DIRECTORY_SEPARATOR . pathinfo($server_name, PATHINFO_FILENAME) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
			$source_path = $this->directory . DIRECTORY_SEPARATOR . $server_name;
			
			//Функция, создающаяя уменьшенную копию изображения
			$fileManager->resize($source_path, $copy_path, '', 600, 25);
		}

		$fileParameters['real_name'] = $file_name;
		$fileParameters['server_name'] = $server_name;
		$fileParameters['mime_type'] = $info['mime_type'];
		$fileParameters['filesize'] = $fileManager->getFileSize($info['filesize']);
		$fileParameters['extension'] = $info['fileformat'];
		
		$file = new \Project\Models\File($fileParameters);

		if(empty($_COOKIE)){
			$token = $fileManager->createToken();
			setcookie('token', $token, strtotime('+10 years'), null, null, null, true);
		} else {
			$token = $_COOKIE['token'];
		}
		
		$fileRecorder->addFile($file, $token);

		header('Location: /view/' . $server_name);
	}
}