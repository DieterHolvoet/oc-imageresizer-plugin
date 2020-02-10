<?php

return [
    'image_processor' => [
        'label' => 'Image processor',
        'description' => 'The system that will be used to process your images',
        'options' => [
            'local' => [
                'label' => 'Local',
                'description' => 'Thumbnails are generated and cached locally',
            ],
            'imgix' => [
                'label' => 'Imgix',
                'description' => 'Thumbnails are generated through the external Imgix service',
            ],
        ],
    ],
    'imgix_domain' => [
        'label' => 'Domain',
        'description' => 'The domain through which the images are served. This can be an Imgix subdomain or a domain of your own.',
    ],
    'imgix_use_https' => [
        'label' => 'Use HTTPS',
        'description' => 'Whether to generate urls with HTTPS. This is supported by default when using an Imgix subdomain.',
    ],
    'imgix_prefix' => [
        'label' => 'Path prefix',
        'description' => 'A path to prepend to the image path',
    ],
    'imgix_secure_url_token' => [
        'label' => 'Secure url token',
        'description' => 'The token used to calculate the hash which is appended to every generated url. For more information, visit https://docs.imgix.com/setup/securing-images.',
    ],
];
