<?php 

namespace App\GraphQL\Mutations;

use App\Models\User;

class DeleteUserUser
{
    public function __invoke($_, array $args)
    {
        $user = User::find($args['id']);

        $user->delete();
        return $user;
    }
}
