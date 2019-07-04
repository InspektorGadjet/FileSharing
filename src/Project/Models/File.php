<?php 

namespace Project\Models;

class File
{
	private $realName;
	private $serverName;
	private $mime_type;
	private $size;
	private $extension;

	public function __construct(array $fileParameters)
	{
		$this->realName = $fileParameters['realName'];
		$this->serverName = $fileParameters['serverName'];
		$this->mime_type = $fileParameters['mime_type'];
		$this->size = $fileParameters['filesize'];
		$this->extension = $fileParameters['extension'];
		
	}

	public function getName(): string
	{
		return $this->realName;
	}

	public function getServerName(): string
	{
		return $this->serverName;
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