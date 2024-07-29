<?php

use Illuminate\Support\Facades\Route;

return [
    'module' => [
        // Quản lý thành viên
        [
            'name' => 'user',
            'title' => 'QL THÀNH VIÊN',
            'icon' => 'fa fa-user',
            'subModule' => [
                [
                    'title' => 'QL nhóm thành viên',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => 'QL thành viên',
                    'route' => 'user/index'
                ]
            ]
        ],
        // Quản lý bài viết
        [
            'name' => 'post',
            'title' => 'QL BÀI VIẾT',
            'icon' => 'fa fa-file',
            'subModule' => [
                [
                    'title' => 'QL nhóm bài viết',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => 'QL bài viết',
                    'route' => 'post/index'
                ]
            ]
        ]
    ],
];

?>