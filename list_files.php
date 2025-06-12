<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar File yang Telah Diunggah</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar File yang Telah Diunggah</h2>

        <?php
        // Tampilkan pesan status dari URL
        if (isset($_GET['status']) && isset($_GET['message'])) {
            $class = ($_GET['status'] == 'success') ? 'success' : 'error';
            $message = $_GET['message'];
            echo '<div class="message ' . $class . '">' . htmlspecialchars($message) . '</div>';
        }

        $upload_dir = "uploads/";

        // Logika untuk menghapus file
        if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['file'])) {
            $file_to_delete = $upload_dir . basename($_GET['file']);
            if (file_exists($file_to_delete)) {
                if (unlink($file_to_delete)) {
                    echo '<div class="message success">Berkas ' . htmlspecialchars($_GET['file']) . ' berhasil dihapus.</div>';
                } else {
                    echo '<div class="message error">Gagal menghapus berkas ' . htmlspecialchars($_GET['file']) . '.</div>';
                }
            } else {
                echo '<div class="message error">Berkas ' . htmlspecialchars($_GET['file']) . ' tidak ditemukan.</div>';
            }
        }

        // Ambil daftar file dari direktori
        $files = is_dir($upload_dir) ? array_diff(scandir($upload_dir), array('.', '..')) : array();

        if (empty($files)) {
            echo "<div class='empty-message'>Belum ada file yang diunggah.</div>";
        } else {
            echo '<div class="file-list">';
            foreach ($files as $file) {
                $file_path = $upload_dir . $file;
                $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $file_size = filesize($file_path);
                $icon = '📄'; // Ikon default

                // Tentukan ikon berdasarkan ekstensi file
                switch ($file_extension) {
                    case 'pdf': $icon = '📑'; break;
                    case 'png': case 'jpg': case 'jpeg': case 'gif': $icon = '🖼️'; break;
                    case 'doc': case 'docx': $icon = '📝'; break;
                    case 'zip': case 'rar': $icon = '🗄️'; break;
                    case 'txt': $icon = '📋'; break;
                }

                echo '<div class="file-item">';
                echo '<div class="file-info">';
                echo '<span class="file-icon">' . $icon . '</span>';
                echo '<span class="file-name">' . htmlspecialchars($file) . '</span>';
                echo '<span class="file-size">(' . formatFileSize($file_size) . ')</span>';
                echo '</div>';
                echo '<div class="actions">';
                echo '<a href="' . htmlspecialchars($file_path) . '" download class="download-btn">Unduh</a>';
                echo '<a href="?action=delete&file=' . urlencode($file) . '" class="delete-btn" onclick="return confirm(\'Anda yakin ingin menghapus file ini?\')">Hapus</a>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }

        // Fungsi bantuan untuk memformat ukuran file
        function formatFileSize($bytes) {
            if ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        }
        ?>

        <a href="index.html" class="back-link">Unggah File Lain</a>
    </div>
</body>
</html>