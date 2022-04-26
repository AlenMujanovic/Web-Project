<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Field;

class CategoryModel extends Model
{
    protected function getFields(): array
    {
        return [
            'category_id'     => new Field((new \App\Validators\NumberValidator())->setIntegerLength(11), false),
            'name'            => new Field((new \App\Validators\StringValidator(0, 64)))
        ];
    }
}
