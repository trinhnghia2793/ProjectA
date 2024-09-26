<?php
return [
    'module' => [
        // Quản lý bài viết
        [
            'name' => ['post'],
            'title' => '文章',
            'icon' => 'fa fa-file',
            'subModule' => [
                [
                    'title' => '文章組',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => '文章',
                    'route' => 'post/index'
                ]
            ]
        ],
                
        // Quản lý thành viên
        [
            'name' => ['user'],
            'title' => '用户组',
            'icon' => 'fa fa-user',
            'subModule' => [
                [
                    'title' => '用户组',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => '用户',
                    'route' => 'user/index'
                ],
                [
                    'title' => '允许',
                    'route' => 'permission/index'
                ]
            ]
        ],

        // Ngôn ngữ
        [
            'name' => ['language'],
            'title' => '一般的',
            'icon' => 'fa fa-file',
            'subModule' => [
                [
                    'title' => '语言',
                    'route' => 'language/index'
                ]
            ]
        ]
    ],
];