<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'course_videos_raw' => [
            'driver' => 'local',
            'root' => '/var/www/login/storage/videos/raw',
        ],
        'course_videos_raw_local' => [
            'driver' => 'local',
            'root' => storage_path('videos/raw'),
        ],
        'course_videos_playlist' => [
            'driver' => 'local',
            'root' => storage_path('videos/playlist'),
        ],
        'course_videos_thumbnails' => [
            'driver' => 'local',
            'root' => '/var/www/login/public/thumbnails/',
        ],
        'student_profile_pictures' => [
            'driver' => 'local',
            'root' => storage_path('profile_pic/student'),
        ],
        'facilitator_profile_pictures' => [
            'driver' => 'local',
            'root' => storage_path('profile_pic/facilitator'),
        ],
        'institution_profile_pictures' => [
            'driver' => 'local',
            'root' => storage_path('profile_pic/institution'),
        ],
        'institution_admin_profile_pictures' => [
            'driver' => 'local',
            'root' => storage_path('profile_pic/institution_admin'),
        ],
        'authority_profile_pictures' => [
            'driver' => 'local',
            'root' => storage_path('profile_pic/authority'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/institution_admin',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],
        's3_authority_profile_pictures' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/authority',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_institution_admin_profile_pictures' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/institution_admin',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_institution_profile_pictures' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/institution',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_facilitator_profile_pictures' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/facilitator',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_student_profile_pictures' => [
            'driver' => 's3',
            'root'=>'app/profile_pic/student',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_course_videos' => [
            'driver' => 's3',
            'root'=>'app/videos',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_course_thumbnails' => [
            'driver' => 's3',
            'root'=>'app/thumbnails',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],
        's3_website_assets' => [
            'driver' => 's3',
            'root'=>'app/website/assets',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'visibility'=> 'public'
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('/'),
    ],

];
