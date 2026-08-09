<?php

namespace Tests\Unit;

use App\Services\WhatsappGatewayService;
use Kstmostofa\LaravelWhatsApp\Web\Resources\MessagesResource;
use Kstmostofa\LaravelWhatsApp\Web\WebClient;
use Kstmostofa\LaravelWhatsApp\Web\WebSession;
use Mockery;
use Tests\TestCase;

class WhatsappGatewayServiceTest extends TestCase
{
    private WhatsappGatewayService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new WhatsappGatewayService();
    }

    public function test_format_no_hp_converts_local_08_to_628(): void
    {
        $this->assertEquals('628123456789', $this->service->formatNoHp('08123456789'));
        $this->assertEquals('628123456789', $this->service->formatNoHp('+628123456789'));
        $this->assertEquals('628123456789', $this->service->formatNoHp('628123456789'));
        $this->assertEquals('628123456789', $this->service->formatNoHp('0812-3456-789'));
    }

    public function test_check_connection_status_returns_online_when_ready(): void
    {
        $mockWebSession = Mockery::mock(WebSession::class);
        $mockWebSession->shouldReceive('state')
            ->once()
            ->andReturn(['status' => 'ready']);

        $mockWebClient = Mockery::mock(WebClient::class);
        $mockWebClient->shouldReceive('session')
            ->with('main')
            ->once()
            ->andReturn($mockWebSession);

        $this->app->instance(WebClient::class, $mockWebClient);

        $result = $this->service->checkConnectionStatus('main');

        $this->assertTrue($result['online']);
        $this->assertEquals('READY', $result['status']);
        $this->assertStringContainsString('online', strtolower($result['pesan']));
    }

    public function test_send_returns_success_response(): void
    {
        $mockMessages = Mockery::mock(MessagesResource::class);
        $mockMessages->shouldReceive('sendText')
            ->with('628123456789', 'Pesan pengujian')
            ->once()
            ->andReturn(['id' => 'msg_123', 'status' => 'sent']);

        $mockWebSession = Mockery::mock(WebSession::class);
        $mockWebSession->shouldReceive('messages')
            ->once()
            ->andReturn($mockMessages);

        $mockWebClient = Mockery::mock(WebClient::class);
        $mockWebClient->shouldReceive('session')
            ->with('main')
            ->once()
            ->andReturn($mockWebSession);

        $this->app->instance(WebClient::class, $mockWebClient);

        $result = $this->service->send('08123456789', 'Pesan pengujian', 'main');

        $this->assertTrue($result['sukses']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertNotNull($result['json']);
    }
}
