<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;
use Application\Core\Request;

class AdminModel extends Model
{

    //private $quotesArray = array();
    //private $authorsArray = array();
    private $dbh;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
        
    }
}