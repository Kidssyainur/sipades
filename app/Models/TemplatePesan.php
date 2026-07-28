<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePesan extends Model
{
    protected $table = 'template_pesan';

    protected $fillable = ['kode', 'judul', 'isi_template', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function render(array $data): string
    {
        $pesan = $this->isi_template;
        foreach ($data as $key => $value) {
            $pesan = str_replace('{'.$key.'}', (string) $value, $pesan);
        }

        return $pesan;
    }
}
