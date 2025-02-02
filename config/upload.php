<?php

return [
    'image' => [
        'disk' => 'public',
        'max_size' => 5120, // 5MB
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/jpg',
        ],
        'base_path' => 'uploads/images',
        'pipelines' => [
            'default' => [
                'generate_filename' => [
                    'enabled' => true
                ],
                'resize' => [
                    'enabled' => true,
                    'width' => 800,
                    'height' => 600
                ],
                'optimize' => [
                    'enabled' => true,
                    'quality' => 85
                ],
                'storage' => [
                    'enabled' => true
                ]
            ],
            'avatar' => [
                'generate_filename' => [
                    'enabled' => true
                ],
                'resize' => [
                    'enabled' => true,
                    'width' => 300,
                    'height' => 300
                ],
                'optimize' => [
                    'enabled' => true,
                    'quality' => 90
                ],
                'storage' => [
                    'enabled' => true
                ]
            ],
        ]
    ]
];