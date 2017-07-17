<?php

namespace Application\AdminControllers;

use Application\Core\Controller;
use Application\Core\ErrorHandler;
use Application\Models\CommentsModel;
use Application\Models\FeedbacksModel;
use Application\AdminControllers\AdminController;

class FeedbacksController extends AdminController
{

    private $comments;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'feedbacks';
        $this->data['title'] = "Отзывы";
        $this->comments = new FeedbacksModel();
    }

    public function getPage()
    {             
        $this->data['comments'] = $this->comments->getComments();
        $this->view->generate('/admin/feedback.php', 'adminTemplate.php', $this->data);
    }

    public function delComment()
    {
        $getArray = $this->request->getProperty('GET');
        $commentId = $getArray['comment_id'];

        if ($this->comments->delComment($commentId)) {
            $this->data['successful'] = $this->comments->getSuccessful();
            $this->getPage();
        } else {
            $this->data['errors'] = $this->comments->getErrors();
            $this->getPage();
        }
    }

}
