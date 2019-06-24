<?php

namespace Project\Models;

class FilesDataGateway
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}

	public function addFile($file)
	{
		$stmt = $this->pdo->prepare("INSERT INTO `files` (`real_name`, `server_name`, `format`) VALUES (:real_name, :server_name, :format)");
		$stmt->bindValue(':real_name', $file->getName());
		$stmt->bindValue(':server_name', $file->getServerName());
		$stmt->bindValue(':format', $file->getMimeType());
		$stmt->execute();
		return;
	}

	public function getFiles()
	{
		$stmt = $this->pdo->prepare("SELECT real_name, server_name, format FROM `files`");
		$stmt->execute();
		return $stmt->fetchAll();
	}
}