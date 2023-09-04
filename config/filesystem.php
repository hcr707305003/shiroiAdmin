<?php
return [
    // 默认磁盘
    'default'          => env('filesystem.driver', 'local'),
    // 磁盘列表
    'disks'            => [
        'local'        => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'uploads',
        ],
        'public'       => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'public/uploads',
            // 磁盘路径对应的外部URL路径
            'url'        => '/uploads',
            // 可见性
            'visibility' => 'public',
            'validate'   => [
                'image' => [
                    'fileSize:10485760',// 10MB
                    'fileExt:png,jpg,jpeg,gif,bmp,ico,svg',
                    'fileMime:image/png,image/jpeg,image/gif,image/bmp,image/ico,image/svg',
                ],
                'video' => [
                    'fileSize:209715200',// 200MB
                    'fileExt:mp4,mov,mpg,mpeg,rmvb,avi,rm,mkv,flv,wmv',
                ],
                'file'  => [
                    'fileSize:419430400',// 50MB
                    'fileExt:png,jpg,jpeg,gif,bmp,ico,svg,mp4,mov,mpg,mpeg,rmvb,avi,rm,mkv,flv,wmv,mp4,wav,mid,flac,ape,m4a,ogg,mid,txt,doc,docx,xls,xlsx,ppt,pptx,pdf,md,xml,rar,zip,tar,gz,7z,bz2,cab,iso'
                ]
            ],
        ],

        // 后台导入配置
        'admin_import' => [
            // 磁盘类型
            'type'       => 'local',
            // 磁盘路径
            'root'       => app()->getRootPath() . 'import',
            // 磁盘路径对应的外部URL路径
            'url'        => '',
            // 可见性
            'visibility' => 'private',
            'validate'   => [
                'file' => ['fileSize:2048000', 'fileExt:xlsx']
            ],
        ],
        // 更多的磁盘配置信息
    ],
    // 表单内是否真实删除文件
    'form_true_delete' => false,
];
