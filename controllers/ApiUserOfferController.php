<?php

namespace App\Controllers;

use \App\Core\UserApiController;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ApiUserOfferController extends UserApiController
{
    public function postMakeOffer()
    {
        $userId = $this->getSession()->get('user_id');

        $auctionId  = \filter_input(INPUT_POST, 'auction_id', FILTER_SANITIZE_NUMBER_INT);
        $offerPrice = floatval(\filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING));

        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($auctionId);

        if (!$auction) {
            $this->set('error', -20001);
            $this->set('message', 'This auction does not exist.');
            return;
        }

        if (!$auction->is_active) {
            $this->set('error', -20002);
            $this->set('message', 'This auction is not active.');
            return;
        }

        $auctionEndsAtTimestamp = strtotime($auction->ends_at);
        $currentTimestamp = time();

        if ($currentTimestamp > $auctionEndsAtTimestamp) {
            $this->set('error', -20003);
            $this->set('message', 'This auction has ended.');
            return;
        }

        $auctionStartsAtTimestamp = strtotime($auction->starts_at);
        if ($currentTimestamp < $auctionStartsAtTimestamp) {
            $this->set('error', -20004);
            $this->set('message', 'This auction has not yet started.');
            return;
        }

        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());
        $currentAuctionPrice = $offerModel->getLastOfferPrice($auction);

        if ($currentAuctionPrice + 2.0 > $offerPrice) {
            $this->set('error', -20005);
            $this->set('message', 'This offer price is too low.');
            return;
        }

        if ($userId == $auction->user_id) {
            $this->set('error', -10002);
            $this->set('message', 'You cannot make an offer for your auction.');
            return;
        }

        $offerModel = new \App\Models\OfferModel($this->getDatabaseConnection());

        $offerPriceStirng = sprintf('%.2f', $offerPrice);

        $offerId = $offerModel->add([
            'auction_id' => $auction->auction_id,
            'user_id'    => $userId,
            'price'      => $offerPriceStirng
        ]);

        if (!$offerId) {
            $this->set('error', -10002);
            $this->set('message', 'There was an error trying to add this offer.');
            return;
        }

        $this->set('error', 0);
        $this->set('message', 'Success.');
        $this->set('offer_id', $offerId);
    }
}
