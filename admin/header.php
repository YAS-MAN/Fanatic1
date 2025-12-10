<?php
// Pastikan sesi dimulai dan require_admin_login dipanggil sebelum header
require_once __DIR__ . '/auth_admin.php';
require_admin_login();

// Ambil info user yang sedang login
$currentUser = current_user();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - Admin F1' : 'Admin F1'; ?></title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="style_admin.css" />
</head>
<body>
    <nav class="admin-nav">
        <div class="admin-nav-inner">
            <a href="index.php" class="logo">
                <span class="logo-text"><span class="red-f">ADMIN</span> Fanatic</span>
                <span class="logo-f1">1</span>
            </a>
            <div class="admin-nav-right">
                <span class="admin-welcome">Selamat datang, <?php echo htmlspecialchars($currentUser['name'] ?? 'Admin'); ?></span>
                
                <a href="../logout.php" class="admin-logout" onclick="return confirm('Akhiri sesi Admin?');">Logout</a>
            
            </div>
        </div>
    </nav>
    
    <div class="admin-layout">
        <div class="admin-content" style="max-width:1200px; width:100%; margin:0 auto; padding:20px 0 60px 0;">
            
            <script>
                // Script untuk tombol Kembali (Fixed position)
                (function(){
                    function positionAdminBack(){
                        try{
                            var banner = document.querySelector('.page-banner');
                            var buttons = document.querySelectorAll('.detail-back');
                            if(!banner || !buttons || buttons.length===0) return;
                            var rect = banner.getBoundingClientRect();
                            var top = rect.top + rect.height/2 + window.scrollY;
                            buttons.forEach(function(b){
                                b.style.position = 'fixed';
                                b.style.left = '14px';
                                b.style.top = (top) + 'px';
                                b.style.transform = 'translateY(-50%)';
                                b.style.zIndex = '9999';
                            });
                        }catch(e){} 
                    }
                    document.addEventListener('DOMContentLoaded', positionAdminBack);
                    window.addEventListener('resize', positionAdminBack);
                    window.addEventListener('scroll', positionAdminBack);
                })();
            </script>