<?php

namespace App\Controllers;

use App\Core\ApiController;

class ApiAuctionController extends ApiController
{

    public function show($id)
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($id);

        $this->set('auction', $auction);
    }
}
