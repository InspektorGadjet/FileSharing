<?php 

namespace Project\Controllers;

class MainController
{
	private $file;
	private $directory;
	private $imageExtensions = array(
		'jpg',
		'png'
	);

	public function __construct(\Slim\Http\UploadedFile $newfile, string $directory, string $copyDirectory)
	{
		$this->file = $newfile;
		$this->directory = $directory;
		$this->copyDirectory = $copyDirectory;
	}

	public function main(\PDO $pdo): void
	{
		$fileRecorder = new \Project\Models\FilesDataGateway($pdo);
		$fileManager = new \Project\Models\FileManager();
		$serverName = $fileManager->makeServerFileName();

		//Загрузка файла на сервер
		$fileManager->moveUploadedFile($this->directory, $this->file, $serverName);

		//Использование библиотеки getID3 для получения данных о файле
		$fileName = $fileManager->getRealFileName($this->file);

		$info = $fileManager->getInfoAboutFile($this->directory, $serverName);
	
		if (in_array($info['fileformat'], $this->imageExtensions)) {
			$copyPath = $this->copyDirectory . DIRECTORY_SEPARATOR . pathinfo($serverName, PATHINFO_FILENAME) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
			$sourcePath = $this->directory . DIRECTORY_SEPARATOR . $serverName;
			
			//Функция, создающаяя уменьшенную копию изображения
			$fileManager->resize($sourcePath, $copyPath, '', 600, 25);
		}

		$fileParameters['realName'] = $fileName;
		$fileParameters['serverName'] = $serverName;
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

		header('Location: /view/' . $serverName);
	}
}