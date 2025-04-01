<?php 

namespace App\GraphQL\Mutations;

use App\Models\User;

class UpdateUserUser
{
    public function __invoke($_, array $args)
    {
        $user = User::find($args['id']);

        if (isset($args['name'])) {
            $user->name = $args['name'];
        }

        $user->save();

        return $user;
    }
}
