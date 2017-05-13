<?php
namespace Application\Core;

class View
{
    /**
     * 
     * @param string $content_view подключаемая страница, пример '/auth/successfulAuth.php'
     * @param string $template_view шаблон страницы, пример 'authTemplate.php'
     * @param array $data данные для отображения на странице 
     * @param array $error текст ошибок, если есть
     */
    public function generate($content_view, $template_view, $data = null, $error = null)
    {
        require __DIR__ . '/../../views/templates/' . $template_view;
    }
    
    // Защита от XSS уязвимостей. Вызывается в представлении с помощью $this->html('text');
    public function html($text) {
        return htmlspecialchars($text, ENT_QUOTES);
    }

}

?>
