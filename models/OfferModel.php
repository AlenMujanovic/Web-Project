<?php

namespace App\Models;

use App\Core\DatabaseConnection;
use App\Core\Model;
use App\Core\Field;
use App\Validators\NumberValidator;
use App\Validators\DateTimeValidator;
use App\Validators\StringValidator;
use App\Validators\BitValidator;

class OfferModel extends Model
{

    protected function getFields(): array
    {
        return [
            'offer_id'        => new Field((new NumberValidator())->setIntegerLength(20), false),
            'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

            'auction_id'      => new Field((new NumberValidator())->setIntegerLength(11)),
            'user_id'         => new Field((new NumberValidator())->setIntegerLength(11)),
            'price'           => new Field((new NumberValidator())->setDecimal()
                ->setUnsigned()
                ->setIntegerLength(7)
                ->setMaxDecimalDigits(2))
        ];
    }

    public function getAllByAuctionId(int $auctionId): array
    {
        $items =  $this->getAllByFieldName('auction_id', $auctionId);
        usort($items, function ($a, $b) {
            return strcmp($a->created_at, $b->created_at);
        });

        return $items;
    }

    public function getLastByAuctionId(int $auctionId)
    {
        $sql = 'SELECT * FROM `offer` WHERE `auction_id` = ? ORDER BY `created_at` DESC LIMIT 1;';
        $prep = $this->getConnection()->prepare($sql);

        if (!$prep) {
            return null;
        }

        $res = $prep->execute([$auctionId]);

        if (!$res) {
            return null;
        }

        return $prep->fetch(\PDO::FETCH_OBJ);
    }

    public function getLastOfferPrice($auction)
    {
        $lastOffer = $this->getLastByAuctionId($auction->auction_id);

        if (!$lastOffer) {
            return $auction->starting_price;
        }

        return $lastOffer->price;
    }
}
