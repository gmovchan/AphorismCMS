<?php

namespace Application\AdminControllers;

use Application\Core\Controller;
use Application\Models\AuthorsModel;
use Application\Models\QuotesModel;
use Application\Core\Errors;

class AuthorsController extends AdminController
{

    private $authors;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'authors';
        $this->authors = new AuthorsModel();
    }

    public function getPage()
    {
        $this->getAuthors();
    }
 
    public function addAuthor()
    {
        $formContent = $this->request->getProperty('POST');
        $name = $formContent['authorName'];
        if ($this->authors->addAuthor($name)) {
            $this->data['successful'] = $this->authors->getSuccessful();
            $this->getAuthors();
        } else {
            $this->data['errors'] = $this->authors->getErrors();
            $this->getAuthors();
        }
    }
    
    public function getAuthors()
    {
        $this->data['thisPage'] = 'authors';
        // 'quotes' - сортировка по количеству цитат;
        $authors = $this->authors->getAllAuthors('quotes');
        $this->data['authors'] = $authors;
        $this->view->generate('/admin/authors.php', 'adminTemplate.php', $this->data);
    }
    
    public function delAuthor()
    {
        $getArray = $this->request->getProperty('GET');
        $id = $getArray['author_id'];

        if ($this->authors->delAuthor($id)) {
            $this->data['successful'] = $this->authors->getSuccessful();
            $this->getAuthors();
        } else {
            $this->data['errors'] = $this->authors->getErrors();
            $this->getAuthors();
        }
    }
    
    public function editAuthor()
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        if (isset($_POST['idInDB'])) {

            $formContent = $this->request->getProperty('POST');
            $authorID = $formContent['idInDB'];

            if ($this->authors->renameAuthor($formContent)) {
                $this->data['successful'] = $this->authors->getSuccessful();
                $this->getAuthors();
            } else {
                $this->data['errors'] = $this->authors->getErrors();
                $this->getAuthorEditor($authorID);
            }
        } else {
            $getArray = $this->request->getProperty('GET');
            $authorID = $getArray['author_id'];
            $this->getAuthorEditor($authorID);
        }
    }
    
    private function getAuthorEditor($authorID)
    {
        $author = $this->authors->getAuthor($authorID);

        if (is_null($author)) {
            Errors::printErrorPage404();
        } else {
            $this->data['author'] = $author;
        }

        $this->view->generate('/admin/editAuthor.php', 'adminTemplate.php', $this->data);
    }

}
