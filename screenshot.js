import fs from 'fs';
import path from 'path';
import puppeteer from 'puppeteer';

const BASE_URL = process.env.APP_URL || 'http://127.0.0.1:8000';
const SCREENSHOT_DIR = path.resolve(process.cwd(), 'screenshots');

if (!fs.existsSync(SCREENSHOT_DIR)) {
    fs.mkdirSync(SCREENSHOT_DIR, { recursive: true });
}

function getExecutablePath() {
    const possiblePaths = [
        'C:\\Users\\g0str\\.cache\\puppeteer\\chrome\\win64-151.0.7922.47\\chrome-win64\\chrome.exe',
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe'
    ];

    for (const p of possiblePaths) {
        if (fs.existsSync(p)) {
            return p;
        }
    }
    return null;
}

async function takeScreenshots() {
    console.log('🚀 Starting SIPADES complete screenshot generator...');

    const execPath = getExecutablePath();
    const launchOptions = {
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1440,900']
    };

    if (execPath) {
        console.log(`📌 Using browser executable: ${execPath}`);
        launchOptions.executablePath = execPath;
    }

    const browser = await puppeteer.launch(launchOptions);

    // ==========================================
    // 1. PUBLIC PAGES (TANPA LOGIN)
    // ==========================================
    const publicPage = await browser.newPage();
    await publicPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    const publicPages = [
        { name: '01_portal_login.png', path: '/portal/login' },
        { name: '02_registrasi.png', path: '/registrasi' },
        { name: '03_verifikasi_otp.png', path: '/verifikasi-otp' },
        { name: '04_admin_login.png', path: '/admin/login' },
    ];

    for (const p of publicPages) {
        try {
            console.log(`📸 Capturing Public: ${p.name} (${p.path})`);
            await publicPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle2' });
            await publicPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    // ==========================================
    // 2. PORTAL WARGA (LOGIN AS WARGA)
    // ==========================================
    console.log('\n🔑 Logging into Portal Warga...');
    const wargaContext = await browser.createBrowserContext();
    const wargaPage = await wargaContext.newPage();
    await wargaPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    try {
        await wargaPage.goto(`${BASE_URL}/portal/login`, { waitUntil: 'networkidle2' });
        
        await wargaPage.waitForSelector('input', { timeout: 5000 });
        const inputs = await wargaPage.$$('input');
        if (inputs.length >= 2) {
            await inputs[0].type('warga@karduluk.desa.id');
            await inputs[1].type('password');
            
            const submitBtn = await wargaPage.$('button[type="submit"]');
            if (submitBtn) {
                await Promise.all([
                    submitBtn.click(),
                    wargaPage.waitForNavigation({ waitUntil: 'networkidle2' }).catch(() => {})
                ]);
            }
        }
        console.log('✅ Logged in to Portal Warga!');
    } catch (e) {
        console.warn('⚠️ Portal Warga Login Issue:', e.message);
    }

    const portalPages = [
        { name: '05_portal_dashboard.png', path: '/portal/dashboard' },
        { name: '06_portal_ajukan_surat_buat.png', path: '/portal/pengajuan/buat' },
        { name: '07_portal_pengajuan_index.png', path: '/portal/pengajuan' },
        { name: '08_portal_pengajuan_status.png', path: '/portal/pengajuan/1/status' },
        { name: '09_portal_surat_terbit_index.png', path: '/portal/surat-terbit' },
        { name: '10_portal_profil_saya.png', path: '/portal/profil' },
    ];

    for (const p of portalPages) {
        try {
            console.log(`📸 Capturing Portal Warga: ${p.name} (${p.path})`);
            await wargaPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle2' });
            await wargaPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    // ==========================================
    // 3. FILAMENT ADMIN PANEL (LOGIN AS ADMIN)
    // ==========================================
    console.log('\n🔑 Logging into Filament Admin Panel...');
    const adminContext = await browser.createBrowserContext();
    const adminPage = await adminContext.newPage();
    await adminPage.setViewport({ width: 1440, height: 900, deviceScaleFactor: 1 });

    try {
        await adminPage.goto(`${BASE_URL}/admin/login`, { waitUntil: 'networkidle2' });
        
        await adminPage.waitForSelector('input', { timeout: 5000 });
        const inputs = await adminPage.$$('input');
        if (inputs.length >= 2) {
            await inputs[0].type('admin@karduluk.desa.id');
            await inputs[1].type('password');

            const submitBtn = await adminPage.$('button[type="submit"]');
            if (submitBtn) {
                await Promise.all([
                    submitBtn.click(),
                    adminPage.waitForNavigation({ waitUntil: 'networkidle2' }).catch(() => {})
                ]);
            }
        }
        console.log('✅ Logged in to Admin Panel!');
    } catch (e) {
        console.warn('⚠️ Admin Login Issue:', e.message);
    }

    const adminPages = [
        { name: '11_admin_dashboard.png', path: '/admin' },
        { name: '12_admin_data_kependudukan_index.png', path: '/admin/data-kependudukans' },
        { name: '13_admin_data_kependudukan_create.png', path: '/admin/data-kependudukans/create' },
        { name: '14_admin_jenis_surat_index.png', path: '/admin/jenis-surats' },
        { name: '15_admin_jenis_surat_create.png', path: '/admin/jenis-surats/create' },
        { name: '16_admin_jenis_surat_edit.png', path: '/admin/jenis-surats/1/edit' },
        { name: '17_admin_pengajuan_surat_index.png', path: '/admin/pengajuan-surats' },
        { name: '18_admin_pengajuan_surat_create.png', path: '/admin/pengajuan-surats/create' },
        { name: '19_admin_pengajuan_surat_detail.png', path: '/admin/pengajuan-surats/1' },
        { name: '20_admin_surat_terbit_index.png', path: '/admin/surat-terbits' },
        { name: '21_admin_template_pesan_index.png', path: '/admin/template-pesans' },
        { name: '22_admin_template_pesan_create.png', path: '/admin/template-pesans/create' },
        { name: '23_admin_notifikasi_log_index.png', path: '/admin/notifikasi-logs' },
        { name: '24_admin_whatsapp_settings.png', path: '/admin/whatsapp-gateway-settings' },
        { name: '25_admin_laporan.png', path: '/admin/laporan' },
        { name: '26_admin_users_index.png', path: '/admin/users' },
        { name: '27_admin_users_create.png', path: '/admin/users/create' },
        { name: '28_admin_activity_logs.png', path: '/admin/activity-logs' },
        { name: '29_admin_shield_roles.png', path: '/admin/shield/roles' },
    ];

    for (const p of adminPages) {
        try {
            console.log(`📸 Capturing Admin: ${p.name} (${p.path})`);
            await adminPage.goto(`${BASE_URL}${p.path}`, { waitUntil: 'networkidle2' });
            await adminPage.screenshot({ path: path.join(SCREENSHOT_DIR, p.name), fullPage: true });
        } catch (e) {
            console.warn(`⚠️ Skipped ${p.name}: ${e.message}`);
        }
    }

    await browser.close();
    console.log(`\n🎉 All 29 SIPADES screenshots captured & saved to: ${SCREENSHOT_DIR}`);
}

takeScreenshots().catch(console.error);
