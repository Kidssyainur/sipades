<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Tamu yang membuka root dapat melihat halaman landing publik.
     */
    public function test_the_application_renders_landing_page_for_guests(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Alur Pengajuan');
        $response->assertSee('Offline');
        $response->assertSee('SIPADES');
        $response->assertSee('Jam Loket');
        $response->assertSee('Berkas Wajib');
        $response->assertSee('Surat Pengantar RT / RW');
        $response->assertSee('Perbandingan: Pengajuan Offline vs SIPADES Online');
    }
}
