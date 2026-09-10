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

    // --- Alur Pengajuan Surat (Offline vs Online) ---
    'alur_surat_offline' => [
        'judul' => 'Alur Pengajuan Surat Offline (Konvensional di Balai Desa)',
        'deskripsi' => 'Tata cara pengurusan surat secara manual dengan datang langsung ke loket pelayanan Kantor Desa Karduluk seperti sebelumnya.',
        'jam_layanan' => [
            'hari_kerja' => 'Senin – Kamis: 08.00 – 14.30 WIB',
            'jumat' => 'Jumat: 08.00 – 11.30 WIB & 13.00 – 14.30 WIB',
            'libur' => 'Sabtu, Minggu & Hari Libur Nasional Tutup',
            'lokasi' => 'Loket Pelayanan Administrasi Umum, Balai Desa Karduluk',
        ],
        'berkas_wajib' => [
            'Surat Pengantar asli dari Ketua RT / RW setempat',
            'Fotokopi KTP Pemohon (membawa KTP asli untuk verifikasi)',
            'Fotokopi Kartu Keluarga (KK) terbaru',
            'Dokumen pendukung khusus sesuai jenis surat (misal: Bukti PBB, Surat Tanah, Surat Kematian, dll.)',
            'Materai Rp10.000 (bila dipersyaratkan untuk jenis surat tertentu)',
        ],
        'langkah' => [
            [
                'tahap' => '1',
                'judul' => 'Surat Pengantar RT / RW',
                'deskripsi' => 'Pemohon menemui Ketua RT / RW di wilayah dusun masing-masing dengan membawa fotokopi KTP dan KK untuk memperoleh lembar Surat Pengantar resmi.',
                'durasi' => 'Tergantung RT/RW',
            ],
            [
                'tahap' => '2',
                'judul' => 'Datang ke Balai Desa',
                'deskripsi' => 'Mendatangi Balai Desa Karduluk pada hari & jam operasional kerja, lalu mengambil nomor antrean loket pelayanan administrasi umum.',
                'durasi' => '10 – 30 Menit',
            ],
            [
                'tahap' => '3',
                'judul' => 'Penyerahan Berkas & Formulir Kertas',
                'deskripsi' => 'Menyerahkan dokumen fisik (Pengantar RT/RW, fotokopi KTP & KK) kepada petugas loket dan mengisi formulir permohonan kertas secara manual.',
                'durasi' => '15 Menit',
            ],
            [
                'tahap' => '4',
                'judul' => 'Verifikasi Data & Pengetikan Draf',
                'deskripsi' => 'Petugas mencocokkan data pada buku induk kependudukan desa, mengetik draf surat resmi, dan mencatat nomor registrasi di Buku Agenda Surat Keluar.',
                'durasi' => '30 – 60 Menit',
            ],
            [
                'tahap' => '5',
                'judul' => 'Paraf Sekdes & Tanda Tangan Basah Kades',
                'deskripsi' => 'Draf surat diajukan untuk paraf verifikasi Sekretaris Desa, dilanjutkan tanda tangan basah Kepala Desa serta cap/stempel basah resmi desa.',
                'durasi' => '1 – 3 Hari Kerja (bila Kades dinas luar)',
            ],
            [
                'tahap' => '6',
                'judul' => 'Pengambilan Surat Fisik di Loket',
                'deskripsi' => 'Pemohon mengambil lembar fisik surat resmi yang telah bertanda tangan dan berstempel basah. Bila perlu, dilanjutkan legalisasi ke Kantor Kecamatan Pragaan.',
                'durasi' => 'Selesai di Loket',
            ],
        ],
        'catatan' => 'Layanan offline di Balai Desa tetap berjalan normal untuk melayani warga lansia atau warga yang belum terbiasa dengan gawai smartphone. Bagi warga yang menginginkan proses lebih praktis tanpa antre, disarankan memanfaatkan layanan online SIPADES.',
    ],

    'alur_surat_online' => [
        'judul' => 'Alur Pengajuan Surat Online (SIPADES)',
        'deskripsi' => 'Inovasi digital Desa Karduluk untuk pengurusan surat kilat dari rumah tanpa antre, kapan pun dan di mana pun.',
        'langkah' => [
            [
                'tahap' => '1',
                'judul' => 'Daftar / Masuk Akun Warga',
                'deskripsi' => 'Buat akun dengan NIK, nama lengkap, dan nomor WhatsApp aktif. Verifikasi cepat tanpa perlu datang ke kantor desa.',
            ],
            [
                'tahap' => '2',
                'judul' => 'Pilih Jenis Surat & Isi Data',
                'deskripsi' => 'Pilih jenis surat yang dibutuhkan dari daftar layanan. Data identitas otomatis terisi sesuai profil akun kependudukan Anda.',
            ],
            [
                'tahap' => '3',
                'judul' => 'Unggah Berkas Persyaratan Digital',
                'deskripsi' => 'Foto atau lampirkan berkas yang dibutuhkan (KTP, KK, foto bukti pendukung) langsung dari kamera HP atau file PDF/JPG.',
            ],
            [
                'tahap' => '4',
                'judul' => 'Verifikasi & TTE Digital Kepala Desa',
                'deskripsi' => 'Admin desa memeriksa berkas secara digital. Kepala Desa menandatangani dengan Tanda Tangan Elektronik (TTE) resmi bersertifikasi QR Code.',
            ],
            [
                'tahap' => '5',
                'judul' => 'Notifikasi WhatsApp & Unduh PDF',
                'deskripsi' => 'Setiap pembaruan status dikabari via WhatsApp real-time. Surat resmi PDF dapat langsung diunduh dan disimpan atau dicetak.',
            ],
        ],
    ],

    'komparasi_layanan' => [
        [
            'aspek' => 'Tempat Pengajuan',
            'offline' => 'Wajib datang fisik ke loket Balai Desa',
            'online' => 'Fleksibel dari rumah via HP / Laptop',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Waktu Layanan',
            'offline' => 'Terbatas jam kerja kantor (08.00 – 14.30 WIB)',
            'online' => 'Bisa diajukan kapan saja 24/7 non-stop',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Pengisian Berkas',
            'offline' => 'Mengisi formulir kertas tulisan tangan & fotokopi berkas',
            'online' => 'Formulir digital otomatis & upload foto/PDF dari ponsel',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Pelacakan Status',
            'offline' => 'Harus datang atau telepon untuk menanyakan status surat',
            'online' => 'Notifikasi otomatis ke WhatsApp & fitur lacak resi online',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Penandatanganan',
            'offline' => 'Tanda tangan basah manual (menunggu kehadiran Kades)',
            'online' => 'Tanda Tangan Elektronik (TTE) ber-QR Code valid & cepat',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Pengambilan Surat',
            'offline' => 'Harus antre kembali di loket untuk mengambil kertas fisik',
            'online' => 'Langsung unduh file PDF asli ber-QR Code & cetak mandiri',
            'unggul' => 'online',
        ],
        [
            'aspek' => 'Biaya Pelayanan',
            'offline' => 'Gratis (Rp 0) — sesuai ketentuan desa',
            'online' => 'Gratis (Rp 0) — tanpa dipungut biaya apa pun',
            'unggul' => 'imbang',
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
