<?php 

namespace Project\Controllers;

class ViewController
{
	private $fileManager;

	public function __construct(\PDO $pdo)
	{
		$this->fileManager = new \Project\Models\FileManager();
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
	}

	public function view($directory, $filename): array
	{
		$date = $this->filesDataGateway->getDate($filename);
		$date = $this->fileManager->showDate($date);

		$info['name'] = $this->filesDataGateway->getFileName($filename);
		$info = $info + $this->fileManager->getInfoAboutFile($directory, $filename);
		$info['filesize'] = $this->fileManager->getFileSize($info['filesize']);
		$info['date'] = $date; 

		#var_dump($info);
		return $info;
	}
}