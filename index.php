<?php


use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

try{
require __DIR__ . '/vendor/autoload.php';

$configuration = [
	'settings' => [
		'displayErrorDetails' => true,
		'addContentLengthHeader' => false,
		'db' => [
			'host' => 'localhost',
			'user' => 'root',
			'pass' => '',
			'dbname' => ''
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

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {

	$this->view->render($response, 'homepage.php');

})->setName('home');

$app->post('/upload', function (Request $request, Response $response, array $args) use ($app) {

	$directory = $this->get('upload_directory');

	$files = $request->getUploadedFiles();

    if (empty($files['newfile'])) {
        throw new \Project\Exceptions\NoFileException('Expected a newfile');
    }

 	
    $newfile = $files['newfile'];
    
    $o = new \Project\Controllers\MainController($newfile, $app, $directory);
    $o->main();

});




$app->run();
} catch (\Project\Exceptions\NoFileException $e) {
	echo $e->getMessage();
}