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

		$date = $this->fileManager->showDate($file->created_at);

		

		if($comment != $this->filesDataGateway->getFileComment($filename) && !empty($comment)) {
			$this->filesDataGateway->updateComment($file->server_name, $comment);
		}

		$info['name'] = $file->real_name;
		$info['server_name'] = $file->server_name;
		$info['filesize'] = $file->size;
		$info['date'] = $date;
		$info['extension'] = $file->extension;
		$info['copy'] = pathinfo($filename, PATHINFO_FILENAME). '.' . $file->extension;
		$info['author'] = $this->filesDataGateway->checkAuthor($this->checkCookie(), $file->server_name);
		$info['comment'] = $this->filesDataGateway->getFileComment($file->server_name);
		
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