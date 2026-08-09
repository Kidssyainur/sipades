<?php

namespace App\Filament\Pages;

use App\Models\NotifikasiLog;
use App\Services\WhatsappGatewayService;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Kstmostofa\LaravelWhatsApp\Exceptions\SidecarException;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use UnitEnum;

class WhatsappGatewaySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?string $navigationLabel = 'Status & Pairing WhatsApp';

    protected static ?string $title = 'Status, QR Pairing & Tes WhatsApp Gateway';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.pages.whatsapp-gateway-settings';

    public string $sessionId = 'main';

    public ?string $qr = null;

    public ?array $data = [];

    public ?array $statusKoneksi = null;

    public function mount(WhatsappGatewayService $gateway): void
    {
        $this->form->fill([
            'no_hp' => '6281234567890',
            'pesan' => 'Halo! Ini adalah pesan pengujian dari Sistem Informasi Pelayanan Desa Karduluk (SIPADES) via WhatsApp Gateway.',
        ]);

        $this->cekStatusKoneksi($gateway);
    }

    public function cekStatusKoneksi(WhatsappGatewayService $gateway): void
    {
        $this->statusKoneksi = $gateway->checkConnectionStatus($this->sessionId);
    }

    public function startSession(): void
    {
        try {
            $response = WhatsApp::web($this->sessionId)->start();

            $this->qr = $response['qr'] ?? null;
            $status = $response['status'] ?? 'qr';

            $this->statusKoneksi = [
                'online' => ($status === 'ready'),
                'status' => strtoupper($status),
                'pesan' => ($status === 'ready') ? 'Sesi WhatsApp siap digunakan.' : 'Sesi dimulai. Silakan scan QR code.',
                'response' => $response,
            ];

            Notification::make()
                ->title('Sesi WhatsApp Dimulai')
                ->body(($status === 'ready') ? 'Sesi sudah terhubung!' : 'Silakan scan QR Code yang muncul.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal Memulai Sesi')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function stopSession(WhatsappGatewayService $gateway): void
    {
        try {
            WhatsApp::web($this->sessionId)->stop();
            $this->qr = null;
            $this->cekStatusKoneksi($gateway);

            Notification::make()->title('Sesi WhatsApp Dihentikan')->info()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Gagal Menghentikan Sesi')->body($e->getMessage())->danger()->send();
        }
    }

    public function destroySession(WhatsappGatewayService $gateway): void
    {
        try {
            WhatsApp::web($this->sessionId)->destroy();
            $this->qr = null;
            $this->cekStatusKoneksi($gateway);

            Notification::make()->title('Sesi WhatsApp Dihapus (Kredensial di-reset)')->warning()->send();
        } catch (\Throwable $e) {
            Notification::make()->title('Gagal Menghapus Sesi')->body($e->getMessage())->danger()->send();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Tes Pengiriman Pesan WhatsApp Direct')
                    ->description('Kirim pesan simulasi langsung melalui laravel-whatsapp Web Sidecar untuk memverifikasi koneksi.')
                    ->schema([
                        TextInput::make('no_hp')
                            ->label('Nomor HP Tujuan')
                            ->required()
                            ->placeholder('08xxx / 628xxx'),
                        Textarea::make('pesan')
                            ->label('Isi Pesan Test')
                            ->required()
                            ->rows(4),
                    ]),
            ]);
    }

    public function kirimTestPesan(WhatsappGatewayService $gateway): void
    {
        $state = $this->form->getState();

        $noHp = $state['no_hp'];
        $pesan = $state['pesan'];

        try {
            $hasil = $gateway->send($noHp, $pesan, $this->sessionId);

            NotifikasiLog::create([
                'user_id' => auth()->id(),
                'no_hp_tujuan' => $noHp,
                'pesan' => $pesan,
                'status' => $hasil['sukses'] ? 'terkirim' : 'gagal',
                'percobaan' => 1,
                'response_gateway' => is_string($hasil['body']) ? $hasil['body'] : json_encode($hasil['body']),
                'dikirim_pada' => $hasil['sukses'] ? now() : null,
            ]);

            if ($hasil['sukses']) {
                Notification::make()
                    ->title('Pesan WhatsApp Berhasil Terkirim!')
                    ->body('Gateway merespons sukses.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Pengiriman Pesan Gagal')
                    ->body('Gateway merespons Status ' . $hasil['status_code'] . ': ' . $hasil['body'])
                    ->danger()
                    ->send();
            }
        } catch (\Throwable $e) {
            NotifikasiLog::create([
                'user_id' => auth()->id(),
                'no_hp_tujuan' => $noHp,
                'pesan' => $pesan,
                'status' => 'gagal',
                'percobaan' => 1,
                'response_gateway' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Koneksi WhatsApp Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
