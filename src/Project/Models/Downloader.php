<?php

namespace Project\Models;

class Downloader
{
	public function downloadFile(string $filename, string $directory, string $realFileName)
	{
		$filename = ($directory . DIRECTORY_SEPARATOR . $filename);

		if (file_exists($filename)) {
			header('X-SendFile: ' . realpath($filename));
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $realFileName);
			exit;
		}
	}
}