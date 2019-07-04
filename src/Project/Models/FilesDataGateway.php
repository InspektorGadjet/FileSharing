<?php

namespace Project\Models;

class FilesDataGateway
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function addFile(\Project\Models\File $file, string $token)
	{
		$stmt = $this->pdo->prepare("INSERT INTO `files` (`realName`, `serverName`, `format`, `size`, `extension`, `token`, `authorComment`) VALUES (:realName, :serverName, :format, :size, :extension, :token, :authorComment)");
		$stmt->bindValue(':realName', $file->getName());
		$stmt->bindValue(':serverName', $file->getServerName());
		$stmt->bindValue(':format', $file->getMimeType());
		$stmt->bindValue(':size', $file->getSize());
		$stmt->bindValue(':extension', $file->getExtension());
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':authorComment', '');
		$stmt->execute();
		return;
	}

	public function deleteFile(string $serverName)
	{
		$stmt = $this->pdo->prepare("DELETE FROM `files` WHERE serverName = :serverName");
		$stmt->bindValue(':serverName', $serverName);
		$stmt->execute();
		return;
	}

	public function checkAuthor(string $token, string $serverName)
	{
		$stmt = $this->pdo->prepare("SELECT `token` FROM `files` WHERE token = :token AND serverName = :serverName");
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':serverName', $serverName);
		$stmt->execute();
		return (bool)$stmt->fetchcolumn();
	}

	public function getFileByName(string $filename)
	{
		$stmt = $this->pdo->prepare("SELECT realName, serverName, format, createdAt, size, extension FROM `files` WHERE `serverName` = :serverName");
		$stmt->bindValue(':serverName', $filename);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function getFileComment(string $filename)
	{
		$stmt = $this->pdo->prepare("SELECT authorComment FROM `files` WHERE `serverName` = :serverName");
		$stmt->bindValue(':serverName', $filename);
		$stmt->execute();
		return $stmt->fetchcolumn();
	}

	public function updateComment(string $serverName, string $authorComment)
	{
		$stmt = $this->pdo->prepare("UPDATE `files` SET authorComment = :authorComment WHERE serverName = :serverName");
		$stmt->bindValue(':authorComment', $authorComment);
		$stmt->bindValue(':serverName', $serverName);
		$stmt->execute();
		return;
	}

	public function getFileName(string $serverName): string
	{
		$stmt = $this->pdo->prepare("SELECT `realName` FROM `files` WHERE `serverName` = :serverName");
		$stmt->bindValue(':serverName', $serverName);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function getList(): array
	{
		$stmt = $this->pdo->prepare("SELECT realName, serverName, format, createdAt, size FROM `files` ORDER BY `createdAt` DESC LIMIT 100");
		#$stmt->bindValue(':max_files', $max_files);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getDate(string $serverName): string
	{
		$stmt = $this->pdo->prepare("SELECT createdAt FROM `files` WHERE `serverName` = :serverName");
		$stmt->bindValue(':serverName', $serverName);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
}