<?php

namespace Application\IndexControllers;

use Application\Core\Controller;
use Application\Core\ErrorHandler;
use Application\Models\CommentsModel;
use Application\Models\FeedbacksModel;
use Application\Model\CaptchaModel;

class FeedbacksController extends Controller
{

    private $comments;
    private $captcha;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'feedbacks';
        $this->data['title'] = "Отзывы";
        $this->comments = new FeedbacksModel();
        $this->captcha = new CaptchaModel;
        $this->data['thisURI'] = '/feedbacks/addfeedback';
    }

    public function getPage()
    {             
        $this->data['comments'] = $this->comments->getComments();
        $this->view->generate('/index/feedback.php', 'indexTemplate.php', $this->data);
    }

    public function addFeedback()
    {
        if (isset($_POST['comment'])) {

            $formContent = $this->request->getProperty('POST');

            if ($this->comments->addComment($formContent)) {
                $this->data['successful'] = $this->comments->getSuccessful();
                $this->getPage();
            } else {
                $this->data['errors'] = $this->comments->getErrors();
                $this->getPage();
            }
        }
    }

    // возвращает картинку с каптчей
    public function getCaptchaImg()
    {
        header('Content-type: image/jpeg');
        echo $this->captcha->getCaptchaImg();
    }

}
