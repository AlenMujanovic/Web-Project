<?php

namespace App\Models;

use App\Core\DatabaseConnection;
use App\Core\Model;
use App\Core\Field;
use App\Validators\NumberValidator;
use App\Validators\DateTimeValidator;
use App\Validators\StringValidator;
use App\Validators\BitValidator;

class AuctionModel extends Model
{
    protected function getFields(): array
    {
        return [
            'auction_id'      => new Field((new NumberValidator())->setIntegerLength(11), false),
            'created_at'      => new Field((new DateTimeValidator())->allowDate()->allowTime(), false),

            'title'           => new Field((new StringValidator())->setMaxLength(255)),
            'description'     => new Field((new StringValidator())->setMaxLength(64 * 1024)),
            'starting_price'  => new Field((new NumberValidator())
                ->setDecimal()
                ->setUnsigned()
                ->setIntegerLength(7)
                ->setMaxDecimalDigits(2)),
            'starts_at'       => new Field((new DateTimeValidator())->allowDate()->allowTime()),
            'ends_at'         => new Field((new DateTimeValidator())->allowDate()->allowTime()),
            'is_active'       => new Field(new BitValidator()),
            'category_id'     => new Field((new NumberValidator())->setIntegerLength(11)),
            'user_id'         => new Field((new NumberValidator())->setIntegerLength(11)),
            // 'image_path'      => new Field((new StringValidator())->setMaxLength(255))
        ];
    }

    public function getAllByCategoryId(int $categoryId): array
    {
        return $this->getAllByFieldName('category_id', $categoryId);
    }

    public function getAllByUserId(int $userId): array
    {
        return $this->getAllByFieldName('user_id', $userId);
    }

    public function getAllBySearch(string $keywords)
    {
        $sql = 'SELECT * FROM `auction` WHERE `title` LIKE ? OR `description` LIKE ?;';

        $keywords = '%' . $keywords . '%';

        $prep = $this->getConnection()->prepare($sql);
        if (!$prep) {
            return [];
        }

        $res = $prep->execute([$keywords, $keywords]);
        if (!$res) {
            return [];
        }

        return $prep->fetchAll(\PDO::FETCH_OBJ);
    }
}
