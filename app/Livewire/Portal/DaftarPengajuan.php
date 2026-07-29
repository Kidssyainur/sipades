<?php

namespace App\Livewire\Portal;

use App\Models\PengajuanSurat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.portal')]
#[Title('Pengajuan Saya')]
class DaftarPengajuan extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = Auth::id();

        $query = PengajuanSurat::query()
            ->where('user_id', $userId)
            ->with(['jenisSurat']);

        if (! empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nomor_referensi', 'like', '%'.$this->search.'%')
                    ->orWhereHas('jenisSurat', function ($j) {
                        $j->where('nama', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $pengajuan = $query->latest('tanggal_pengajuan')->paginate(10);

        return view('livewire.portal.daftar-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }
}
