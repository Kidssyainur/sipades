<?php

namespace Tests\Feature;

use App\Filament\Pages\WhatsappGatewaySettings;
use App\Models\NotifikasiLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kstmostofa\LaravelWhatsApp\Web\Resources\MessagesResource;
use Kstmostofa\LaravelWhatsApp\Web\WebClient;
use Kstmostofa\LaravelWhatsApp\Web\WebSession;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class WhatsappGatewaySettingsPageTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@karduluk.desa.id',
        ]);
    }

    public function test_whatsapp_gateway_settings_page_can_be_rendered(): void
    {
        $mockWebSession = Mockery::mock(WebSession::class);
        $mockWebSession->shouldReceive('state')
            ->andReturn(['status' => 'ready']);

        $mockWebClient = Mockery::mock(WebClient::class);
        $mockWebClient->shouldReceive('session')
            ->with('main')
            ->andReturn($mockWebSession);

        $this->app->instance(WebClient::class, $mockWebClient);

        $this->actingAs($this->user);

        Livewire::test(WhatsappGatewaySettings::class)
            ->assertStatus(200)
            ->assertSee('Status Live WhatsApp Web Sidecar');
    }

    public function test_can_send_test_whatsapp_message_from_settings_page(): void
    {
        $mockMessages = Mockery::mock(MessagesResource::class);
        $mockMessages->shouldReceive('sendText')
            ->with('6281234567890', 'Test pesan singkat')
            ->once()
            ->andReturn(['id' => 'msg_999', 'status' => 'sent']);

        $mockWebSession = Mockery::mock(WebSession::class);
        $mockWebSession->shouldReceive('state')
            ->andReturn(['status' => 'ready']);
        $mockWebSession->shouldReceive('messages')
            ->once()
            ->andReturn($mockMessages);

        $mockWebClient = Mockery::mock(WebClient::class);
        $mockWebClient->shouldReceive('session')
            ->with('main')
            ->andReturn($mockWebSession);

        $this->app->instance(WebClient::class, $mockWebClient);

        $this->actingAs($this->user);

        Livewire::test(WhatsappGatewaySettings::class)
            ->fillForm([
                'no_hp' => '081234567890',
                'pesan' => 'Test pesan singkat',
            ])
            ->call('kirimTestPesan')
            ->assertHasNoFormErrors()
            ->assertNotified('Pesan WhatsApp Berhasil Terkirim!');

        $this->assertDatabaseHas('notifikasi_log', [
            'user_id' => $this->user->id,
            'no_hp_tujuan' => '081234567890',
            'pesan' => 'Test pesan singkat',
            'status' => 'terkirim',
        ]);
    }
}
