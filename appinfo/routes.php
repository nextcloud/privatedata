<?php

return [
	'ocs' => [
		[
			'root' => '/privatedata',
			'name' => 'privateData#get',
			'url' => '/getattribute/{app}/{key}',
			'verb' => 'GET',
			'defaults' => [
				'app' => null,
				'key' => null
			]
		],
		[
			'root' => '/privatedata',
			'name' => 'privateData#set',
			'url' => '/setattribute/{app}/{key}',
			'verb' => 'POST',
		],
		[
			'root' => '/privatedata',
			'name' => 'privateData#delete',
			'url' => '/deleteattribute/{app}/{key}',
			'verb' => 'POST',
		],
	],
];
