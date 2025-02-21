<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function indexAll()
    {
        $allUsers = $this->userService->getAll();
        return response()->json(['error' => false,'users' => $allUsers]);
    }

    public function index($id)
    {
        $user = $this->userService->get($id);
        return response()->json(['error' => false, 'user' => $user]);
    }

    public function create(CreateUserRequest $request)
    {   
        $data = $request->validated();
        $user = $this->userService->create($data);

        return response()->json(['message' => 'Usuário criado com sucesso.', 'user' => $user]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userService->update($id, $request->validated());
        return response()->json(['message' => 'Usuário atualizado com sucesso.', 'user' => $user]);
    }

    public function delete($id)
    {
        $this->userService->delete($id);
        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }
}
