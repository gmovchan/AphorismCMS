<?php
    if (isset($data['errors'])) {
        require __DIR__ . '/../errors/errorsList.php';
    }

    if (isset($data['successful'])) {
        require __DIR__ . '/../successful/successfulList.php';
        // очищает поля формы в случае успеха
        unset($_POST);
    }