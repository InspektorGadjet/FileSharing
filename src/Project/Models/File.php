<?php 

namespace Project\Models;

class File
{
	private $real_name;
	private $server_name;
	private $mime_type;
	private $filenamepath;

	public function __construct(array $fileParameters)
	{
		$this->real_name = $fileParameters['real_name'];
		$this->server_name = $fileParameters['server_name'];
		$this->mime_type = $fileParameters['mime_type'];
		
	}

	public function getName()
	{
		return $this->real_name;
	}
	
	public function getMimeType()
	{
		return $this->mime_type;
	}
}