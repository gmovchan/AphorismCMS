<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;

class AuthorsModel extends Model
{

    //private $quotesArray = array();
    //private $authorsArray = array();
    private $dbh;

    public function __construct()
    {
        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
    }

    /**
     * Получает имена авторов и количество цитат автора
     * @param string $sortingType тип сортировки 'quotes' - по количеству цитат, 
     * 'names' - авторы по алфавиту
     * @return array
     */
    public function getAllAuthors($sortingType)
    {
        $authors = $this->dbh->query("SELECT * FROM `authors`;", 'fetchAll', '');

        // получает количество цитат автора
        foreach ($authors as $key => $author) {
            $countQuotes = $this->dbh->query("SELECT COUNT(*) FROM `quotes` WHERE `author_id` = ?;", 'fetch', '', array($author['id']));
            $countQuotes = $countQuotes[0];
            $authors[$key]['countQuotes'] = (integer) $countQuotes;
        }

        switch ($sortingType) {
            case 'quotes':
                // сортирует по количеству цитат у автора
                foreach ($authors as $key => $author) {
                    $countQuotesArray[$key] = $author['countQuotes'];
                }
                array_multisort($countQuotesArray, SORT_NUMERIC, SORT_DESC, $authors);

                break;

            case 'names':
                // сортирует по имени в алфавитном порядке
                foreach ($authors as $key => $author) {
                    $names[$key] = $author['name'];
                }

                // для регистронезависимой сортировки все буквы заменяются на строчные
                $namesStrtolower = array_map('mb_strtolower', $names);
                array_multisort($namesStrtolower, SORT_STRING, SORT_ASC, $authors);

                break;

            default:
                $this->ensure(false, 'Не задан тип сортировки');
                break;
        }

        return $authors;
    }

    // добавляет нового автора
    public function addAuthor($name = null)
    {
        if (is_null($name)) {
            $this->errors[] = "Имя автора пустое";
            return false;
        }

        // проверяет, существует ли автор с похожим именем в таблице
        if ($this->searchAuthor($name)) {
            $this->errors[] = "Автор уже существует";
            return false;
        } else {
            $this->dbh->query("INSERT INTO `authors` (`name`) VALUES (?)", 'none', '', array($name));
            $this->successful[] = "Новый автор \"$name\" добавлен";
            return true;
        }
    }

    /**
     * Ищет автора с похожим именем в таблице
     * Если похожий автор найден, то вернет TRUE
     * @param string $name
     * @return boolean
     */
    private function searchAuthor(string $name)
    {
        $searchQuery = "%$name%";
        $countAuthor = $this->dbh->query("SELECT * FROM `authors` WHERE `name` = ?;", 'rowCount', '', array($searchQuery));
        return $countAuthor >= 1;
    }

    // Удаляет автора из БД и заменяет автора у цитат, которые раньше ему принадлежали, на "неизвестен".
    public function delAuthor($id)
    {
        $this->ensure(!is_null($id), "Не удалось получить id автора.");

        $name = $this->checkID($id);

        // FIXME: id автора надо вынести в конфиг-файл, он испольуется по всему 
        // сркипту и должна быть возможность менять его в одном месте
        $this->replaceAuthor($id, 157);

        $delete = $this->dbh->query("DELETE FROM `authors` WHERE `id` = ?;", 'rowCount', '', array($id));

        if ($delete === 1) {
            $this->successful[] = "Автор \"{$name}\" удален.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить автора \"{$name}\"";
            return false;
        }
    }

    /**
     * Заменяет автора у цитат, которые ему принадлежат, на автора, чей id передан вторым аргументом.
     * @param type $authorIdReplaceable заменяемый автор
     * @param type $authorIdReplacing замена автора
     */
    private function replaceAuthor($authorIdReplaceable, $authorIdReplacing)
    {
        $quotes = $this->dbh->query("SELECT `id` FROM `quotes` WHERE `author_id` = ?;", 'fetchAll', '', array($authorIdReplaceable));

        foreach ($quotes as $quote) {
            $replace = $this->dbh->query("UPDATE `quotes` SET `author_id` = ? WHERE `id` = ?;", 'none', '', array($authorIdReplacing, $quote['id']));
        }
    }

    // проверяет существование автора и возвращает его имя
    private function checkID($id)
    {
        $author = $this->dbh->query("SELECT `name` FROM `authors` WHERE `id` = ?;", 'fetch', '', array($id));

        if (!empty($author)) {
            return $author['name'];
        } else {
            $this->ensure(false, "Автор с id{$id} не найден в таблице.");
        }
    }

    public function getAuthor($id)
    {
        $author = $this->dbh->query("SELECT * FROM `authors` WHERE `id` = ?;", 'fetch', '', array($id));
        return $author;
    }

    // Переименовывает автора.
    public function renameAuthor($formContent)
    {
        $id = $formContent['idInDB'];
        $name = $formContent['authorName'];
                
        $this->ensure($this->checkID($id), "Автор id{$id} не найден в БД");

        if (empty($name)) {
            $this->errors[] = "Поле с именем не должно быть пустым.";
            return false;
            } elseif (iconv_strlen($name) > 128) {
            $this->errors[] = "Длина имени больше 128 символов.";
            return false;
        }

        $this->dbh->query("UPDATE `authors` SET `name` = ? WHERE `id` = ?;", 'none', '', array($name, $id));
        $this->successful[] = "Автор переименован.";
        return true;
    }

}
