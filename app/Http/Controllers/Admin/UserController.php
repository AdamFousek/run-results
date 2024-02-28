<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Transformers\User\UserListTransformer;
use App\Models\Illuminate\User;
use App\Services\PaginateService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends AdminController
{
    public function __construct(
        private readonly PaginateService $paginateService,
        private readonly UserListTransformer $userListTransformer,
    ) {
    }

    private const int LIMIT = 50;

    public function index(Request $request): Response
    {
        $search = trim(strtolower($request->get('query')));
        $page = (int)$request->get('page', 1);

        $users = User::with('runner')->whereRaw('LOWER(`username`) LIKE ? ',["%$search%"])->orWhereRaw('LOWER(`email`) LIKE ? ',["%$search%"])->paginate(self::LIMIT);

        return Inertia::render('Admin/Users/Index', [
            'users' => $this->userListTransformer->transform($users->items()),
            'paginate' => [
                'links' => $this->paginateService->resolveLinks($users),
                'page' => $page,
                'total' => $users->total(),
                'limit' => self::LIMIT
            ],
            'search' => $search,
        ]);
    }

    public function edit(): void
    {

    }
}
