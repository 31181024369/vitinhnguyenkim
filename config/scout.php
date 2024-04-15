<?php

return [
    'client' => [
        'hosts' => [
            env('SCOUT_ELASTIC_HOST', 'SCOUT_ELASTIC_HOST=http://elasticsearch:9200'),
        ],
    ],
    'update_mapping' => env('SCOUT_ELASTIC_UPDATE_MAPPING', true),
    'indexer' => env('SCOUT_ELASTIC_INDEXER', 'single'),
    'document_refresh' => env('SCOUT_ELASTIC_DOCUMENT_REFRESH'),

    'elasticsearch' => [
        'index' => env('ELASTICSEARCH_INDEX', 'department'),
        'hosts' => [
            env('ELASTICSEARCH_HOSTS', 'http://localhost:9200'),
        ],
    ],
];