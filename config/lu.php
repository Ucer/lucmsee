<?php

return [
    'current_version' => '1.1.5.01',
    'api_app_url' => env('API_APP_URL'),
    'system_config_group_list' => [
        'basic' => ['title' => '基本配置', 'icon' => 'ios-medal', 'desc' => ''],
        'content' => ['title' => '内容配置', 'icon' => 'md-menu', 'desc' => ''],
        'user' => ['title' => '用户配置', 'icon' => 'ios-walk', 'desc' => ''],
        'system' => ['title' => '系统配置', 'icon' => 'ios-trophy-outline', 'desc' => ''],
    ],
    'userAgreementTable_agree_type' => [
        'terms_of_use' => '使用条款',
        'privacy_clause' => '隐私条款',
    ],
    'article_aes_encrypt_key' => [ // 文章密码加密密钥
        'first_key' => env('ARTICLE_AES_FIRST_ENCRYPT_KEY'),
        'second_key' => env('ARTICLE_AES_SECOND_ENCRYPT_KEY'),
    ],
    // 项目上线后修改
    'accept_other_host' => [
        'common' => [ // 访问 AcceptCommonAccessController
            'hosts' => [
                'http://lucmsee_api.test',
            ],
            'access_token' => 'xccvxcvxKMSDFYIIadfN*&^%$#@~~!',
        ],
        'lucmsee_api' => [ // 访问  AcceptLucmseeApiAccessController
            'hosts' => [
                'http://lucmsee_api.test',
            ],
            'access_token' => '',
        ]
    ],
    'access_other_host' => [
        'filesystem' => [
            'host' => 'http://filesystem.test',
            'access_token' => 'kjsdfiyYnsdfsadfYkT@#$',
        ]
    ]
];
