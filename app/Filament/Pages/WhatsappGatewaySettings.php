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
use UnitEnum;

class WhatsappGatewaySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static ?string $navigationLabel = 'Status & Tes Go-WA Gateway';

    protected static ?string $title = 'Status & Pengujian Go-WA Gateway';

    protected static string|UnitEnum|null $navigationGroup = 'WhatsApp Gateway';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.pages.whatsapp-gateway-settings';

    public ?array $data = [];

    public ?array $statusKoneksi = null;

    public function mount(WhatsappGatewayService $gateway): void
    {
        $this->form->fill([
            'no_hp' => '6281234567890',
            'pesan' => 'Halo! Ini adalah pesan pengujian dari Sistem Informasi Pelayanan Desa Karduluk (SIPADES) via Go-WA Gateway.',
        ]);

        $this->cekStatusKoneksi($gateway);
    }

    public function cekStatusKoneksi(WhatsappGatewayService $gateway): void
    {
        $this->statusKoneksi = $gateway->checkConnectionStatus();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Tes Pengiriman Pesan Direct Go-WA')
                    ->description('Kirim pesan simulasi langsung melalui Go-WA REST API untuk memverifikasi koneksi pengiriman.')
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
            $hasil = $gateway->send($noHp, $pesan);

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
                    ->body('Go-WA Gateway merespons sukses: ' . $hasil['body'])
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Pengiriman Pesan Gagal')
                    ->body('Go-WA Gateway merespons Status ' . $hasil['status_code'] . ': ' . $hasil['body'])
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
                ->title('Koneksi Go-WA Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
