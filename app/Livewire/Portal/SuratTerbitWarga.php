<?php

namespace App\Livewire\Portal;

use App\Models\SuratTerbit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.portal')]
#[Title('Surat Terbit Saya')]
class SuratTerbitWarga extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $userId = Auth::id();

        $query = SuratTerbit::query()
            ->whereHas('pengajuanSurat', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['pengajuanSurat.jenisSurat']);

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                    ->orWhereHas('pengajuanSurat.jenisSurat', function ($j) {
                        $j->where('nama', 'like', '%'.$this->search.'%');
                    });
            });
        }

        $suratList = $query->latest('tanggal_terbit')->paginate(10);

        return view('livewire.portal.surat-terbit-warga', [
            'suratList' => $suratList,
        ]);
    }
}
