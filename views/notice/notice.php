<?php
    if (!empty($data['errors'])) {
        require __DIR__ . '/errorsList.php';
    }

    if (!empty($data['successful'])) {
        require __DIR__ . '/successfulList.php';
        // очищает поля формы в случае успеха
        unset($_POST);
    }
