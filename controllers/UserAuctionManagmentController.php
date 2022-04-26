<?php

namespace App\Controllers;

use App\Core\Role\UserRoleController;
use App\Models\AuctionModel;
use App\Models\CategoryModel;
use App\Models\OfferModel;
use Configuration;
use Exception;

class UserAuctionManagmentController extends UserRoleController
{
    public function auctions()
    {
        $userId = $this->getSession()->get('user_id');

        $auctionModel = new AuctionModel($this->getDatabaseConnection());
        $auctions = $auctionModel->getAllByUserId($userId);

        $this->set('auctions', $auctions);
    }

    public function getAdd()
    {
        $categoryModel = new CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set('categories', $categories);
    }

    public function postAdd()
    {
        $addData = [
            'title'          => \filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description'    => \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
            'starting_price' => sprintf("%.2f", \filter_input(INPUT_POST, 'starting_price', FILTER_SANITIZE_STRING)),
            'starts_at'      => \filter_input(INPUT_POST, 'starts_at', FILTER_SANITIZE_STRING),
            'ends_at'        => \filter_input(INPUT_POST, 'ends_at', FILTER_SANITIZE_STRING),
            'category_id'    => \filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT),
            'user_id'        => $this->getSession()->get('user_id')
        ];


        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());

        $auctionId = $auctionModel->add($addData);

        if (!$auctionId) {
            $this->set('message', 'Auction failed to add!');
            return;
        }

        $uploadStatus = $this->doImageUpload('image', $auctionId);
        if (!$uploadStatus) {
            return;
        }

        $this->redirect(\Configuration::BASE . 'user/auctions');
    }

    public function getEdit($auctionId)
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($auctionId);

        if (!$auction) {
            $this->redirect(\Configuration::BASE . 'user/auctions');
            return;
        }

        if ($auction->user_id != $this->getSession()->get('user_id')) {
            $this->redirect(\Configuration::BASE . 'user/auctions');
            return;
        }

        $offerModel = new OfferModel($this->getDatabaseConnection());
        $offer = $offerModel->getAllByAuctionId($auctionId);
        if (count($offer) > 0) {
            $this->redirect(\Configuration::BASE . 'user/auctions');
            return;
        }

        $auction->starts_at = str_replace(' ', 'T', substr($auction->starts_at, 0, 16));
        $auction->ends_at   = str_replace(' ', 'T', substr($auction->ends_at, 0, 16));

        $this->set('auction', $auction);

        $categoryModel = new \App\Models\CategoryModel($this->getDatabaseConnection());
        $categories = $categoryModel->getAll();
        $this->set('categories', $categories);
    }

    public function postEdit($auctionId)
    {
        $this->getEdit($auctionId);

        $editData = [
            'title'          => \filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description'    => \filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
            'starting_price' => sprintf("%.2f", \filter_input(INPUT_POST, 'starting_price', FILTER_SANITIZE_STRING)),
            'starts_at'      => \filter_input(INPUT_POST, 'starts_at', FILTER_SANITIZE_STRING),
            'ends_at'        => \filter_input(INPUT_POST, 'ends_at', FILTER_SANITIZE_STRING),
            'category_id'    => \filter_input(INPUT_POST, 'category_id', FILTER_SANITIZE_NUMBER_INT)
        ];

        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());

        $res = $auctionModel->editById($auctionId, $editData);
        if (!$res) {
            $this->set('message', 'Not possible editing auction!');
            return;
        }

        $uploadStatus = $this->doImageUpload('image', $auctionId);

        if (!$uploadStatus) {
            return;
        }

        $this->redirect(\Configuration::BASE . 'user/auctions');
    }

    public function doImageUpload(string $fieldName, string $fileName): bool
    {

        unlink(\Configuration::UPLOAD_DIR . $fileName . '.jpg');

        $uploadPath = new \Upload\Storage\FileSystem(\Configuration::UPLOAD_DIR);
        $file = new \Upload\File($fieldName, $uploadPath);
        $file->setName($fileName);
        $file->addValidations([
            new \Upload\Validation\Mimetype(["image/jpeg", "image/png"]),
            new \Upload\Validation\Size("3M")
        ]);

        try {
            $file->upload();
            return true;
        } catch (Exception $e) {
            $this->set('message', "Error" . $e->getMessage());
            return false;
        }
    }
}
