<?php 

namespace Project\Controllers;

class ListController
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
			$file->created_at = $this->fileManager->showDate($file->created_at);

		}
		
		return $fileList;
	}
}