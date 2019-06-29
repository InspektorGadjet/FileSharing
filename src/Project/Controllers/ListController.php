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
		foreach ($fileList as $file) {
			if (mb_strlen($file->real_name) > 15) {
				#$file->real_name = mb_substr($file->real_name, 4);
				echo $file->real_name;
				echo '<br>';
			}
		}
		
		return $fileList;
	}
}