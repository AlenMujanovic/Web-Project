<?php

namespace App\Models;

use App\Core\DatabaseConnection;

class CategoryModel
{
    private $dbc;

    public function __construct(DatabaseConnection &$dbc)
    {
        $this->dbc = $dbc;
    }

    public function getById(int $categoryId)
    {
        $sql = 'SELECT * FROM category WHERE category_id = ?;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute([$categoryId]);
        $category = NULL;

        if ($res) {
            $category = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $category;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM category;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute();
        $categorys = [];

        if ($res) {
            $categorys = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $categorys;
    }
}
