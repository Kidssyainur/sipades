{{-- Template fallback generik untuk jenis surat tanpa template khusus. --}}
@extends('surat.partials.layout', ['judul' => $pengajuan->jenisSurat->nama ?? 'Surat Keterangan'])

@section('isi')
    @include('surat.partials.isi-default')
@endsection
