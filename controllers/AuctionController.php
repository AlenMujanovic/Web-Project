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

        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());
        $lastOfferPrice = $offerModel->getLastOfferPrice($auction);
        $this->set('lastOfferPrice', $lastOfferPrice);

        $auctionViewModel = new \App\Models\AuctionViewModel($this->getDatabaseConnection());

        $ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $userAgent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');

        $auctionViewModel->add(
            [
                'auction_id' => $id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]
        );

        $this->set('showBidForm', true);

        if ($this->getSession()->get('user_id') === null) {
            $this->set('showBidForm', false);
        }

        $auctionEndsAtTimestamp = strtotime($auction->ends_at);
        $currentTimestamp = time();

        if ($currentTimestamp > $auctionEndsAtTimestamp) {
            $this->set('showBidForm', false);
        }
    }

    private function normaliseKeywords(string $keywords): string
    {
        $keywords = trim($keywords);
        $keywords = preg_replace('/ +/', ' ', $keywords);
        # ...
        return $keywords;
    }

    public function postSearch()
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());

        $q = filter_input(INPUT_POST, 'q', FILTER_SANITIZE_STRING);

        $keywords = $this->normaliseKeywords($q);

        $auctions = $auctionModel->getAllBySearch($q);

        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());

        $auctions = array_map(function ($auction) use ($offerModel) {
            $auction->last_offer_price = $offerModel->getLastOfferPrice($auction);
            return $auction;
        }, $auctions);

        $this->set('auctions', $auctions);
    }
}
