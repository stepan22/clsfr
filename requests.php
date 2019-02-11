<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$json = file_get_contents('php://input');

if ($json)
	$request = json_decode($json, true);
else
	$request = $_REQUEST;

if (isset($request['method'])) {
	switch ($request['method']) {
		case 'get':
			$response = [
				'code' => 200,
				'data' => getData(),
			];
			break;
		case 'save':
			if (isset($request['data']))
				$response = [
					'code' => 200,
					'data' => saveData($request['data']),
				];
			else
				$response = [
					'code' => 400,
					'error' => 'empty',
				];
			break;
		case 'update':
			if (isset($request['data']))
				$response = [
					'code' => 200,
					'data' => updateData($request['data']),
				];
			else
				$response = [
					'code' => 400,
					'error' => 'empty',
				];
			break;
		case 'classifier':
			if (isset($request['data']))
				saveData($data);

			if (classifier())
				$response = [
					'code' => 200,
					'data' => getData('output'),
				];
			else
				$response = [
					'code' => 500,
					'error' => 'error',
				];
			break;
		default:
			$response = [
				'code' => 400,
				'error' => 'empty method',
			];
			break;
	}
} else
	$response = [
		'code' => 400,
		'error' => 'empty method',
	];

echo json_encode($response, JSON_UNESCAPED_UNICODE);



// Берем данные из JSON
function getData ($name = 'input') {
	$filename = "interchange/{$name}.json";

	$file_data = json_decode(file_get_contents($filename));

	return $file_data;
}

// Сохраняем данные в JSON
function saveData ($data) {
	$filename = 'files/input.json';

	file_put_contents($filename, json_encode($data));

	return true;
}


// Обновляем данные в JSON
function updateData ($data) {
	$filename = 'files/input.json';
	$id = $data['id'];
	$topic = $data['topic'];
	$file = file_get_contents($filename);

	$classy_array = json_decode($file, true);
	unset($file);
	foreach ($classy_array  as $key => $value) {
		if (in_array($id, $value)) {
			$classy_array[$key] = ['topic' => $topic];
		}
	}

	file_put_contents($filename, json_encode($classy_array));
	unset($classy_array);

	return true;
}

// Производим классификацию
function classifier () {
	
	exec("Rscript resources/classify.R files/input.json files/output.json", $output); 

	return true;
}
