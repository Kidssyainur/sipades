<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function show(Berita $berita)
    {
        abort_unless($berita->status === 'published', 404);

        $berita->increment('views');

        $beritaTerbaru = Berita::query()
            ->published()
            ->terbaru()
            ->whereKeyNot($berita->getKey())
            ->limit(3)
            ->get();

        return view('landing.berita-detail', [
            'berita' => $berita,
            'beritaTerbaru' => $beritaTerbaru,
        ]);
    }
}
