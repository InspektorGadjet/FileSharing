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

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {

	$this->view->render($response, 'homepage.php');

})->setName('home');

$app->post('/upload', function (Request $request, Response $response, array $args) {
	$files = $request->getUploadedFiles();
    if (empty($files['newfile'])) {
        throw new Exception('Expected a newfile');
    }
 
    $newfile = $files['newfile'];

    var_dump($newfile);
});

$app->run();