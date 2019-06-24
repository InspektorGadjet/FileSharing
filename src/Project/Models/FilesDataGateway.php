<?php

namespace Project\Models;

class FilesDataGateway
{
	private $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
	}
}