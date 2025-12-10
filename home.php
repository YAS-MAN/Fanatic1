<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = 'Home - F1 Fanatic';
$currentPage = 'home';

// --- 1. FUNGSI FETCH DATA (METODE GANDA: CURL + FILE_GET_CONTENTS) ---
function fetchData($url, $headers = []) {
    // 1. Coba pakai CURL dulu (Biasanya lebih cepat)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Timeout cepat 5 detik
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Paksa IPv4
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    }
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($data && $httpCode == 200) return $data;

    // 2. Jika CURL Gagal, Coba pakai file_get_contents (Fallback)
    try {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => !empty($headers) ? implode("\r\n", $headers) : "User-Agent: Mozilla/5.0\r\n"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ]
        ];
        $context = stream_context_create($opts);
        $fileData = @file_get_contents($url, false, $context);
        if ($fileData) return $fileData;
    } catch (Exception $e) { /* Ignore */ }

    return null; // Gagal total
}

// --- 2. GET NEWS (SUMBER: MOTORSPORT.COM) ---
function getF1News() {
    $url = "https://www.motorsport.com/rss/f1/news/";
    // Tidak butuh headers khusus untuk RSS ini
    $raw = fetchData($url);
    $news = [];
    
    if ($raw) {
        $rss = @simplexml_load_string($raw);
        if ($rss) {
            $i = 0;
            foreach ($rss->channel->item as $item) {
                if ($i >= 5) break;
                
                $img = 'assets/default_news.jpg';
                if (isset($item->enclosure)) {
                    $img = (string)$item->enclosure['url'];
                } elseif ($item->children('media', true)) {
                    $media = $item->children('media', true)->content;
                    if ($media) {
                        $attr = $media->attributes();
                        $img = (string)$attr['url'];
                    }
                }

                $news[] = [
                    'title' => (string)$item->title,
                    'link'  => (string)$item->link,
                    'date'  => date('d M H:i', strtotime((string)$item->pubDate)),
                    'desc'  => strip_tags((string)$item->description),
                    'image' => $img,
                    'category' => 'MOTORSPORT'
                ];
                $i++;
            }
        }
    }
    return $news;
}

// --- 3. GET NEXT RACE (JOLPICA) ---
function getNextRace() {
    $json = fetchData("http://api.jolpi.ca/ergast/f1/current/next.json");
    $data = $json ? json_decode($json, true) : null;
    
    if ($data && !empty($data['MRData']['RaceTable']['Races'])) {
        $r = $data['MRData']['RaceTable']['Races'][0];
        // Konversi Waktu
        try {
            $dt = new DateTime($r['date'] . ' ' . ($r['time'] ?? '00:00:00Z'), new DateTimeZone('UTC'));
            $dt->setTimezone(new DateTimeZone('Asia/Jakarta'));
            $timeWIB = $dt->format('d M - H:i') . ' WIB';
        } catch (Exception $e) { $timeWIB = $r['date']; }

        return [
            'status' => 'LIVE',
            'round' => $r['round'],
            'name' => $r['raceName'],
            'country' => strtoupper($r['Circuit']['Location']['country']),
            'time' => $timeWIB,
            'flag' => getFlagEmoji($r['Circuit']['Location']['country'])
        ];
    }
    
    return ['status'=>'OFFLINE', 'round'=>'24', 'name'=>'ABU DHABI GRAND PRIX', 'country'=>'UAE', 'time'=>'08 Dec 2024', 'flag'=>'🇦🇪'];
}

// --- 4. MAPPING GAMBAR DRIVER (SESUAI FILE YANG ANDA UPLOAD) ---
function getDriverImage($name) {
    // Pencocokan nama dari API dengan Nama File di folder assets
    
    if (strpos($name, 'Verstappen') !== false) return './assets/driver/RB Driver 1 Max Verstappen.jpg';
    if (strpos($name, 'Perez') !== false) return './assets/driver/RB Driver 2 Sergio Perez.jpg'; // Asumsi jika ada
    if (strpos($name, 'Norris') !== false) return './assets/driver/MCL Driver 1 Lando Norris.jpg';
    if (strpos($name, 'Piastri') !== false) return './assets/driver/MCL Driver 2 Oscar Piastri.jpg';
    if (strpos($name, 'Leclerc') !== false) return './assets/driver/FS Driver 1 Charles LecLerc.jpg';
    if (strpos($name, 'Hamilton') !== false) return './assets/driver/SF Driver 2 Lewis Hamilton.jpg';
    if (strpos($name, 'Russell') !== false) return './assets/driver/W Driver 1 George Russell.jpg';
    if (strpos($name, 'Alonso') !== false) return './assets/driver/AMR Driver 1 Fernando Alonso.jpg';
    if (strpos($name, 'Stroll') !== false) return './assets/driver/AMR Driver 2 Lance Stroll.jpg';
    if (strpos($name, 'Tsunoda') !== false) return './assets/driver/RB Driver 2 Yuki Tsunoda.jpg';
    if (strpos($name, 'Antonelli') !== false) return './assets/driver/W Driver 2 Andrea Kimi Antonelli.jpg';

    // Gambar Default jika file belum diupload (misal Sainz belum ada di list upload)
    return './assets/default_driver.png';
}

// --- 5. GET DRIVER STANDINGS ---
function getDriverStandings() {
    $json = fetchData("http://api.jolpi.ca/ergast/f1/current/driverStandings.json");
    $data = $json ? json_decode($json, true) : null;
    
    $drivers = [];
    $status = 'OFFLINE';

    // A. JIKA ONLINE
    if ($data && !empty($data['MRData']['StandingsTable']['StandingsLists'])) {
        $list = $data['MRData']['StandingsTable']['StandingsLists'][0]['DriverStandings'];
        $status = 'LIVE';
        
        for($i=0; $i<6; $i++) {
            if(!isset($list[$i])) break;
            $d = $list[$i];
            
            $teamName = $d['Constructors'][0]['name'] ?? 'F1';
            $fullName = $d['Driver']['givenName'].' '.$d['Driver']['familyName'];
            
            // Warna Tim
            $color = '#333';
            if(stripos($teamName, 'Red Bull')!==false) $color = '#0600ef';
            elseif(stripos($teamName, 'Ferrari')!==false) $color = '#dc0000';
            elseif(stripos($teamName, 'Mercedes')!==false) $color = '#00d2be';
            elseif(stripos($teamName, 'McLaren')!==false) $color = '#ff8000';
            elseif(stripos($teamName, 'Aston')!==false) $color = '#006f62';

            $drivers[$i+1] = [
                'name' => $fullName,
                'team' => $teamName,
                'pts' => $d['points'],
                'flag' => getFlagEmoji($d['Driver']['nationality']),
                'color' => $color,
                'img' => getDriverImage($fullName) // Panggil mapping gambar baru
            ];
        }
    } 
    
    // B. JIKA OFFLINE (FALLBACK MANUAL)
    if (empty($drivers)) {
        $drivers = [
            1 => ['name'=>'Max Verstappen', 'team'=>'Red Bull Racing', 'pts'=>437, 'flag'=>'🇳🇱', 'color'=>'#0600ef', 'img'=>getDriverImage('Max Verstappen')],
            2 => ['name'=>'Lando Norris', 'team'=>'McLaren', 'pts'=>374, 'flag'=>'🇬🇧', 'color'=>'#ff8000', 'img'=>getDriverImage('Lando Norris')],
            3 => ['name'=>'Charles Leclerc', 'team'=>'Ferrari', 'pts'=>356, 'flag'=>'🇲🇨', 'color'=>'#dc0000', 'img'=>getDriverImage('Charles Leclerc')],
            4 => ['name'=>'Oscar Piastri', 'team'=>'McLaren', 'pts'=>292, 'flag'=>'🇦🇺', 'color'=>'#ff8000', 'img'=>getDriverImage('Oscar Piastri')],
            5 => ['name'=>'Carlos Sainz', 'team'=>'Ferrari', 'pts'=>290, 'flag'=>'🇪🇸', 'color'=>'#dc0000', 'img'=>'assets/default_driver.png'], // Sainz belum ada fotonya di upload an
            6 => ['name'=>'George Russell', 'team'=>'Mercedes', 'pts'=>245, 'flag'=>'🇬🇧', 'color'=>'#00d2be', 'img'=>getDriverImage('George Russell')]
        ];
    }
    
    return ['status' => $status, 'data' => $drivers];
}

function getFlagEmoji($nat) {
    $map = ['Max Verstappen'=>'🇳🇱','Dutch'=>'🇳🇱','British'=>'🇬🇧','UK'=>'🇬🇧','Australian'=>'🇦🇺','Monegasque'=>'🇲🇨','Spanish'=>'🇪🇸','Mexican'=>'🇲🇽','German'=>'🇩🇪','USA'=>'🇺🇸','Italian'=>'🇮🇹','UAE'=>'🇦🇪','French'=>'🇫🇷'];
    return $map[$nat] ?? '🏁';
}

// --- EXECUTE ---
$newsData = getF1News();
$nextRace = getNextRace();
$lbData   = getDriverStandings(); 
$drivers  = $lbData['data'];

// Fallback News
if (empty($newsData)) {
    // Jika gagal ambil berita, kosongkan saja agar tidak muncul dummy
    $newsData = []; 
}

$heroNews = !empty($newsData) ? $newsData[0] : null;
$subNews = !empty($newsData) ? array_slice($newsData, 1, 4) : [];

include 'header.php';
?>

<div class="race-bar" style="border-bottom: 2px solid <?php echo ($nextRace['status']=='LIVE')?'#0f0':'#f00'; ?>">
    <div class="race-status">
        <span class="race-flag"><?php echo $nextRace['flag']; ?></span>
        <div class="race-info">
            <span class="race-round" style="color:<?php echo ($nextRace['status']=='LIVE')?'#0f0':'#f00'; ?>">
                STATUS: <?php echo $nextRace['status']; ?>
            </span>
            <span class="race-name"><?php echo $nextRace['name']; ?></span>
        </div>
    </div>
    <div class="race-timer">DATE: <?php echo $nextRace['time']; ?></div>
</div>

<div style="max-width:1200px; margin:0 auto; padding:30px 20px;">
    
    <div class="section-header-f1">
        <h2>Latest News</h2>
        <a href="https://www.motorsport.com/f1/news/" target="_blank" class="view-all-link">Source: Motorsport.com</a>
    </div>

    <?php if ($heroNews): ?>
        <div class="f1-news-grid">
            <a href="<?php echo $heroNews['link']; ?>" target="_blank" class="f1-hero-card">
                <div class="hero-image-wrap">
                    <img src="<?php echo $heroNews['image']; ?>" onerror="this.src='assets/default_news.jpg'">
                    <div class="hero-overlay">
                        <span class="f1-tag"><?php echo $heroNews['category']; ?></span>
                        <h3><?php echo $heroNews['title']; ?></h3>
                        <p><?php echo $heroNews['desc']; ?></p>
                    </div>
                </div>
            </a>
            <div class="f1-sub-grid">
                <?php foreach($subNews as $n): ?>
                    <a href="<?php echo $n['link']; ?>" target="_blank" class="f1-news-card">
                        <div class="news-img"><img src="<?php echo $n['image']; ?>" onerror="this.src='assets/default_news.jpg'"></div>
                        <div class="news-content">
                            <span class="f1-tag small"><?php echo $n['category']; ?></span>
                            <h4><?php echo $n['title']; ?></h4>
                            <span class="news-time"><?php echo $n['date']; ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert" style="text-align:center; padding:40px; background:#222; border:1px solid red;">
            <h3 style="color:red;">⚠️ Gagal Mengambil Berita</h3>
            <p>Koneksi ke Motorsport.com diblokir atau gagal. Pastikan komputer terhubung internet.</p>
        </div>
    <?php endif; ?>

    <div class="section-header-f1" style="margin-top:60px;">
        <h2>Driver Standings</h2>
        <span style="font-size:12px; color:<?php echo ($lbData['status']=='LIVE')?'#0f0':'#f00'; ?>; border:1px solid currentColor; padding:2px 6px; border-radius:4px;">
            <?php echo $lbData['status']; ?> DATA
        </span>
    </div>

    <div class="standings-container">
        <div class="podium-grid">
            <div class="podium-card p2" style="border-color:silver;">
                <div class="podium-rank">2</div>
                <div class="podium-info">
                    <h3><?php echo $drivers[2]['name']; ?></h3>
                    <p style="color:<?php echo $drivers[2]['color']; ?>"><?php echo $drivers[2]['team']; ?></p>
                    <h4 class="p-points"><?php echo $drivers[2]['pts']; ?> PTS</h4>
                </div>
                <div class="podium-img-wrap" style="border-bottom: 4px solid <?php echo $drivers[2]['color']; ?>">
                    <img src="<?php echo $drivers[2]['img']; ?>" alt="Driver"> 
                </div>
            </div>
            <div class="podium-card p1" style="border-color:gold;">
                <div class="podium-rank">1</div>
                <div class="podium-info">
                    <h3><?php echo $drivers[1]['name']; ?></h3>
                    <p style="color:<?php echo $drivers[1]['color']; ?>"><?php echo $drivers[1]['team']; ?></p>
                    <h4 class="p-points"><?php echo $drivers[1]['pts']; ?> PTS</h4>
                </div>
                <div class="podium-img-wrap" style="border-bottom: 4px solid <?php echo $drivers[1]['color']; ?>">
                    <img src="<?php echo $drivers[1]['img']; ?>" alt="Driver">
                </div>
            </div>
            <div class="podium-card p3" style="border-color:#cd7f32;">
                <div class="podium-rank">3</div>
                <div class="podium-info">
                    <h3><?php echo $drivers[3]['name']; ?></h3>
                    <p style="color:<?php echo $drivers[3]['color']; ?>"><?php echo $drivers[3]['team']; ?></p>
                    <h4 class="p-points"><?php echo $drivers[3]['pts']; ?> PTS</h4>
                </div>
                <div class="podium-img-wrap" style="border-bottom: 4px solid <?php echo $drivers[3]['color']; ?>">
                    <img src="<?php echo $drivers[3]['img']; ?>" alt="Driver">
                </div>
            </div>
        </div>

        <div class="standings-list">
            <table class="f1-table">
                <thead><tr><th>POS</th><th>DRIVER</th><th>TEAM</th><th>PTS</th></tr></thead>
                <tbody>
                    <?php for($i=4; $i<=6; $i++): $d = $drivers[$i]; ?>
                    <tr>
                        <td class="pos-num"><?php echo $i; ?></td>
                        <td style="font-weight:bold; color:#fff; border-left:3px solid <?php echo $d['color']; ?>; padding-left:10px;">
                            <?php echo $d['flag'] . ' ' . $d['name']; ?>
                        </td>
                        <td style="color:#aaa;"><?php echo $d['team']; ?></td>
                        <td style="font-weight:bold;"><?php echo $d['pts']; ?></td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php include 'footer.php'; ?>