<?php 

namespace Project\Controllers;

class ViewController implements \Project\Interfaces\CheckCookie
{
	private $fileManager;
	private $filesDataGateway;
	private $commentsDataGateway;

	public function __construct(\PDO $pdo)
	{
		$this->fileManager = new \Project\Models\FileManager();
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
		$this->commentsDataGateway = new \Project\Models\CommentsDataGateway($pdo);
	}

	public function view(string $directory, string $filename, string $authorComment): array
	{
		$file = $this->filesDataGateway->getFileByName($filename);

		$date = $this->fileManager->showDate($file->createdAt);

		if($authorComment != $this->filesDataGateway->getFileComment($filename) && !empty($authorComment)) {
			$this->filesDataGateway->updateComment($file->serverName, $authorComment);
		}

		if(!empty($_POST['comment'])) {
			$comment = $_POST['comment'];
			$this->commentsDataGateway->addComment($comment, $file->id);
			header('Location: /view/' . $file->serverName);
			die();
		}

		$commentsList = $this->commentsDataGateway->getCommentsList($file->id);
		
		$info['id'] = $file->id;
		$info['name'] = $file->realName;
		$info['serverName'] = $file->serverName;
		$info['filesize'] = $file->size;
		$info['date'] = $date;
		$info['extension'] = $file->extension;
		$info['copy'] = pathinfo($filename, PATHINFO_FILENAME). '.' . $file->extension;
		$info['author'] = $this->filesDataGateway->checkAuthor($this->checkCookie(), $file->serverName);
		$info['authorComment'] = $this->filesDataGateway->getFileComment($file->serverName);
		$info['commentsList'] = $commentsList;
		
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