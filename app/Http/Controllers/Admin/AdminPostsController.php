<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostsRequest;
use App\Http\Requests\UpdatePostsRequest;
use App\Models\Illuminate\Posts;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = trim($request->get('query'));
        $posts = Posts::all();

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Posts $posts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Posts $posts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostsRequest $request, Posts $posts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Posts $posts)
    {
        //
    }
}