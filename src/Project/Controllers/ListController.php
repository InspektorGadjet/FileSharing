<?php 

namespace Project\Controllers;

class ListController
{
	private $filesDataGateway;

	public function __construct(\PDO $pdo)
	{
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
	}

	public function fileList()
	{
		$fileList = $this->filesDataGateway->getFiles();
		return $fileList;
	}
}