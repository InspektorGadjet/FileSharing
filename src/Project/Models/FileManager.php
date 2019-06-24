<?php

namespace Project\Models;

class FileManager
{	


	public function addFile()
	{
		
	}

	public function makeServerFileName()
	{
		$server_name = bin2hex(random_bytes(8));
		$server_name = sprintf('%s.%0.8s', $server_name, 'txt');
		return $server_name;
	}

	public function moveUploadedFile(string $directory, $file, $server_name)
	{
	    $file->moveTo($directory . DIRECTORY_SEPARATOR . $server_name);
    	#var_dump($getid3->info);
	}

	public function getInfoAboutFile(string $directory, $server_name)
	{
		$getid3 = new \getID3();
		$getid3->encoding = 'UTF-8';
		$getid3->Analyze($directory . DIRECTORY_SEPARATOR . $server_name);
		return $getid3->info;
	}

	public function getRealFileName(\Slim\Http\UploadedFile $file)
	{
		$real_name = $file->getClientFilename();
		$real_name = pathinfo($real_name);
		return $real_name['basename'];
	}
}