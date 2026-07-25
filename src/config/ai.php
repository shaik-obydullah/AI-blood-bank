<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider used for text generation.
    | You may change this to "openai", "anthropic", "ollama", etc.
    |
    */

    'default' => env('AI_PROVIDER', 'ollama'),

    /*
    |--------------------------------------------------------------------------
    | Default Models
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default models for each AI capability.
    | These can be overridden per-agent using PHP attributes.
    |
    */

    'models' => [
        'text' => env('AI_MODEL', 'llama3.2:3b'),
        'images' => env('AI_IMAGE_MODEL', 'dall-e-3'),
        'audio' => env('AI_AUDIO_MODEL', 'tts-1'),
        'transcription' => env('AI_TRANSCRIPTION_MODEL', 'whisper-1'),
        'embeddings' => env('AI_EMBEDDINGS_MODEL', 'nomic-embed-text'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Configure each AI provider here. The Ollama provider is pre-configured
    | for local development with Docker.
    |
    */

    'providers' => [

        'ollama' => [
            'driver' => 'ollama',
            'key' => env('OLLAMA_API_KEY', ''),
            'url' => env('OLLAMA_URL', 'http://localhost:11434/api'),
        ],

        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY', ''),
            'organization' => env('OPENAI_ORGANIZATION', ''),
            'project' => env('OPENAI_PROJECT', ''),
        ],

        'anthropic' => [
            'driver' => 'anthropic',
            'key' => env('ANTHROPIC_API_KEY', ''),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Embeddings Configuration
    |--------------------------------------------------------------------------
    |
    | Configure vector embeddings for semantic search. Ollama supports
    | embeddings via the nomic-embed-text model.
    |
    */

    'embeddings' => [
        'driver' => env('AI_PROVIDER', 'ollama'),
        'model' => env('AI_EMBEDDINGS_MODEL', 'nomic-embed-text'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Vector Search Configuration
    |--------------------------------------------------------------------------
    |
    | Configure vector search for semantic similarity queries.
    | Laravel 13 supports pgvector for PostgreSQL.
    |
    */

    'vector' => [
        'enabled' => env('AI_VECTOR_ENABLED', false),
        'dimensions' => env('AI_VECTOR_DIMENSIONS', 1536),
    ],

];
