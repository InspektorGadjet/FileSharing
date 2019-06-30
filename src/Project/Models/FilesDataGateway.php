<?php

namespace Project\Models;

class FilesDataGateway
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function addFile(\Project\Models\File $file)
	{
		$stmt = $this->pdo->prepare("INSERT INTO `files` (`real_name`, `server_name`, `format`, `size`, `extension`) VALUES (:real_name, :server_name, :format, :size, :extension)");
		$stmt->bindValue(':real_name', $file->getName());
		$stmt->bindValue(':server_name', $file->getServerName());
		$stmt->bindValue(':format', $file->getMimeType());
		$stmt->bindValue(':size', $file->getSize());
		$stmt->bindValue(':extension', $file->getExtension());
		$stmt->execute();
		return;
	}

	public function getFileByName(string $filename)
	{
		$stmt = $this->pdo->prepare("SELECT real_name, server_name, format, created_at, size, extension FROM `files` WHERE `server_name` = :server_name");
		$stmt->bindValue(':server_name', $filename);
		$stmt->execute();
		return $stmt->fetch();
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
		$stmt = $this->pdo->prepare("SELECT real_name, server_name, format, created_at, size FROM `files` ORDER BY `created_at` DESC");
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