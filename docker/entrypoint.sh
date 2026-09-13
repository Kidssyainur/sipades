#!/usr/bin/env bash
set -e

# Pastikan direktori storage dan cache writable
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/private/surat \
         /var/www/html/storage/app/public \
         /var/www/html/storage/app/whatsapp-sidecar/sessions \
         /var/www/html/bootstrap/cache 2>/dev/null || true
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# WhatsApp sidecar berjalan sebagai service Docker terpisah (service `whatsapp`).
# Container ini hanya menjalankan SSE Listener & Queue Worker yang terhubung ke sidecar tersebut.
if [ -f "/var/www/html/vendor/kstmostofa/laravel-whatsapp/sidecar/index.js" ] && [ -d "/var/www/html/vendor/kstmostofa/laravel-whatsapp/sidecar/node_modules" ]; then
    # Jalankan SSE Listener agar event pesan masuk langsung diteruskan ke Laravel
    echo "Starting WhatsApp SSE Listener daemon..."
    nohup php /var/www/html/artisan whatsapp:web:listen main >> /var/www/html/storage/logs/wa-listener.log 2>&1 &

    # Jalankan background Queue Worker
    echo "Starting Queue Worker daemon..."
    nohup php /var/www/html/artisan queue:work --tries=3 --timeout=90 >> /var/www/html/storage/logs/queue-worker.log 2>&1 &
fi

exec "$@"
