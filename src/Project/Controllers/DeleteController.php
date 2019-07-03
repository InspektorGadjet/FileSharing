<?php

namespace Project\Controllers;

class DeleteController
{
	private $fileManager;
	private $filesDataGateway;

	public function __construct(\PDO $pdo)
	{
		$this->fileManager = new \Project\Models\FileManager();
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
	}

	public function delete(string $filename, string $directory, string $copy_directory)
	{
		$file = $this->filesDataGateway->getFileByName($filename);
		$this->filesDataGateway->deleteFile($file->server_name);
		$this->fileManager->deleteFile($file, $directory, $copy_directory);
		
		return;
	}
}