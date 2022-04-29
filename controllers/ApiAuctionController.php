<?php

namespace App\Controllers;

class ApiAuctionController extends \App\Core\ApiController
{
    public function show($id)
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($id);


        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());
        $lastOfferPrice = $offerModel->getLastOfferPrice($auction);
        $auction->last_offer_price = $lastOfferPrice;

        $this->set('auction', $auction);
    }
}
