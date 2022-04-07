<?php

namespace App\Controllers;


class CategoryController extends \App\Core\Controller
{
    public function show($id)
    {
        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $category = $categoryModel->getById($id);

        if (!$category) {
            header('Location: /');
            exit;
        }

        $this->set('category', $category);


        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auctionsInCategory = $auctionModel->getAllByCategoryId($id);
        $this->set('auctionsInCategory', $auctionsInCategory);
    }
}
