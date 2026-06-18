<?php

return [
    'investors' => [
        'chunk_size' => (int) env('INVESTOR_IMPORT_CHUNK_SIZE', 1000),
    ],
];
