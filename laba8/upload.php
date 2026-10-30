<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar'])) {
        die("Файл не надіслано.");
    }

    $file = $_FILES['avatar'];

    echo "<pre>";
    var_dump($file);
    echo "</pre>";
    echo "<hr>";

    if ($file['error'] === UPLOAD_ERR_OK) {

        $maxSize = 20 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            die("Файл завеликий! Максимум 20 МБ.");
        }
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExtensions)) {
            die("Дозволено завантажувати ТІЛЬКИ картинки (jpg, jpeg, png)!");
        }

        $newName = uniqid('avatar_', true) . '.' . $ext;
        $tmpPath = $file['tmp_name'];
        $destination = 'uploads/' . $newName;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($tmpPath, $destination)) {
            echo "Файл успішно збережений як: " . $destination;
        } else {
            echo "Помилка запису на диск (можливо, немає прав у папки uploads).";
        }
    } else {
        echo "Файл не було завантажено (Помилка " . $file['error'] . ").";
    }
}
?>
<br><br>
<a href="index.php">До списку файлів</a>