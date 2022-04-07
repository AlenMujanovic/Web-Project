<?php

namespace App\Models;

use App\Core\DatabaseConnection;

class AuctionModel
{
    private $dbc;

    public function __construct(DatabaseConnection &$dbc)
    {
        $this->dbc = $dbc;
    }

    public function getById(int $auctionId)
    {
        $sql = 'SELECT * FROM auction WHERE auction_id = ?;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute([$auctionId]);
        $auction = NULL;

        if ($res) {
            $auction = $prep->fetch(\PDO::FETCH_OBJ);
        }

        return $auction;
    }

    public function getAllByCategoryId(int $categoryId): array
    {
        $sql = 'SELECT * FROM auction WHERE category_id = ?;';
        $prep = $this->dbc->getConnection()->prepare($sql);
        $res = $prep->execute([$categoryId]);
        $auctions = [];

        if ($res) {
            $auctions = $prep->fetchAll(\PDO::FETCH_OBJ);
        }

        return $auctions;
    }
}
