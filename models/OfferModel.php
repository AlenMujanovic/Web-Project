<?php

namespace App\Models;

use App\Core\DatabaseConnection;

class OfferModel
{
    private $dbc;

    public function __construct(DatabaseConnection &$dbc)
    {
        $this->dbc = $dbc;
    }

    public function getById(int $offerId)
    {
        $sql = 'SELECT * FROM offer WHERE offer_id = ?;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute([$offerId]);
        $offer = NULL;

        if ($res) {
            $offer = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $offer;
    }

    public function getAllByAuctionId(int $auctionId): array
    {
        $sql = 'SELECT * FROM offer WHERE auction_id = ? ORDER BY created_at ASC;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute([$auctionId]);
        $offers = [];

        if ($res) {
            $offers = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $offers;
    }
}
