<?php 

namespace Project\Models;

class File
{
	private $real_name;
	private $server_name;
	private $mime_type;
	private $size;
	private $extension;

	public function __construct(array $fileParameters)
	{
		$this->real_name = $fileParameters['real_name'];
		$this->server_name = $fileParameters['server_name'];
		$this->mime_type = $fileParameters['mime_type'];
		$this->size = $fileParameters['filesize'];
		$this->extension = $fileParameters['extension'];
		
	}

	public function getName(): string
	{
		return $this->real_name;
	}

	public function getServerName(): string
	{
		return $this->server_name;
	}
	
	public function getMimeType(): string
	{
		return $this->mime_type;
	}

	public function getSize(): string
	{
		return $this->size;
	}

	public function getExtension(): string
	{
		return $this->extension;
	}
}