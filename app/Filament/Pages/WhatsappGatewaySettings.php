<?php

namespace App\Filament\Pages;

use App\Models\NotifikasiLog;
use App\Services\WhatsappGatewayService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use UnitEnum;

class WhatsappGatewaySettings extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?string $navigationLabel = 'Status & Pairing WhatsApp';

    protected static ?string $title = 'Status, QR Pairing & Tes WhatsApp Gateway';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.pages.whatsapp-gateway-settings';

    public string $sessionId = 'main';

    public ?string $qr = null;

    public bool $isQrModalOpen = false;

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

        $statusStr = strtolower($this->statusKoneksi['status'] ?? '');

        if (in_array($statusStr, ['qr', 'initializing', 'unknown'])) {
            $fetchedQr = $gateway->getQrCode($this->sessionId);
            if ($fetchedQr) {
                $this->qr = $fetchedQr;
            }
        } elseif ($statusStr === 'ready') {
            if ($this->isQrModalOpen) {
                $this->isQrModalOpen = false;
                $this->unmountAction();
                Notification::make()
                    ->title('WhatsApp Berhasil Terhubung!')
                    ->body('Pairing sukses. Sesi WhatsApp sudah aktif dan siap mengirim pesan.')
                    ->success()
                    ->send();
            }
            $this->qr = null;
        }
    }

    public function startSessionAction(): Action
    {
        return Action::make('startSession')
            ->label('Start / Pairing QR')
            ->icon('heroicon-o-qr-code')
            ->color('success')
            ->action(function (WhatsappGatewayService $gateway) {
                try {
                    $gateway->ensureSidecarRunning();
                    $response = WhatsApp::web($this->sessionId)->start();

                    $this->qr = $response['qr'] ?? null;
                    $status = strtolower($response['status'] ?? 'qr');

                    if (empty($this->qr) && in_array($status, ['qr', 'initializing'])) {
                        $this->qr = $gateway->getQrCode($this->sessionId);
                    }

                    $this->cekStatusKoneksi($gateway);

                    if ($status === 'ready') {
                        $this->isQrModalOpen = false;
                        Notification::make()
                            ->title('WhatsApp Sudah Terhubung!')
                            ->body('Perangkat Anda sudah aktif. Jika ingin menghubungkan HP/nomor baru, klik Reset Pairing (Hapus Sesi).')
                            ->info()
                            ->send();
                    } else {
                        $this->isQrModalOpen = true;
                        $this->mountAction('showQrModal');
                    }
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Gagal Memulai Sesi')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public function showQrModalAction(): Action
    {
        return Action::make('showQrModal')
            ->label('Lihat QR Code')
            ->icon('heroicon-o-qr-code')
            ->color('warning')
            ->modalHeading('Pairing Perangkat WhatsApp')
            ->modalDescription('Arahkan kamera aplikasi WhatsApp di smartphone Anda ke QR Code berikut.')
            ->modalContent(fn () => view('filament.pages.partials.qr-modal-content', ['qr' => $this->qr]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup Modal')
            ->action(fn () => null);
    }

    public function destroySessionAction(): Action
    {
        return Action::make('destroySession')
            ->label('Reset Pairing (Hapus Sesi)')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Reset Pairing WhatsApp?')
            ->modalDescription('Apakah Anda yakin ingin me-reset (hapus) sesi WhatsApp? Perangkat terhubung akan di-logout dan QR Code baru akan dibuat untuk scan ulang.')
            ->modalSubmitActionLabel('Ya, Reset & Hapus Sesi')
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->action(function (WhatsappGatewayService $gateway) {
                try {
                    WhatsApp::web($this->sessionId)->destroy();
                    $this->qr = null;

                    WhatsApp::web($this->sessionId)->start();
                    $this->qr = $gateway->getQrCode($this->sessionId);
                    $this->isQrModalOpen = true;
                    $this->cekStatusKoneksi($gateway);

                    Notification::make()
                        ->title('Sesi Kredensial Dihapus')
                        ->body('Kredensial lama telah di-reset. Sesi baru dibuat, silakan scan QR Code.')
                        ->warning()
                        ->send();

                    $this->mountAction('showQrModal');
                } catch (\Throwable $e) {
                    Notification::make()->title('Gagal Menghapus Sesi')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public function stopSessionAction(): Action
    {
        return Action::make('stopSession')
            ->label('Stop Sesi')
            ->icon('heroicon-o-pause')
            ->color('gray')
            ->action(function (WhatsappGatewayService $gateway) {
                try {
                    WhatsApp::web($this->sessionId)->stop();
                    $this->qr = null;
                    $this->isQrModalOpen = false;
                    $this->unmountAction();
                    $this->cekStatusKoneksi($gateway);

                    Notification::make()->title('Sesi WhatsApp Dihentikan')->info()->send();
                } catch (\Throwable $e) {
                    Notification::make()->title('Gagal Menghentikan Sesi')->body($e->getMessage())->danger()->send();
                }
            });
    }

    public function startSidecarAction(): Action
    {
        return Action::make('startSidecarNodeProcess')
            ->label('Jalankan Sidecar Node.js')
            ->icon('heroicon-o-play')
            ->color('info')
            ->action(function (WhatsappGatewayService $gateway) {
                try {
                    $gateway->ensureSidecarRunning();
                    $this->cekStatusKoneksi($gateway);

                    Notification::make()
                        ->title('Proses Sidecar Node.js Berjalan')
                        ->body('Server sidecar HTTP 127.0.0.1:3000 siap menerima permintaan.')
                        ->success()
                        ->send();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->title('Gagal Menjalankan Sidecar Node.js')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    public function startSession(WhatsappGatewayService $gateway): void
    {
        $this->startSessionAction()->call(['gateway' => $gateway]);
    }

    public function destroySession(WhatsappGatewayService $gateway): void
    {
        $this->destroySessionAction()->call(['gateway' => $gateway]);
    }

    public function stopSession(WhatsappGatewayService $gateway): void
    {
        $this->stopSessionAction()->call(['gateway' => $gateway]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('no_hp')
                    ->label('Nomor HP Tujuan')
                    ->required()
                    ->placeholder('Contoh: 081234567890 / 6281234567890')
                    ->helperText('Format lokal (08xx) otomatis dikonversi ke format internasional (628xx).'),
                Textarea::make('pesan')
                    ->label('Isi Pesan Test')
                    ->required()
                    ->rows(3)
                    ->placeholder('Tulis isi pesan pengujian di sini...'),
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
