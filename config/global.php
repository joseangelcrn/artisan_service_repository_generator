<?php

$ENV_CONFIG_PREFIX = 'SRM'; //Service Repository Manager


return [
    'repositories'=>[
        'namespace'=>env($ENV_CONFIG_PREFIX.'_REPOSITORIES_NAMESPACE','App\Repositories'),
        'path'=> env($ENV_CONFIG_PREFIX.'_REPOSITORIES_PATH','Repositories'),
        'suffix'=> env($ENV_CONFIG_PREFIX.'_REPOSITORIES_SUFFIX','Repository')
    ],
    'services'=>[
        'namespace' =>env($ENV_CONFIG_PREFIX.'_SERVICES_NAMESPACE','App\Services'),
        'path' => env($ENV_CONFIG_PREFIX.'_SERVICES_PATH','Services'),
        'suffix' =>env($ENV_CONFIG_PREFIX.'_SERVICES_SUFFIX','Service'),
    ],

    'modules' => env($ENV_CONFIG_PREFIX.'_MODULES',false)
];

