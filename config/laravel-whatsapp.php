<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Meta Graph API connection (Cloud API backend)
    |--------------------------------------------------------------------------
    */

    'base_host' => env('WHATSAPP_BASE_HOST', 'graph.facebook.com'),

    'api_version' => env('WHATSAPP_API_VERSION', 'v21.0'),

    'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),

    'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),

    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),

    'timeout' => (int) env('WHATSAPP_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Webhook receiver (Cloud API)
    |--------------------------------------------------------------------------
    */

    'webhook' => [
        'enabled' => env('WHATSAPP_WEBHOOK_ENABLED', true),
        'route' => env('WHATSAPP_WEBHOOK_ROUTE', 'webhooks/whatsapp'),
        'middleware' => ['api'],
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'verify_signature' => env('WHATSAPP_VERIFY_SIGNATURE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cloud API event dispatch
    |--------------------------------------------------------------------------
    */

    'events' => [
        'message' => \Kstmostofa\LaravelWhatsApp\Events\MessageReceived::class,
        'status' => \Kstmostofa\LaravelWhatsApp\Events\MessageStatusUpdate::class,
        'interactive' => \Kstmostofa\LaravelWhatsApp\Events\InteractiveReplied::class,
        'media' => \Kstmostofa\LaravelWhatsApp\Events\MediaReceived::class,
        'template_status' => \Kstmostofa\LaravelWhatsApp\Events\TemplateStatusUpdate::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Web backend (whatsapp-web.js sidecar)
    |--------------------------------------------------------------------------
    */

    'web' => [
        'enabled' => env('WHATSAPP_WEB_ENABLED', true),
        'host' => env('WHATSAPP_WEB_HOST', '127.0.0.1'),
        'port' => (int) env('WHATSAPP_WEB_PORT', 3000),
        'token' => env('WHATSAPP_WEB_TOKEN'),
        'timeout' => (int) env('WHATSAPP_WEB_TIMEOUT', 60),

        'sidecar' => [
            'path' => env('WHATSAPP_WEB_SIDECAR_PATH', base_path('vendor/kstmostofa/laravel-whatsapp/sidecar')),
            'node_binary' => env('WHATSAPP_WEB_NODE_BINARY', 'node'),
            'npm_binary' => env('WHATSAPP_WEB_NPM_BINARY', 'npm'),
            'chrome_path' => env('WHATSAPP_WEB_CHROME_PATH', env('PUPPETEER_EXECUTABLE_PATH')),
            'session_dir' => env('WHATSAPP_WEB_SESSION_DIR', storage_path('app/whatsapp-sidecar/sessions')),
            'auto_start_sessions' => env('WHATSAPP_WEB_AUTO_START_SESSIONS', true),
            'pid_file' => env('WHATSAPP_WEB_PID_FILE', storage_path('app/whatsapp-sidecar/sidecar.pid')),
            'log_file' => env('WHATSAPP_WEB_LOG_FILE', storage_path('logs/whatsapp-sidecar.log')),
            'err_file' => env('WHATSAPP_WEB_ERR_FILE', storage_path('logs/whatsapp-sidecar.err.log')),
        ],

        'events' => [
            'message' => \Kstmostofa\LaravelWhatsApp\Events\Web\MessageReceived::class,
            'ready' => \Kstmostofa\LaravelWhatsApp\Events\Web\SessionReady::class,
            'qr' => \Kstmostofa\LaravelWhatsApp\Events\Web\QrGenerated::class,
            'disconnected' => \Kstmostofa\LaravelWhatsApp\Events\Web\Disconnected::class,
            'message_ack' => \Kstmostofa\LaravelWhatsApp\Events\Web\MessageAck::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bundled admin UI (Livewire)
    |--------------------------------------------------------------------------
    */

    'ui' => [
        'enabled' => env('WHATSAPP_UI_ENABLED', false),
        'route_prefix' => env('WHATSAPP_UI_PREFIX', 'whatsapp'),
        'middleware' => ['web'],
        'tailwind_cdn' => env('WHATSAPP_UI_TAILWIND_CDN', true),
        'default_session' => env('WHATSAPP_UI_DEFAULT_SESSION', 'main'),
        'poll_interval' => env('WHATSAPP_UI_POLL_INTERVAL', '5s'),
        'css_mode' => env('WHATSAPP_UI_CSS_MODE', 'vite'),
        'chat_list_limit' => (int) env('WHATSAPP_UI_CHAT_LIST_LIMIT', 50),
        'messages_initial' => (int) env('WHATSAPP_UI_MESSAGES_INITIAL', 50),
        'messages_page_size' => (int) env('WHATSAPP_UI_MESSAGES_PAGE_SIZE', 50),
        'chats_cache_seconds' => (int) env('WHATSAPP_UI_CHATS_CACHE_SECONDS', 3),
        'contacts_cache_seconds' => (int) env('WHATSAPP_UI_CONTACTS_CACHE_SECONDS', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting (live UI updates via Reverb / Pusher / Ably)
    |--------------------------------------------------------------------------
    */

    'broadcasting' => [
        'enabled' => env('WHATSAPP_BROADCAST', false),
        'channel_prefix' => env('WHATSAPP_BROADCAST_PREFIX', 'whatsapp'),
        'channel_type' => env('WHATSAPP_BROADCAST_CHANNEL', 'public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Opt-in persistence — write events to the WA models
    |--------------------------------------------------------------------------
    */

    'persist' => [
        'incoming_messages' => env('WHATSAPP_PERSIST_INCOMING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    */

    'database' => [
        'connection' => env('WHATSAPP_DB_CONNECTION'),
        'prefix' => env('WHATSAPP_DB_PREFIX', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    */

    'queue' => [
        'connection' => env('WHATSAPP_QUEUE_CONNECTION'),
        'queue' => env('WHATSAPP_QUEUE', 'default'),
    ],

];
