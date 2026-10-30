<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторна робота №8</title>
</head>
<body>
    <h2>Завантаження файлу</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label>Оберіть зображення (Avatar):</label><br>
        <input type="file" name="avatar" accept="image/png, image/jpeg"><br><br>
        <button type="submit">Завантажити</button>
    </form>

    <h1>Список завантажених файлів</h1>
    <ul>
        <?php
        $dir = 'uploads/';
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                echo "<li><a href='uploads/" . htmlspecialchars($file) . "'>$file</a></li>";
            }
        }
        ?>
    </ul>
</body>
</html>