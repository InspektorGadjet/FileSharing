<?php

namespace Project\Models;

class FileManager
{	
	public function makeServerFileName(): string
	{
		$serverName = bin2hex(random_bytes(8));
		$serverName = sprintf('%s.%0.8s', $serverName, 'txt');
		return $serverName;
	}

	public function moveUploadedFile(string $directory, $file, string $serverName)
	{
	   $file->moveTo($directory . DIRECTORY_SEPARATOR . $serverName);
	}

	public function getInfoAboutFile(string $directory, string $serverName): array
	{
		$getid3 = new \getID3();
		$getid3->encoding = 'UTF-8';
		$getid3->Analyze($directory . DIRECTORY_SEPARATOR . $serverName);
		return $getid3->info;
	}

	public function getFileSize(int $fileSize): string
	{
		$units = array("B", "KB", "MB", "GB", "TB", "PB");
		$position = 0;
		while($fileSize >= 1024) {
			$fileSize /= 1024;
			$position ++;
		}

		return round($fileSize, 0) . " " . $units[$position];
	}

	public function getRealFileName(\Slim\Http\UploadedFile $file): string
	{
		$realName = $file->getClientFilename();
		$realName = pathinfo($realName);
		return $realName['basename'];
	}

	//Возвращает прошедшее время с создания файла
	public function showDate(string $date): string
	{
		if(!ctype_digit($date))
        $date = strtotime($date);

	    $diff = time() - $date;
	    if($diff == 0) {
	        return 'только что';
	    } elseif($diff > 0) {
	        $dayDiff = floor($diff / 86400);
	        if($dayDiff == 0)
	        {
	            if($diff < 60) {
	            	return 'только что';
	            }
	            if($diff < 120) {
	            	return '1 минуту назад';
	            }
	            if($diff < 3600) {
	            	return floor($diff / 60) . ' минут назад';
	            }
	            if($diff < 7200) {
	            	return '1 час назад';
	            }
	            if($diff < 86400) {
	            	return floor($diff / 3600) . ' часов назад';
	            }
	        }
	        if($dayDiff == 1) {
	        	return 'Вчера';
	        }
	        if($dayDiff < 7) {
	        	return $dayDiff . ' дней назад';
	        }
	        if($day_diff < 31) {
	        	return ceil($dayDiff / 7) . ' недель назад';
	        }
	        if($dayDiff < 60) {
	        	return 'в прошлом месяце';
	        }
	        return date('F Y', $date);
	    }
	}

	public function resize(
		string $sourcePath, 
	    string $destinationPath, 
	    $newwidth,
	    $newheight = FALSE, 
	    $quality = FALSE
	)
	{
		ini_set("gd.jpeg_ignore_warning", 1);
		list($oldwidth, $oldheight, $type) = getimagesize($sourcePath);

		switch ($type) {
        case IMAGETYPE_JPEG: $typestr = 'jpeg'; break;
        case IMAGETYPE_GIF: $typestr = 'gif' ;break;
        case IMAGETYPE_PNG: $typestr = 'png'; break;
    	}

    	$function = "imagecreatefrom$typestr";
    	$srcResource = $function($sourcePath);

    	if (!$newheight) { 
    		$newheight = round($newwidth * $oldheight/$oldwidth); 
    	}  elseif (!$newwidth) { 
    		$newwidth = round($newheight * $oldwidth/$oldheight); 
    	}
    	$destinationResource = imagecreatetruecolor($newwidth,$newheight);

    	imagecopyresampled($destinationResource, $srcResource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);

    	if ($type = 2) { # jpeg
        imageinterlace($destinationResource, 1);
        imagejpeg($destinationResource, $destinationPath, $quality);      
    	} else { # png
        $function = "image$typestr";
        $function($destinationResource, $destinationPath);
    	}

    	imagedestroy($destinationResource);
    	imagedestroy($srcResource); 
	}

	public function createToken()
	{
		$max = 0;
		do {
			$token = bin2hex(random_bytes(32));
			$max ++;
		} while($max < 5);

		return $token;
	}

	public function deleteFile($file, string $directory, string $copyDirectory)
	{
		unlink($directory . DIRECTORY_SEPARATOR . $file->serverName);
		unlink($copyDirectory . DIRECTORY_SEPARATOR . pathinfo($file->serverName, PATHINFO_FILENAME) . '.' . $file->extension);
		return;
	}
}