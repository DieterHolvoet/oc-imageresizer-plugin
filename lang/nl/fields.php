<?php

return [
    'image_processor' => [
        'label' => 'Afbeeldingenverwerker',
        'description' => 'Het systeem dat gebruikt zal worden om je afbeeldingen te verwerken',
        'options' => [
            'local' => [
                'label' => 'Lokaal',
                'description' => 'Thumbnails worden lokaal gegenereerd en gecached',
            ],
            'imgix' => [
                'label' => 'Imgix',
                'description' => 'Thumbnails worden gegenereerd door de externe dienst Imgix',
            ],
        ],
    ],
    'imgix_domain' => [
        'label' => 'Domein',
        'description' => 'Het domein langswaar afbeeldingen aangeboden worden. Dit kan een Imgix subdomein zijn of een custom domein.',
    ],
    'imgix_use_https' => [
        'label' => 'Gebruik HTTPS',
        'description' => 'Bepaalt of HTTPS gebruikt moet worden bij het genereren van de url. Dit wordt standaard ondersteund bij het gebruiken van een Imgix subdomein.',
    ],
    'imgix_prefix' => [
        'label' => 'Pad prefix',
        'description' => 'Een pad om in te voegen voor het pad van de afbeelding.',
    ],
    'imgix_secure_url_token' => [
        'label' => 'Beveiligde url token',
        'description' => 'De token die gebruikt wordt om de hash te berekenen die aan elke url toegevoegd wordt. Voor meer informatie, neem een kijkje op https://docs.imgix.com/setup/securing-images (Engelstalig).',
    ],
];
