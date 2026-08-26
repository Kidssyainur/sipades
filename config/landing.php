<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Konten Landing Page
|--------------------------------------------------------------------------
| Data statis landing page (bersumber dari dokumen "Landing-page.md").
| Admin desa dapat mengedit langsung file ini tanpa menyentuh markup.
*/

return [
    'nama' => 'Desa Karduluk',
    'tagline' => 'Sentra Ukir Madura',
    'wilayah' => 'Kecamatan Pragaan, Kabupaten Sumenep, Jawa Timur',
    'kode_pos' => '69465',

    // --- Statistik kunci (hero & stats bar) ---
    'statistik' => [
        ['nilai' => '11,89', 'target' => 11, 'suffix' => 'km²', 'counter' => false, 'label' => 'Luas Wilayah', 'deskripsi' => 'Desa terluas di Kecamatan Pragaan (±20,5% luas kecamatan)'],
        ['nilai' => '11.535', 'target' => 11535, 'suffix' => '+', 'label' => 'Penduduk', 'deskripsi' => 'Laki-laki 5.576 · Perempuan 5.959'],
        ['nilai' => '12', 'target' => 12, 'suffix' => '', 'label' => 'Dusun', 'deskripsi' => 'Termasuk dusun pesisir Blajud, Bandungan, Rengperreng, dll.'],
        ['nilai' => '600', 'target' => 600, 'suffix' => '+', 'label' => 'Pengrajin Ukir', 'deskripsi' => 'Terlibat mengukir, merakit, hingga finishing produk mebel'],
    ],

    // --- Profil & Sejarah ---
    'profil' => [
        'ringkas' => 'Desa pesisir sekaligus agraris di Pulau Madura, dikenal turun-temurun sebagai pusat kerajinan ukir kayu khas Madura — satu-satunya sentra ukir di Madura dengan kualitas karya yang telah menembus pasar mancanegara.',
        'sejarah' => [
            'Asal-usul nama: wilayah ini dahulu dikenal sebagai "Koel" — daerah/kampung ukiran — karena hampir seluruh warganya menekuni seni pahat kayu secara turun-temurun sejak zaman leluhur.',
            'Jejak sejarah: seni ukir Karduluk dipercaya sudah ada sejak masa Keraton Sumenep; sebagian perabot keraton seperti tempat tidur raja merupakan karya pengukir asal Karduluk.',
            'Tradisi Sangkolan: praktik pewarisan harta yang memadukan hukum adat Madura dengan hukum waris Islam (fikih mawaris), pernah menjadi kajian antropologi hukum Islam.',
        ],
        'karakter' => 'Memiliki garis pantai, area persawahan/ladang, dan permukiman industri rumah tangga.',
        'ikon' => ['Ukiran kayu Madura', 'Mebel & furnitur', 'Gula merah (Jhubâtâ)', 'Wisata Batu Sulung', 'Viaduk kolonial'],
    ],

    // --- Pemerintahan ---
    'pemerintahan' => [
        'kades' => 'Ahmad Faruq',
        'kades_periode' => '2023–2026',
        'struktur' => [
            'Kepala Desa',
            'Sekretaris Desa',
            'Kaur Umum & Perencanaan',
            'Kaur Keuangan',
            'Kasi Pemerintahan',
            'Kasi Kesejahteraan',
            'Kasi Pelayanan',
            'Kepala Dusun (12 dusun)',
            'BPD — Badan Permusyawaratan Desa',
        ],
        'dana_desa' => [
            'tahun' => 2025,
            'total' => 'Rp 2.215.788.000',
            'keterangan' => 'Pagu Dana Desa terbesar se-Kabupaten Sumenep (dari total 330 desa)',
            'rincian' => [
                'Alokasi Dasar' => 'Rp 808.143.000',
                'Alokasi Formula' => 'Rp 1.149.135.000',
                'Alokasi Kinerja' => 'Rp 258.510.000',
            ],
        ],
    ],

    // --- Potensi Ekonomi & UMKM ---
    'potensi' => [
        'ukir' => [
            'judul' => 'Kerajinan Ukir Kayu Madura',
            'deskripsi' => 'Ikon utama desa — sentra ukir kayu khas Madura yang telah dikenal hingga mancanegara.',
            'ciri' => 'Motif ornamen daun, sulur, bunga, dan buah — menghindari motif makhluk hidup karena nilai keagamaan masyarakat setempat.',
            'angka' => [
                ['nilai' => '200+', 'label' => 'Sentra/unit usaha pengrajin'],
                ['nilai' => '592', 'label' => 'Pengrajin Karduluk dalam program Sumenep Mengukir (2018)'],
                ['nilai' => '600', 'label' => 'Warga terlibat langsung dalam proses ukir'],
            ],
            'produk' => ['Kursi & meja ukir', 'Lemari & tempat tidur (Sofa Kraton)', 'Kusen & pintu berukir', 'Kurungan ayam bekisar'],
            'pemasaran' => 'Produk telah dipasarkan ke luar Madura dan luar negeri; kerap mengikuti pameran tingkat kabupaten, provinsi, hingga nasional (Jakarta, Bali).',
        ],
        'pangan' => [
            'judul' => 'Jhubâtâ — Gula Merah Khas Desa',
            'deskripsi' => 'Produk rumahan khas desa; salah satu produsennya adalah usaha rumahan "Harum Manis" di Dusun Blajud.',
        ],
        'sektor' => [
            'judul' => 'Pertanian & Perikanan',
            'deskripsi' => 'Lahan sawah, ladang, dan tegalan sebagai sumber penghidupan sebagian warga; nelayan tangkap aktif di dusun pesisir seperti Blajud.',
        ],
    ],

    // --- Wisata ---
    'wisata' => [
        ['nama' => 'Batu Sulung', 'lokasi' => 'Dusun Blajud, RT 01/RW 02', 'deskripsi' => 'Destinasi wisata alam yang masih alami, diakses melalui jalan paving lalu jalan setapak; menyuguhkan pemandangan laut lepas dari ketinggian tebing.', 'ikon' => 'gunung'],
        ['nama' => 'Viaduk Peninggalan Kolonial', 'lokasi' => 'Dusun Blajud, dekat bibir pantai', 'deskripsi' => 'Bekas jembatan rel kereta api era kolonial (awal abad ke-20) dengan deretan lengkungan pilar penyangga pendek yang berjejer memanjang — kini aset PT KAI.', 'ikon' => 'jembatan'],
        ['nama' => 'Tosolong / Bato Solong', 'lokasi' => 'Dusun Blajud, tak jauh dari viaduk', 'deskripsi' => 'Formasi bukit batu karang unik dengan rongga besar menyerupai terowongan dan sumur tua peninggalan kuno. Hati-hati: bebatuan tajam dan licin.', 'ikon' => 'batu'],
        ['nama' => 'Wisata Edukasi Ukir', 'lokasi' => 'Sentra pengrajin di berbagai dusun', 'deskripsi' => 'Saksikan langsung proses mengukir, merakit, hingga finishing produk mebel — potensial sebagai paket wisata edukasi/kriya.', 'ikon' => 'ukir'],
    ],
    'wisata_akses' => 'Untuk mencapai kawasan viaduk dan Tosolong, jarak tempuh sekitar 2 km melalui jalan kampung dari Balai Desa/Kantor Kepala Desa Karduluk.',

    // --- Pendidikan & Fasilitas ---
    'fasilitas' => [
        'Yayasan An Najah I — PAUD, TK, MI, MTs, MA, hingga Pondok Pesantren',
        'SDN Karduluk IV — sekolah dasar negeri di desa',
        'Posyandu & layanan kesehatan dasar, dikelola bersama PKK Desa (Posyandu Bangga Kencana)',
        'Balai Desa Karduluk — pusat layanan administrasi dan pemerintahan',
    ],

    // --- Berita & Kegiatan (kronologis) ---
    'berita' => [
        ['tanggal' => '31 Juli 2026', 'judul' => 'Potensi Wisata Sejarah Viaduk & Tosolong Terekspos', 'isi' => 'Media Center Diskominfo Sumenep mengangkat potensi wisata viaduk kolonial dan formasi batu Tosolong di Dusun Blajud sebagai daya tarik wisata pesisir selatan Sumenep.'],
        ['tanggal' => '9 Juli 2026', 'judul' => 'Nelayan Warga Meninggal, Tiga Penumpang Selamat', 'isi' => 'Seorang nelayan warga Desa Karduluk meninggal setelah perahu yang digunakan menebar jaring tenggelam di perairan Desa Pakamban Laok; tiga penumpang lain berhasil diselamatkan.'],
        ['tanggal' => '2 Februari 2026', 'judul' => 'Pemulihan Korban Angin Kencang di Empat Dusun', 'isi' => 'Bencana angin kencang disertai hujan deras merusak sekitar 175 rumah dan dua musala di empat dusun; Pemkab Sumenep menyerahkan bantuan pemulihan kepada warga terdampak.'],
        ['tanggal' => '2025', 'judul' => 'Dana Desa Terbesar se-Kabupaten Sumenep', 'isi' => 'Desa Karduluk menerima pagu Dana Desa terbesar dari total 330 desa se-Kabupaten Sumenep, senilai Rp 2.215.788.000.'],
        ['tanggal' => '20 Agustus 2024', 'judul' => 'Juara 3 Lomba Posyandu Bangga Kencana', 'isi' => 'Tim Penggerak PKK Desa Karduluk meraih Juara 3 Lomba Posyandu Bangga Kencana tingkat Kabupaten Sumenep.'],
        ['tanggal' => '2018', 'judul' => 'Peluncuran Program "Sumenep Mengukir"', 'isi' => 'Pameran promosi seni ukir yang dibuka langsung oleh Bupati Sumenep di Desa Karduluk, melibatkan ratusan pengrajin se-Kabupaten Sumenep.'],
    ],

    // --- Prestasi ---
    'prestasi' => [
        'Juara 3 Lomba Posyandu Bangga Kencana tingkat Kabupaten Sumenep (2024)',
        'Penerima pagu Dana Desa terbesar se-Kabupaten Sumenep dari 330 desa (2025)',
        'Satu-satunya sentra kerajinan ukir kayu di Madura dengan produk bernilai ekspor',
    ],

    // --- Alur Layanan ---
    'layanan' => [
        [
            'judul' => 'Pengurusan Surat-Menyurat',
            'deskripsi' => 'Surat Pengantar KTP, KK, domisili, usaha, keterangan tidak mampu, dan lain-lain.',
            'langkah' => [
                'Datang ke Kantor/Balai Desa membawa dokumen pendukung (KTP, KK, dan syarat sesuai jenis surat).',
                'Mengambil dan mengisi formulir permohonan pada bagian pelayanan.',
                'Petugas memverifikasi kelengkapan dan kebenaran data.',
                'Berkas diproses dan ditandatangani Sekretaris/Kepala Desa.',
                'Dokumen diberi stempel/cap resmi desa.',
                'Surat selesai dan diserahkan kepada pemohon.',
            ],
        ],
        [
            'judul' => 'Pengaduan & Aspirasi Masyarakat',
            'deskripsi' => 'Wadah menyampaikan aduan dan usulan warga secara berjenjang.',
            'langkah' => [
                'Warga menyampaikan aduan/aspirasi kepada Kepala Dusun setempat.',
                'Kepala Dusun meneruskan aduan ke perangkat desa terkait.',
                'Perangkat desa melakukan verifikasi lapangan dan koordinasi lintas dinas bila perlu.',
                'Tindak lanjut/penyelesaian dilaksanakan sesuai kewenangan desa.',
                'Hasil dilaporkan kembali kepada pelapor/masyarakat.',
            ],
        ],
        [
            'judul' => 'Usulan Pembangunan & Bantuan (Musrenbangdes)',
            'deskripsi' => 'Perencanaan pembangunan dari tingkat dusun hingga desa.',
            'langkah' => [
                'Usulan warga disampaikan melalui Musyawarah Dusun (Musdus).',
                'Hasil Musdus dibawa ke Musyawarah Perencanaan Pembangunan Desa (Musrenbangdes).',
                'Usulan yang disepakati masuk ke RKP Desa dan APBDes.',
                'Pelaksanaan program didanai dari Dana Desa/ADD/sumber lain.',
                'Pelaporan & pertanggungjawaban kepada BPD dan masyarakat.',
            ],
        ],
    ],

    // --- Kontak ---
    'kontak' => [
        'alamat' => 'Balai Desa / Kantor Kepala Desa Karduluk, Kec. Pragaan, Kab. Sumenep, Jawa Timur 69465',
        'telepon' => '0852-5737-9290',
        'email' => 'kardulukukir@gmail.com',
        'kanal' => [
            ['label' => 'KIM Pragaan (Portal Berita Kecamatan)', 'url' => 'https://kimpragaan.com'],
            ['label' => 'Pemerintah Kabupaten Sumenep', 'url' => 'https://sumenepkab.go.id'],
        ],
    ],
];
