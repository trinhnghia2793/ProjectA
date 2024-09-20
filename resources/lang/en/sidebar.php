<?php
return [
    'module' => [
        // Quản lý bài viết
        [
            'name' => ['post'],
            'title' => 'ARTICLE',
            'icon' => 'fa fa-file',
            'subModule' => [
                [
                    'title' => 'Article Group',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => 'Article',
                    'route' => 'post/index'
                ]
            ]
        ],
                
        // Quản lý thành viên
        [
            'name' => ['user'],
            'title' => 'USER GROUP',
            'icon' => 'fa fa-user',
            'subModule' => [
                [
                    'title' => 'User Group',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => 'User',
                    'route' => 'user/index'
                ]
            ]
        ],

        // Ngôn ngữ
        [
            'name' => ['language'],
            'title' => 'GENERAL',
            'icon' => 'fa fa-file',
            'subModule' => [
                [
                    'title' => 'Language',
                    'route' => 'language/index'
                ]
            ]
        ]
    ],
];