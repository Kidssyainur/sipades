import fs from 'fs';
import path from 'path';
import { execFileSync } from 'node:child_process';
import puppeteer from 'puppeteer';

// =====================================================================
// KONFIGURASI
// =====================================================================
const BASE_URL = process.env.BASE_URL || process.env.APP_URL || 'http://127.0.0.1:8000';
const SCREENSHOT_DIR = path.resolve(process.cwd(), 'screenshots');
const PROJECT_ROOT = process.cwd();

// Waktu penungguan rendering Livewire per halaman (default 2500ms)
const RENDER_DELAY_MS = parseInt(process.env.RENDER_DELAY_MS || '2500', 10);
// Waktu penungguan autentikasi login (default 2000ms)
const LOGIN_DELAY_MS = parseInt(process.env.LOGIN_DELAY_MS || '2000', 10);
// Tulis ulang data pengajuan sebelum run agar screenshot bersih (opsional)
const RESET_DATA = ['1', 'true', 'yes'].includes(String(process.env.RESET_DATA || '').toLowerCase());
// Batasi hanya satu jenis surat untuk uji coba, mis. ONLY_LETTER=DOMISILI
const ONLY_LETTER = String(process.env.ONLY_LETTER || '').toUpperCase();

const VIEWPORT = { width: 1440, height: 900, deviceScaleFactor: 1 };
const FULL_PAGE = false;

const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD || 'password';
const PHP_BINARY = process.env.PHP_BINARY || 'php';

const WARGA = {
    nik: process.env.WARGA_NIK || '3529010101800001',
    noHp: process.env.WARGA_NO_HP || '6285954154437',
};

const AKUN = {
    admin: 'admin@karduluk.desa.id',
    petugas: 'petugas@karduluk.desa.id',
    sekdes: 'sekretaris@karduluk.desa.id',
    kades: 'kepaladesa@karduluk.desa.id',
};

// Berkas contoh untuk unggahan lampiran (relatif dari root proyek)
const LAMPIRAN_CONTOH = [
    'public/assets/desa_karduluk.jpg',
    'public/assets/kepala_desa.jpg',
];

if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

// =====================================================================
// DEFINISI 7 JENIS SURAT (mengikuti JenisSuratSeeder)
// =====================================================================
const JENIS_SURAT = [
    {
        kode: 'DOMISILI',
        nama: 'Surat Keterangan Domisili',
        level: 2,
        tte: true,
        estimasi: 2,
        persyaratan: ['Fotokopi KTP', 'Fotokopi Kartu Keluarga'],
        fields: [
            { name: 'keperluan', type: 'text', value: 'Pengurusan administrasi perbankan dan keperluan umum' },
            { name: 'alamat_domisili', type: 'textarea', value: 'Dusun Tengah RT 01 RW 02, Desa Karduluk, Kecamatan Pragaan' },
        ],
    },
    {
        kode: 'USAHA',
        nama: 'Surat Keterangan Usaha',
        level: 3,
        tte: true,
        estimasi: 3,
        persyaratan: ['Fotokopi KTP', 'Fotokopi Kartu Keluarga', 'Foto lokasi usaha'],
        fields: [
            { name: 'nama_usaha', type: 'text', value: 'Toko Kelontong Berkah' },
            { name: 'jenis_usaha', type: 'text', value: 'Perdagangan Sembako' },
            { name: 'alamat_usaha', type: 'textarea', value: 'Jalan Raya Karduluk No. 12, Dusun Tengah' },
            { name: 'tahun_berdiri', type: 'number', value: '2019' },
        ],
    },
    {
        kode: 'SKTM',
        nama: 'Surat Keterangan Tidak Mampu',
        level: 2,
        tte: true,
        estimasi: 2,
        persyaratan: ['Fotokopi KTP', 'Fotokopi Kartu Keluarga', 'Surat pengantar RT/RW'],
        fields: [
            { name: 'keperluan', type: 'text', value: 'Pengajuan keringanan biaya pendidikan anak' },
            { name: 'penghasilan', type: 'number', value: '1500000' },
        ],
    },
    {
        kode: 'PENGANTAR_KTP',
        nama: 'Surat Pengantar KTP',
        level: 1,
        tte: false,
        estimasi: 1,
        persyaratan: ['Fotokopi Kartu Keluarga', 'Pas foto'],
        fields: [
            { name: 'jenis_permohonan', type: 'select', value: 'Perubahan Data' },
        ],
    },
    {
        kode: 'PENGANTAR_KK',
        nama: 'Surat Pengantar KK',
        level: 1,
        tte: false,
        estimasi: 1,
        persyaratan: ['Fotokopi KTP', 'Fotokopi KK lama (jika ada)'],
        fields: [
            { name: 'jenis_permohonan', type: 'select', value: 'Penambahan Anggota' },
        ],
    },
    {
        kode: 'KELAHIRAN',
        nama: 'Surat Keterangan Kelahiran',
        level: 3,
        tte: true,
        estimasi: 2,
        persyaratan: ['Fotokopi KTP orang tua', 'Fotokopi Kartu Keluarga', 'Surat keterangan lahir dari bidan/RS'],
        fields: [
            { name: 'nama_anak', type: 'text', value: 'Muhammad Rizky Pratama' },
            { name: 'tempat_lahir', type: 'text', value: 'Sumenep' },
            { name: 'tanggal_lahir', type: 'date', value: '2026-01-15' },
            { name: 'jenis_kelamin', type: 'select', value: 'Laki-laki' },
            { name: 'nama_ayah', type: 'text', value: 'Ahmad Fauzi' },
            { name: 'nama_ibu', type: 'text', value: 'Siti Aminah' },
        ],
    },
    {
        kode: 'KEMATIAN',
        nama: 'Surat Keterangan Kematian',
        level: 3,
        tte: true,
        estimasi: 2,
        persyaratan: ['Fotokopi KTP almarhum/ah', 'Fotokopi Kartu Keluarga', 'Surat keterangan kematian dari RS (jika ada)'],
        fields: [
            { name: 'nama_almarhum', type: 'text', value: 'Hasan Basri' },
            { name: 'tanggal_meninggal', type: 'date', value: '2026-02-03' },
            { name: 'tempat_meninggal', type: 'text', value: 'Desa Karduluk' },
            { name: 'sebab_meninggal', type: 'text', value: 'Usia lanjut' },
        ],
    },
];

// =====================================================================
// MANIFEST SCREENSHOT (dibaca oleh build_docx/build_alur_surat.py)
// =====================================================================
let counter = 0;
const manifest = {
    generated_at: new Date().toISOString(),
    base_url: BASE_URL,
    shared: [],
    letters: JENIS_SURAT.map((j) => ({
        kode: j.kode,
        nama: j.nama,
        level: j.level,
        tte: j.tte,
        estimasi: j.estimasi,
        persyaratan: j.persyaratan,
        screenshots: [],
    })),
};

function saveManifest() {
    fs.writeFileSync(path.join(SCREENSHOT_DIR, 'manifest.json'), JSON.stringify(manifest, null, 2));
}

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

// =====================================================================
// UTIL: PHP / ARTISAN (OTP, status, reset, fallback approval)
// =====================================================================
function phpExec(code) {
    const opts = { cwd: PROJECT_ROOT, encoding: 'utf8', timeout: 60000, stdio: ['ignore', 'pipe', 'pipe'] };

    try {
        return execFileSync(PHP_BINARY, ['artisan', 'tinker', '--execute=' + code], opts);
    } catch (e) {
        return execFileSync('docker', ['compose', 'exec', '-T', 'app', 'php', 'artisan', 'tinker', '--execute=' + code], opts);
    }
}

function phpValue(expression) {
    const out = phpExec(`echo "<<<" . (${expression}) . ">>>";`);
    const m = out.match(/<<<([\s\S]*?)>>>/);
    return m ? m[1].trim() : null;
}

async function waitForPhpValue(expression, expectedFn, timeoutMs = 30000, intervalMs = 1500) {
    const deadline = Date.now() + timeoutMs;
    let last = null;

    while (Date.now() < deadline) {
        try {
            last = phpValue(expression);
            if (expectedFn(last)) return last;
        } catch (e) {
            console.warn('   ⚠️ php check gagal:', e.message);
        }
        await sleep(intervalMs);
    }

    return last;
}

function getOtpLogin() {
    return waitForPhpValue(
        `App\\Models\\OtpCode::where('no_hp','${WARGA.noHp}')->where('tipe','login')->whereNull('digunakan_pada')->latest('id')->value('kode_otp')`,
        (v) => /^\d{6}$/.test(String(v)),
        15000,
        1000,
    );
}

function getStatusPengajuan(id) {
    return waitForPhpValue(
        `App\\Models\\PengajuanSurat::find(${id})?->status?->value`,
        (v) => v !== null && v !== '',
        20000,
        1200,
    );
}

function getTteToken(pengajuanId) {
    return phpValue(`App\\Models\\SuratTerbit::where('pengajuan_surat_id', ${pengajuanId})->value('tte_token')`);
}

function cliApprove(pengajuanId, email, catatan) {
    phpExec(
        `$p = App\\Models\\PengajuanSurat::find(${pengajuanId}); ` +
        `$u = App\\Models\\User::where('email', '${email}')->first(); ` +
        `app(App\\Services\\ApprovalService::class)->setujui($p, $u, ${JSON.stringify(catatan)}); ` +
        `echo '<<<approved>>>';`,
    );
}

function resetPengajuanData() {
    phpExec(
        "DB::statement('SET FOREIGN_KEY_CHECKS=0'); " +
        "foreach (['pengajuan_surat','approval_log','surat_terbit','notifikasi_log','media','otp_codes','jobs','failed_jobs','wa_messages','wa_contacts','wa_sessions'] as $t) { DB::table($t)->truncate(); } " +
        "DB::statement('SET FOREIGN_KEY_CHECKS=1'); echo '<<<reset-ok>>>';",
    );
}

// =====================================================================
// UTIL: SCREENSHOT & INTERAKSI HALAMAN
// =====================================================================
async function shot(page, bucket, { file, caption, explanation, role }) {
    counter += 1;
    const name = `${String(counter).padStart(3, '0')}_${file}.png`;
    await page.screenshot({ path: path.join(SCREENSHOT_DIR, name), fullPage: FULL_PAGE });
    bucket.push({ file: name, caption, explanation, role });
    console.log(`   📸 ${name} — ${caption}`);
    return name;
}

async function clickByText(page, selector, text) {
    const handles = await page.$$(selector);

    for (const h of handles) {
        const t = (await h.evaluate((el) => (el.innerText || '').trim())).replace(/\s+/g, ' ');
        if (t.includes(text)) {
            await h.click();
            return true;
        }
    }

    return false;
}

async function fillField(page, model, value, type = 'text') {
    const sel = `[wire\\:model="${model}"]`;
    await page.waitForSelector(sel, { visible: true, timeout: 15000 });

    if (type === 'select') {
        await page.select(sel, value);
    } else if (type === 'date') {
        await page.$eval(sel, (el, v) => {
            const setter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
            setter.call(el, v);
            el.dispatchEvent(new Event('input', { bubbles: true }));
            el.dispatchEvent(new Event('change', { bubbles: true }));
        }, value);
    } else {
        await page.click(sel, { clickCount: 3 });
        await page.type(sel, value, { delay: 5 });
    }
}

async function loginFilament(page, email, label) {
    await page.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle0', timeout: 45000 });
    await page.waitForSelector('input[type="email"]', { timeout: 15000 });
    await page.type('input[type="email"]', email);
    await page.type('input[type="password"]', ADMIN_PASSWORD);
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0', timeout: 30000 }).catch(() => {}),
        page.click('button[type="submit"]'),
    ]);
    await sleep(LOGIN_DELAY_MS);
    console.log(`   🔑 Login ${label}: ${page.url()}`);
}

async function loginWarga(page, sharedBucket) {
    await page.goto(`${BASE_URL}/portal/login`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(1500);

    await shot(page, sharedBucket, {
        file: 'UMUM_login_warga',
        caption: 'Halaman Login Portal Warga',
        explanation: 'Warga masuk ke portal menggunakan NIK (16 digit) dan nomor WhatsApp terdaftar. Sistem mengirimkan kode OTP ke WhatsApp sebagai verifikasi dua faktor.',
        role: 'warga',
    });

    await fillField(page, 'nik', WARGA.nik);
    await fillField(page, 'no_hp', WARGA.noHp);
    await clickByText(page, 'button[type="submit"]', 'Kirim OTP WhatsApp');
    await page.waitForFunction(() => location.pathname.includes('verifikasi-otp'), { timeout: 20000 }).catch(() => {});
    await sleep(LOGIN_DELAY_MS);

    const otp = await getOtpLogin();
    if (!otp) {
        throw new Error('Kode OTP tidak ditemukan di database. Pastikan PHP/artisan dapat dijalankan.');
    }

    await fillField(page, 'kode_otp', otp);

    await shot(page, sharedBucket, {
        file: 'UMUM_verifikasi_otp',
        caption: 'Verifikasi Kode OTP WhatsApp',
        explanation: 'Kode OTP 6 digit yang dikirim melalui WhatsApp Gateway dimasukkan untuk memverifikasi kepemilikan nomor dan mengaktifkan sesi login warga.',
        role: 'warga',
    });

    await clickByText(page, 'button[type="submit"]', 'Verifikasi Sekarang');
    await page.waitForFunction(() => location.pathname.includes('portal/dashboard'), { timeout: 25000 }).catch(() => {});
    await sleep(RENDER_DELAY_MS);

    await shot(page, sharedBucket, {
        file: 'UMUM_dashboard_warga',
        caption: 'Dashboard Portal Warga',
        explanation: 'Setelah login, warga melihat ringkasan statistik pengajuan (total, dalam proses, disetujui, ditolak), pintasan layanan, dan riwayat pengajuan terbaru.',
        role: 'warga',
    });
}

// =====================================================================
// ALUR: WARGA MENGAJUKAN SURAT
// =====================================================================
async function wargaBuatPengajuan(page, letter, bucket) {
    console.log(`\n📝 [${letter.kode}] Warga mengajukan ${letter.nama}...`);

    await page.goto(`${BASE_URL}/portal/pengajuan/buat`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    // Klik kartu jenis surat berdasarkan judulnya
    const cardClicked = await page.evaluate((nama) => {
        const h3 = [...document.querySelectorAll('h3')].find((el) => el.innerText.trim() === nama);
        const btn = h3?.closest('button');
        if (btn) { btn.click(); return true; }
        return false;
    }, letter.nama);

    if (!cardClicked) throw new Error(`Kartu jenis surat "${letter.nama}" tidak ditemukan.`);
    await sleep(1800);

    // Isi seluruh field formulir dinamis
    for (const f of letter.fields) {
        await fillField(page, `formulir.${f.name}`, f.value, f.type);
    }

    // Unggah berkas lampiran contoh
    const fileInput = await page.$('input[type="file"]');
    if (fileInput) {
        await fileInput.uploadFile(...LAMPIRAN_CONTOH.map((f) => path.resolve(PROJECT_ROOT, f)));
        await page.waitForFunction(
            () => document.body.innerText.includes('desa_karduluk') || document.body.innerText.includes('kepala_desa'),
            { timeout: 30000 },
        ).catch(() => {});
        await sleep(1500);
    }

    await shot(page, bucket, {
        file: `${letter.kode}_01_formulir`,
        caption: `Formulir Pengajuan ${letter.nama} (Terisi)`,
        explanation: `Warga mengisi formulir permohonan ${letter.nama} secara digital. Sistem menampilkan kolom isian sesuai konfigurasi jenis surat, daftar dokumen persyaratan (${letter.persyaratan.join(', ')}), serta area unggah lampiran pendukung berformat PDF/JPG/PNG maksimal 2 MB per berkas.`,
        role: 'warga',
    });

    // Lanjut ke pratinjau
    await clickByText(page, 'button[type="submit"]', 'Pratinjau Data');
    await page.waitForFunction(() => document.body.innerText.includes('Pratinjau Permohonan Surat'), { timeout: 20000 });
    await sleep(1200);

    await shot(page, bucket, {
        file: `${letter.kode}_02_pratinjau`,
        caption: `Pratinjau & Konfirmasi Permohonan ${letter.nama}`,
        explanation: 'Sebelum dikirim, warga meninjau ringkasan seluruh data formulir beserta jumlah lampiran yang berhasil diunggah untuk memastikan tidak ada data yang keliru.',
        role: 'warga',
    });

    // Kirim pengajuan
    await clickByText(page, 'button', 'Kirim Pengajuan Sekarang');
    await page.waitForFunction(() => /\/portal\/pengajuan\/\d+\/status/.test(location.pathname), { timeout: 25000 });
    const match = page.url().match(/\/portal\/pengajuan\/(\d+)\/status/);
    const pengajuanId = match ? parseInt(match[1], 10) : null;

    if (!pengajuanId) throw new Error('Gagal membaca ID pengajuan dari URL status.');

    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_03_status_awal`,
        caption: `Status Awal Pengajuan ${letter.nama} (Menunggu Verifikasi Petugas)`,
        explanation: 'Setelah dikirim, pengajuan memperoleh nomor referensi unik dan berstatus "Diajukan". Halaman pelacakan menampilkan timeline approval yang dimulai dari antrian verifikasi Petugas Desa (Level 1).',
        role: 'warga',
    });

    console.log(`   ✅ Pengajuan #${pengajuanId} dibuat.`);
    return pengajuanId;
}

// =====================================================================
// ALUR: APPROVAL OLEH STAF (Petugas / Sekdes / Kades)
// =====================================================================
async function approvalOlehStaf(page, letter, bucket, opts) {
    const { role, roleLabel, roleEmail, listPath, catatan, expectedStatus, isFinal } = opts;

    console.log(`   🔏 [${letter.kode}] ${roleLabel} memproses approval...`);

    // 1. Antrian approval
    await page.goto(`${BASE_URL}${listPath}`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_04_${role}_antrian`,
        caption: `Antrian ${roleLabel} — ${letter.nama}`,
        explanation: `Permohonan ${letter.nama} tampil pada antrian ${roleLabel}. Tabel menampilkan identitas pemohon, jenis surat, status terkini, dan tombol Keputusan yang berisi opsi Setujui, Minta Revisi, atau Tolak.`,
        role,
    });

    // 2. Detail pengajuan
    await page.goto(`${BASE_URL}${listPath}/${opts.pengajuanId}`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_05_${role}_detail`,
        caption: `Detail Permohonan pada ${roleLabel} — ${letter.nama}`,
        explanation: `${roleLabel} memeriksa rincian permohonan: ringkasan status, data pemohon, isian formulir, berkas lampiran persyaratan, dan riwayat approval sebelumnya sebelum mengambil keputusan.`,
        role,
    });

    // 3. Buka modal keputusan "Setujui"
    await page.goto(`${BASE_URL}${listPath}`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    const groupClicked = await page.evaluate(() => {
        const tr = document.querySelector('table tbody tr');
        const btn = tr && [...tr.querySelectorAll('button')].find((b) => b.innerText.includes('Keputusan'));
        if (btn) { btn.click(); return true; }
        return false;
    });
    if (!groupClicked) throw new Error('Tombol Keputusan tidak ditemukan pada baris tabel.');
    await sleep(900);

    const itemClicked = await page.evaluate(() => {
        const tr = document.querySelector('table tbody tr');
        const btn = tr && [...tr.querySelectorAll('button.fi-ac-grouped-action')].find((b) => b.innerText.trim() === 'Setujui');
        if (btn) { btn.click(); return true; }
        return false;
    });
    if (!itemClicked) throw new Error('Opsi Setujui tidak ditemukan pada menu Keputusan.');

    await page.waitForSelector('.fi-modal-open textarea', { timeout: 15000 });
    await page.type('.fi-modal-open textarea', catatan, { delay: 3 });
    await sleep(400);

    await shot(page, bucket, {
        file: `${letter.kode}_06_${role}_modal`,
        caption: `Modal Keputusan Setujui — ${roleLabel}`,
        explanation: `${roleLabel} memberikan keputusan melalui modal persetujuan beserta catatan. ${isFinal && letter.tte ? 'Persetujuan pada level ini sekaligus menandatangani dokumen secara elektronik (TTE) dan menerbitkan surat resmi.' : 'Setelah disetujui, permohonan otomatis diteruskan ke tahap approval berikutnya.'}`,
        role,
    });

    // 4. Kirim keputusan
    await page.evaluate(() => {
        const btn = [...document.querySelectorAll('.fi-modal-open button[type="submit"]')].find((b) => (b.innerText || '').includes('Kirim'));
        if (btn) btn.click();
    });

    const changed = await waitForPhpValue(
        `App\\Models\\PengajuanSurat::find(${opts.pengajuanId})?->status?->value`,
        (v) => v === expectedStatus,
        45000,
        1500,
    );

    if (changed !== expectedStatus) {
        console.warn(`   ⚠️ Approval via UI tidak terdeteksi (status: ${changed}). Fallback ke ApprovalService...`);

        try {
            cliApprove(opts.pengajuanId, roleEmail, catatan);
        } catch (e) {
            console.warn(`   ⚠️ Fallback CLI gagal (mungkin sudah ter-approve): ${e.message.split('\n')[0]}`);
        }

        await sleep(2500);
        await page.reload({ waitUntil: 'networkidle0' });
    }

    await sleep(900);

    await shot(page, bucket, {
        file: `${letter.kode}_07_${role}_hasil`,
        caption: `Hasil Keputusan ${roleLabel} — ${letter.nama}`,
        explanation: `${isFinal ? 'Keputusan final tersimpan, surat resmi diterbitkan, dan status pengajuan berubah menjadi Selesai (Surat Terbit). Notifikasi WhatsApp dikirim ke warga.' : 'Keputusan tersimpan dan notifikasi keberhasilan muncul. Status pengajuan berpindah ke tahap berikutnya sesuai konfigurasi level approval jenis surat ini.'}`,
        role,
    });
}

// =====================================================================
// ALUR: STATUS WARGA & ARSIP SURAT TERBIT
// =====================================================================
async function wargaStatus(page, letter, bucket, pengajuanId, afterLevel) {
    await page.goto(`${BASE_URL}/portal/pengajuan/${pengajuanId}/status`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_0${afterLevel === 'selesai' ? 8 : 5}_status_${afterLevel}`,
        caption: `Pelacakan Status setelah ${afterLevel === 'selesai' ? 'Persetujuan Final' : `Level ${afterLevel - 1}`} — ${letter.nama}`,
        explanation: afterLevel === 'selesai'
            ? 'Seluruh tahap approval selesai. Portal warga menampilkan banner "Surat Telah Terbit & TTE Terverifikasi" beserta tautan unduh PDF resmi melalui signed URL, serta riwayat catatan persetujuan dari setiap level.'
            : `Timeline pada portal warga diperbarui secara real-time: tahap level sebelumnya selesai dan pengajuan kini menunggu keputusan pada level berikutnya (Level ${afterLevel}).`,
        role: 'warga',
    });
}

async function arsipSuratTerbit(page, letter, bucket, pengajuanId) {
    await page.goto(`${BASE_URL}/portal/surat-terbit`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_09_surat_terbit`,
        caption: `Arsip Surat Terbit — ${letter.nama}`,
        explanation: `Surat ${letter.nama} yang telah disetujui penuh muncul pada arsip "Surat Terbit" milik warga, lengkap dengan nomor surat resmi${letter.tte ? ' dan token verifikasi TTE' : ''}. Dokumen PDF dapat diunduh kapan saja melalui tautan aman (signed URL).`,
        role: 'warga',
    });

    const token = getTteToken(pengajuanId);
    if (!token) return;

    await page.goto(`${BASE_URL}/verifikasi-surat/${token}`, { waitUntil: 'networkidle0', timeout: 45000 });
    await sleep(RENDER_DELAY_MS);

    await shot(page, bucket, {
        file: `${letter.kode}_10_verifikasi_tte`,
        caption: `Verifikasi Keabsahan TTE Publik — ${letter.nama}`,
        explanation: 'QR Code pada dokumen surat mengarahkan pihak penerima surat ke halaman verifikasi publik ini. Halaman membuktikan keaslian dokumen: nomor surat, tanggal terbit, identitas pemohon, serta pengesahan pejabat desa secara elektronik. ' +
            (letter.level === 3
                ? 'Dokumen ditandatangani secara elektronik oleh Kepala Desa Karduluk selaku approver pada level akhir (Level 3).'
                : letter.level === 2
                    ? 'Dokumen disahkan secara elektronik oleh Sekretaris Desa Karduluk selaku approver pada level akhir (Level 2).'
                    : 'Karena hanya melalui satu tingkat persetujuan (Level 1), pengesahan dilakukan oleh Petugas Desa atas nama Kepala Desa Karduluk.'),
        role: 'publik',
    });
}

// =====================================================================
// MAIN: ORKESTRASI SELURUH ALUR
// =====================================================================
async function main() {
    console.log('🚀 SIPADES — Generator Screenshot Alur Pelayanan Surat');
    console.log(`🌐 Base URL: ${BASE_URL}`);
    console.log(`⏱️  Delay render: ${RENDER_DELAY_MS / 1000}s per halaman`);

    if (RESET_DATA) {
        console.log('🧹 RESET_DATA=true → membersihkan data pengajuan & screenshot lama...');
        resetPengajuanData();

        for (const f of fs.readdirSync(SCREENSHOT_DIR)) {
            if (/^\d{3}_.*\.png$/.test(f)) fs.unlinkSync(path.join(SCREENSHOT_DIR, f));
        }
        const manifestPath = path.join(SCREENSHOT_DIR, 'manifest.json');
        if (fs.existsSync(manifestPath)) fs.unlinkSync(manifestPath);
    }

    const execPath = process.env.PUPPETEER_EXECUTABLE_PATH || getWindowsExecutablePath();
    const launchOptions = {
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1440,900'],
    };
    if (execPath) {
        console.log(`📌 Browser Executable: ${execPath}`);
        launchOptions.executablePath = execPath;
    }

    const browser = await puppeteer.launch(launchOptions);

    try {
        const sharedBucket = manifest.shared;

        // Context warga
        const wargaContext = await browser.createBrowserContext();
        const wargaPage = await wargaContext.newPage();
        await wargaPage.setViewport(VIEWPORT);

        // Context staf desa
        const petugasContext = await browser.createBrowserContext();
        const petugasPage = await petugasContext.newPage();
        await petugasPage.setViewport(VIEWPORT);

        const sekdesContext = await browser.createBrowserContext();
        const sekdesPage = await sekdesContext.newPage();
        await sekdesPage.setViewport(VIEWPORT);

        const kadesContext = await browser.createBrowserContext();
        const kadesPage = await kadesContext.newPage();
        await kadesPage.setViewport(VIEWPORT);

        // 1. Halaman publik & autentikasi
        console.log('\n📖 1. Menyiapkan halaman umum & autentikasi...');

        await wargaPage.goto(`${BASE_URL}/`, { waitUntil: 'networkidle0', timeout: 45000 });
        await sleep(RENDER_DELAY_MS);
        await shot(wargaPage, sharedBucket, {
            file: 'UMUM_landing_publik',
            caption: 'Halaman Utama (Landing Page) SIPADES Desa Karduluk',
            explanation: 'Halaman publik sebagai pintu masuk layanan digital Desa Karduluk. Warga dapat menuju portal persuratan, melihat layanan unggulan, serta memverifikasi keabsahan surat melalui menu yang tersedia.',
            role: 'publik',
        });

        await loginWarga(wargaPage, sharedBucket);

        await loginFilament(petugasPage, AKUN.petugas, 'Petugas Desa');
        await loginFilament(sekdesPage, AKUN.sekdes, 'Sekretaris Desa');
        await loginFilament(kadesPage, AKUN.kades, 'Kepala Desa');

        await wargaPage.goto(`${BASE_URL}/portal/pengajuan/buat`, { waitUntil: 'networkidle0', timeout: 45000 });
        await sleep(RENDER_DELAY_MS);
        await shot(wargaPage, sharedBucket, {
            file: 'UMUM_katalog_surat',
            caption: 'Katalog 7 Jenis Surat Pelayanan Desa',
            explanation: 'Langkah pertama pengajuan: warga memilih salah satu dari tujuh jenis surat yang dilayani. Setiap kartu menampilkan estimasi durasi penyelesaian dan jumlah dokumen persyaratan.',
            role: 'warga',
        });
        saveManifest();

        // 2. Alur per jenis surat
        const daftar = ONLY_LETTER
            ? JENIS_SURAT.filter((j) => j.kode === ONLY_LETTER)
            : JENIS_SURAT;

        console.log(`\n🗂️  2. Memproses ${daftar.length} jenis surat...`);

        for (const letter of daftar) {
            const bucket = manifest.letters.find((l) => l.kode === letter.kode).screenshots;
            const label = `${letter.nama} (${letter.level} level approval${letter.tte ? ' + TTE' : ''})`;

            console.log(`\n================================================================`);
            console.log(`🗂️  ALUR ${label}`);
            console.log(`================================================================`);

            try {
                const pengajuanId = await wargaBuatPengajuan(wargaPage, letter, bucket);

                // Level 1 — Petugas Desa
                const level1Final = letter.level === 1;
                await approvalOlehStaf(petugasPage, letter, bucket, {
                    role: 'petugas',
                    roleLabel: 'Verifikasi Petugas Desa (Level 1)',
                    roleEmail: AKUN.petugas,
                    listPath: '/admin/verifikasi-petugas',
                    pengajuanId,
                    isFinal: level1Final,
                    expectedStatus: level1Final ? 'selesai' : 'diverifikasi_petugas',
                    catatan: level1Final
                        ? 'Berkas persyaratan lengkap dan valid. Permohonan disetujui dan surat diterbitkan.'
                        : 'Berkas persyaratan lengkap dan data pemohon sesuai. Diverifikasi dan diteruskan ke Sekretaris Desa.',
                });

                if (letter.level >= 2) {
                    await wargaStatus(wargaPage, letter, bucket, pengajuanId, 2);
                }

                // Level 2 — Sekretaris Desa
                if (letter.level >= 2) {
                    const level2Final = letter.level === 2;
                    await approvalOlehStaf(sekdesPage, letter, bucket, {
                        role: 'sekdes',
                        roleLabel: 'Persetujuan Sekretaris Desa (Level 2)',
                        roleEmail: AKUN.sekdes,
                        listPath: '/admin/persetujuan-sekretaris',
                        pengajuanId,
                        isFinal: level2Final,
                        expectedStatus: level2Final ? 'selesai' : 'disetujui_sekretaris',
                        catatan: level2Final
                            ? 'Data administrasi telah sesuai dengan berkas kependudukan. Permohonan disetujui dan surat diterbitkan.'
                            : 'Data administrasi telah sesuai dengan berkas kependudukan. Disetujui untuk diteruskan kepada Kepala Desa.',
                    });
                }

                if (letter.level >= 3) {
                    await wargaStatus(wargaPage, letter, bucket, pengajuanId, 3);

                    // Level 3 — Kepala Desa + TTE
                    await approvalOlehStaf(kadesPage, letter, bucket, {
                        role: 'kades',
                        roleLabel: 'Persetujuan Kepala Desa (Level 3 & TTE)',
                        roleEmail: AKUN.kades,
                        listPath: '/admin/persetujuan-kepalas',
                        pengajuanId,
                        isFinal: true,
                        expectedStatus: 'selesai',
                        catatan: 'Permohonan disetujui. Dokumen ditandatangani dan diterbitkan secara elektronik (TTE).',
                    });
                }

                // Penutup alur: status selesai + arsip + verifikasi TTE
                await wargaStatus(wargaPage, letter, bucket, pengajuanId, 'selesai');
                await arsipSuratTerbit(wargaPage, letter, bucket, pengajuanId);

                saveManifest();
                console.log(`✅ Alur ${letter.kode} selesai.`);
            } catch (e) {
                console.error(`❌ Gagal memproses alur ${letter.kode}: ${e.message}`);
                saveManifest();
            }
        }
    } finally {
        await browser.close();
        saveManifest();
    }

    const total = manifest.shared.length + manifest.letters.reduce((n, l) => n + l.screenshots.length, 0);
    console.log(`\n🎉 Selesai! ${total} screenshot tersimpan di: ${SCREENSHOT_DIR}`);
    console.log(`🧾 Manifest: ${path.join(SCREENSHOT_DIR, 'manifest.json')}`);
}

function getWindowsExecutablePath() {
    const possiblePaths = [
        path.join(process.env.USERPROFILE || '', '.cache', 'puppeteer', 'chrome', 'win64-151.0.7922.47', 'chrome-win64', 'chrome.exe'),
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    ];

    for (const p of possiblePaths) {
        if (p && fs.existsSync(p)) return p;
    }

    return null;
}

main().catch((e) => {
    console.error(e);
    process.exit(1);
});
