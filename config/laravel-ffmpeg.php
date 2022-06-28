<?php

return [
    'ffmpeg' => [
        'binaries' => env('FFMPEG_PATH', 'ffmpeg'),
        'threads'  => 12,
    ],

    'ffprobe' => [
        'binaries' => env('FFPROBE_PATH', 'ffprobe'),
    ],

    'timeout' => 3600,

    'enable_logging' => true,

    'set_command_and_error_output_on_exception' => false,
];
