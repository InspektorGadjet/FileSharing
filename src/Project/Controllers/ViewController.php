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

	public function view(string $directory, string $filename, string $comment): array
	{
		$file = $this->filesDataGateway->getFileByName($filename);
		$date = $this->fileManager->showDate($file->created_at);
		
		$info['name'] = $file->real_name;
		$info['server_name'] = $file->server_name;
		$info['filesize'] = $file->size;
		$info['date'] = $date;
		$info['extension'] = $file->extension;
		$info['copy'] = pathinfo($filename, PATHINFO_FILENAME). '.' . $file->extension;

		return $info;
	}
}