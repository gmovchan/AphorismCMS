<?php
namespace Application\Core;

class View
{
    /**
     * 
     * @param str $content_view подключаемая страница, пример '/auth/successfulAuth.php'
     * @param str $template_view шаблон страницы, пример 'authTemplate.php'
     * @param arr $data данные для отображения на странице 
     * @param arr $error текст ошибок, если есть
     */
    public function generate($content_view, $template_view, $data = null, $error = null)
    {
        require __DIR__ . '/../../views/templates/' . $template_view;
    }

}

?>
