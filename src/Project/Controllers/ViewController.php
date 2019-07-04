<?php 

namespace Project\Controllers;

class ViewController implements \Project\Interfaces\CheckCookie
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

		$date = $this->fileManager->showDate($file->createdAt);

		

		if($comment != $this->filesDataGateway->getFileComment($filename) && !empty($comment)) {
			$this->filesDataGateway->updateComment($file->serverName, $comment);
		}

		$info['name'] = $file->realName;
		$info['serverName'] = $file->serverName;
		$info['filesize'] = $file->size;
		$info['date'] = $date;
		$info['extension'] = $file->extension;
		$info['copy'] = pathinfo($filename, PATHINFO_FILENAME). '.' . $file->extension;
		$info['author'] = $this->filesDataGateway->checkAuthor($this->checkCookie(), $file->serverName);
		$info['comment'] = $this->filesDataGateway->getFileComment($file->serverName);
		
		return $info;
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