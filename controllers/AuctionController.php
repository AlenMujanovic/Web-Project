<?php

namespace App\Controllers;


class AuctionController extends \App\Core\Controller
{
    public function show($id)
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($id);

        if (!$auction) {
            header('Location: /');
            exit;
        }

        $this->set('auction', $auction);

        $lastOfferPrice = $this->getLastOfferPrice($id);
        if (!$lastOfferPrice) {
            $lastOfferPrice = $auction->starting_price;
        }


        $this->set('lastOfferPrice', $lastOfferPrice);
    }

    private function getLastOfferPrice($auctionId)
    {
        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());
        $offers = $offerModel->getAllByAuctionId($auctionId);
        $lastPrice = 0;

        foreach ($offers as $offer) {
            if ($lastPrice < $offer->price) {
                $lastPrice = $offer->price;
            }
        }

        return $lastPrice;
    }
}
