<?php

namespace Project\Models;

class FileManager
{	
	public function makeServerFileName(): string
	{
		$server_name = bin2hex(random_bytes(8));
		$server_name = sprintf('%s.%0.8s', $server_name, 'txt');
		return $server_name;
	}

	public function moveUploadedFile(string $directory, $file, string $server_name)
	{
	   $file->moveTo($directory . DIRECTORY_SEPARATOR . $server_name);
	}

	public function getInfoAboutFile(string $directory, string $server_name): array
	{
		$getid3 = new \getID3();
		$getid3->encoding = 'UTF-8';
		$getid3->Analyze($directory . DIRECTORY_SEPARATOR . $server_name);
		return $getid3->info;
	}

	public function getFileSize(int $file_size): string
	{
		$units = array("B", "KB", "MB", "GB", "TB", "PB");
		$position = 0;
		while($file_size >= 1024) {
			$file_size /= 1024;
			$position ++;
		}

		return round($file_size, 0) . " " . $units[$position];
	}

	public function getRealFileName(\Slim\Http\UploadedFile $file): string
	{
		$real_name = $file->getClientFilename();
		$real_name = pathinfo($real_name);
		return $real_name['basename'];
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
	        $day_diff = floor($diff / 86400);
	        if($day_diff == 0)
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
	        if($day_diff == 1) {
	        	return 'Вчера';
	        }
	        if($day_diff < 7) {
	        	return $day_diff . ' дней назад';
	        }
	        if($day_diff < 31) {
	        	return ceil($day_diff / 7) . ' недель назад';
	        }
	        if($day_diff < 60) {
	        	return 'в прошлом месяце';
	        }
	        return date('F Y', $date);
	    }
	}

	public function resize(
		string $source_path, 
	    string $destination_path, 
	    $newwidth,
	    $newheight = FALSE, 
	    $quality = FALSE
	)
	{
		ini_set("gd.jpeg_ignore_warning", 1);
		list($oldwidth, $oldheight, $type) = getimagesize($source_path);

		switch ($type) {
        case IMAGETYPE_JPEG: $typestr = 'jpeg'; break;
        case IMAGETYPE_GIF: $typestr = 'gif' ;break;
        case IMAGETYPE_PNG: $typestr = 'png'; break;
    	}

    	$function = "imagecreatefrom$typestr";
    	$src_resource = $function($source_path);

    	if (!$newheight) { 
    		$newheight = round($newwidth * $oldheight/$oldwidth); 
    	}  elseif (!$newwidth) { 
    		$newwidth = round($newheight * $oldwidth/$oldheight); 
    	}
    	$destination_resource = imagecreatetruecolor($newwidth,$newheight);

    	imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $newwidth, $newheight, $oldwidth, $oldheight);

    	if ($type = 2) { # jpeg
        imageinterlace($destination_resource, 1);
        imagejpeg($destination_resource, $destination_path, $quality);      
    	} else { # png
        $function = "image$typestr";
        $function($destination_resource, $destination_path);
    	}

    	imagedestroy($destination_resource);
    	imagedestroy($src_resource); 
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
}