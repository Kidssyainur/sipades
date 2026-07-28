<?php

namespace App\Livewire\Portal;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.portal')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $userId = Auth::id();

        $pengajuan = PengajuanSurat::query()
            ->where('user_id', $userId)
            ->with('jenisSurat')
            ->latest('tanggal_pengajuan')
            ->paginate(10);

        $ringkasan = [
            'total' => PengajuanSurat::where('user_id', $userId)->count(),
            'proses' => PengajuanSurat::where('user_id', $userId)
                ->whereNotIn('status', [StatusPengajuan::SELESAI, StatusPengajuan::DITOLAK])
                ->count(),
            'selesai' => PengajuanSurat::where('user_id', $userId)
                ->where('status', StatusPengajuan::SELESAI)
                ->count(),
        ];

        return view('livewire.portal.dashboard', [
            'pengajuan' => $pengajuan,
            'ringkasan' => $ringkasan,
        ]);
    }
}
