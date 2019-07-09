<?php

namespace Project\Models;

class CommentsDataGateway
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function addComment(string $text, int $fileId)
	{
		$stmt = $this->pdo->prepare("INSERT INTO `comments` (`fileId`, `text`) VALUES (:fileId, :text)");
		$stmt->bindValue(':fileId', $fileId);
		$stmt->bindValue(':text', $text);
		$stmt->execute();
		return;
	}

	public function getCommentsList(int $fileId)
	{
		$stmt = $this->pdo->prepare("SELECT author, text FROM `comments` WHERE `fileId` = :fileId");
		$stmt->bindValue(':fileId', $fileId);
		$stmt->execute();
		return $stmt->fetchAll();
	}
}