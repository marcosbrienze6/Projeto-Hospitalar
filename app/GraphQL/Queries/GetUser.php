<?php
namespace App\GraphQL\Queries;

use App\Models\User;

class GetUser
{
    public function __invoke($_, array $args)
    {
        return User::find($args['id']);
    }
}
