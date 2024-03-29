<?php 

namespace Project\Controllers;

class ListController implements \Project\Interfaces\CheckCookie
{
	
	private $filesDataGateway;
	private $fileManager;
	
	public function __construct(\PDO $pdo)
	{
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
		$this->fileManager = new \Project\Models\FileManager();
	}

	public function fileList(): array
	{
		$fileList = $this->filesDataGateway->getList();
		foreach ($fileList as $file) {

			$file->createdAt = $this->fileManager->showDate($file->createdAt);
			$file->author = $this->filesDataGateway->checkAuthor($this->checkCookie(), $file->serverName);
		}

		return $fileList;
	}

	public function checkCookie()
	{
		if (empty($_COOKIE['token'])) {
			$token = '';
		} else {
			$token = $_COOKIE['token'];
		}

		return $token;
	}
}