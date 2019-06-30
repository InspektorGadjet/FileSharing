<?php 

namespace Project\Controllers;

class DownloadController
{
	private $filename;
	private $directory;
	private $filesDataGateway;

	public function __construct(string $filename, string $directory, \PDO $pdo)
	{
		$this->filename = $filename;
		$this->directory = $directory;
		$this->filesDataGateway = new \Project\Models\FilesDataGateway($pdo);
	}

	public function download(string $filename): void
	{
		// filename - имя файла на сервере, realname - настоящее имя файла
		
		$realFileName = $this->filesDataGateway->getFileName($this->filename);
		$downloader = new \Project\Models\Downloader();
		$downloader->downloadFile($this->filename, $this->directory, $realFileName);

	}
}