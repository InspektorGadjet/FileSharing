<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/vendor/autoload.php';

$configuration = [
	'settings' => [
		'displayErrorDetails' => true,
		'addContentLengthHeader' => false,
		'db' => [
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'dbname' => 'files'
		],
	],
];
$c = new \Slim\Container($configuration);

$app = new \Slim\App($c);

$container = $app->getContainer();

$container['view'] = function ($container) {
	$view = new \Slim\Views\Twig('./templates', [
		'cache' => false
	]);

	return $view;
};
$container['upload_directory'] = __DIR__ . '\uploads';
$container['db'] = function ($c) {
	$db = $c['settings']['db'];
	$pdo = new \PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass'], array(
		PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES UTF8",
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE=>TRUE
	));
	return $pdo;
};

$app->get('/', function (Request $request, Response $response, array $args) {
	$this->view->render($response, 'homepage.php');
})->setName('home');

$app->post('/upload', function (Request $request, Response $response, array $args) {
	$directory = $this->get('upload_directory');
	$pdo = $this->get('db');
	$files = $request->getUploadedFiles();

    if (empty($files['newfile']->file)) {
        header("Location: /");
        exit();
    }
    
    $newfile = $files['newfile'];
    
    #var_dump($newfile->file);
    $controller = new \Project\Controllers\MainController($newfile, $directory);
    $file = $controller->main($pdo);
    $this->view->render($response, 'upload_page.php', [
    	'file_name' => $file->getName(),
    	'server_name'=> $file->getServerName(),
    	'mime_type' => $file->getMimeType()
	]);
});

$app->get('/download/{filename}', function (Request $request, Response $response, array $args) {
	$directory = $this->get('upload_directory');
	$pdo = $this->get('db');
	$controller = new Project\Controllers\DownloadController($args['filename'], $directory, $pdo);
	$controller->main();
});

$app->get('/files', function (Request $request, Response $response, array $args) {
	$pdo = $this->get('db');
	$controller = new Project\Controllers\ListController($pdo);
	$fileList = $controller->fileList();
	var_dump($fileList);
	$this->view->render($response, 'file_list.php', ['fileList' => $fileList]);
});

$app->run();
