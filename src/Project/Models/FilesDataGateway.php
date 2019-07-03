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
		$stmt = $this->pdo->prepare("INSERT INTO `files` (`real_name`, `server_name`, `format`, `size`, `extension`, `token`, `author_comment`) VALUES (:real_name, :server_name, :format, :size, :extension, :token, :author_comment)");
		$stmt->bindValue(':real_name', $file->getName());
		$stmt->bindValue(':server_name', $file->getServerName());
		$stmt->bindValue(':format', $file->getMimeType());
		$stmt->bindValue(':size', $file->getSize());
		$stmt->bindValue(':extension', $file->getExtension());
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':author_comment', '');
		$stmt->execute();
		return;
	}

	public function deleteFile(string $server_name)
	{
		$stmt = $this->pdo->prepare("DELETE FROM `files` WHERE server_name = :server_name");
		$stmt->bindValue(':server_name', $server_name);
		$stmt->execute();
		return;
	}

	public function checkAuthor(string $token, string $server_name)
	{
		$stmt = $this->pdo->prepare("SELECT `token` FROM `files` WHERE token = :token AND server_name = :server_name");
		$stmt->bindValue(':token', $token);
		$stmt->bindValue(':server_name', $server_name);
		$stmt->execute();
		return (bool)$stmt->fetchcolumn();
	}

	public function getFileByName(string $filename)
	{
		$stmt = $this->pdo->prepare("SELECT real_name, server_name, format, created_at, size, extension FROM `files` WHERE `server_name` = :server_name");
		$stmt->bindValue(':server_name', $filename);
		$stmt->execute();
		return $stmt->fetch();
	}

	public function getFileComment(string $filename)
	{
		$stmt = $this->pdo->prepare("SELECT author_comment FROM `files` WHERE `server_name` = :server_name");
		$stmt->bindValue(':server_name', $filename);
		$stmt->execute();
		return $stmt->fetchcolumn();
	}

	public function updateComment(string $server_name, string $author_comment)
	{
		$stmt = $this->pdo->prepare("UPDATE `files` SET author_comment = :author_comment WHERE server_name = :server_name");
		$stmt->bindValue(':author_comment', $author_comment);
		$stmt->bindValue(':server_name', $server_name);
		$stmt->execute();
		return;
	}

	public function getFileName(string $server_name): string
	{
		$stmt = $this->pdo->prepare("SELECT `real_name` FROM `files` WHERE `server_name` = :server_name");
		$stmt->bindValue(':server_name', $server_name);
		$stmt->execute();
		return $stmt->fetchColumn();
	}

	public function getList(): array
	{
		$stmt = $this->pdo->prepare("SELECT real_name, server_name, format, created_at, size FROM `files` ORDER BY `created_at` DESC LIMIT 100");
		#$stmt->bindValue(':max_files', $max_files);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getDate(string $server_name): string
	{
		$stmt = $this->pdo->prepare("SELECT created_at FROM `files` WHERE `server_name` = :server_name");
		$stmt->bindValue(':server_name', $server_name);
		$stmt->execute();
		return $stmt->fetchColumn();
	}
}