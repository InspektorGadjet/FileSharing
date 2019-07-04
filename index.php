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
$container['uploadDirectory'] = __DIR__ . '\uploads';
$container['copyDirectory'] = __DIR__ . '\copyes';
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
	$copyDirectory = $this->get('copyDirectory');
	$directory = $this->get('uploadDirectory');
	$pdo = $this->get('db');
	$files = $request->getUploadedFiles();

    if (empty($files['newfile']->file)) {
        header("Location: /");
        exit;
    }
    
    //Файл для загрузки на сервер
    $newfile = $files['newfile'];
    $controller = new \Project\Controllers\MainController($newfile, $directory, $copyDirectory);
    $controller->main($pdo);
    exit;
    /*$this->view->render($response, 'upload_page.php', [
    	'file_name' => $file->getName(),
    	'server_name'=> $file->getServerName(),
    	'mime_type' => $file->getMimeType()
	]);*/
});

$app->get('/download/{filename}', function (Request $request, Response $response, array $args) {
	$directory = $this->get('uploadDirectory');
	$pdo = $this->get('db');
	$controller = new Project\Controllers\DownloadController($args['filename'], $directory, $pdo);
	$controller->download($args['filename']);
});

$app->get('/files', function (Request $request, Response $response, array $args) {
	$pdo = $this->get('db');
	$controller = new Project\Controllers\ListController($pdo);
	$fileList = $controller->fileList();
	$this->view->render($response, 'file_list.php', ['fileList' => $fileList]);
});

$app->map(['GET', 'POST'], '/view/{filename}', function (Request $request, Response $response, array $args) {
	$pdo = $this->get('db');
	$directory = $this->get('uploadDirectory');
	$comment = $request->getParsedBody()['text'];
	if (empty($comment)) {
		$comment = '';
	}
	$controller = new \Project\Controllers\ViewController($pdo);
	$info = $controller->view($directory, $args['filename'], $comment);

	$this->view->render($response, 'file_info.php', ['info' => $info, 'comment' => $comment]);

});

$app->get('/delete/{filename}', function (Request $request, Response $response, array $args) {
	$pdo = $this->get('db');
	$copyDirectory = $this->get('copyDirectory');
	$directory = $this->get('uploadDirectory');
	$controller = new Project\Controllers\DeleteController($pdo);
	$controller->delete($args['filename'], $directory, $copyDirectory);
	header("Location: /files");
	exit();
});

$app->run();
