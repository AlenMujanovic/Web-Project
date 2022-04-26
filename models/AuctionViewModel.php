<?php

namespace App\Models;

use App\Core\DatabaseConnection;
use App\Core\Field;
use App\Core\Model;

class AuctionViewModel extends Model
{

    protected function getFields(): array
    {
        return [
            'auction_view_id' => new Field((new \App\Validators\NumberValidator())->setIntegerLength(11), false),
            'created_at'      => new Field((new \App\Validators\DateTimeValidator())->allowDate()->allowTime(), false),

            'auction_id'      => new Field((new \App\Validators\NumberValidator())->setIntegerLength(11)),
            'ip_address'      => new Field((new \App\Validators\StringValidator(7, 255))),
            'user_agent'      => new Field((new \App\Validators\StringValidator(0, 255)))
        ];
    }

    public function getAllByAuctionId(int $auctionId): array
    {
        return $this->getAllByFieldName('auction_id', $auctionId);
    }

    public function getAllByIpAddress(int $ipAddress): array
    {
        return $this->getAllByFieldName('ip_address', $ipAddress);
    }
}
