<?php

namespace App\Models;

use App\Core\DatabaseConnection;
use App\Core\Model;
use App\Core\Field;

class UserModel extends Model
{

    protected function getFields(): array
    {
        return [
            'user_id'         => new Field((new \App\Validators\NumberValidator())->setIntegerLength(11), false),
            'created_at'      => new Field((new \App\Validators\DateTimeValidator())->allowDate()->allowTime(), false),

            'username'        => new Field((new \App\Validators\StringValidator(0, 64))),
            'password_hash'   => new Field((new \App\Validators\StringValidator(0, 128))),
            'email'           => new Field((new \App\Validators\StringValidator(0, 255))),
            'forename'        => new Field((new \App\Validators\StringValidator(0, 64))),
            'surname'         => new Field((new \App\Validators\StringValidator(0, 64))),
            'is_active'       => new Field((new \App\Validators\BitValidator()))
        ];
    }


    public function getByUsername(string $username)
    {
        return $this->getByFieldName('username', $username);
    }
}
