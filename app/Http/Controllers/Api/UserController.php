<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getTopCreators()
    {
        // Example query to get top creators with post counts for the week
        $weekCreators = User::withCount(['posts' => function ($query) {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }])->orderBy('posts_count', 'desc')->limit(10)->get();

        // Example query to get top creators with post counts for all time
        $allTimeCreators = User::withCount('posts')->orderBy('posts_count', 'desc')->limit(10)->get();

        return response()->json([
            'week' => $weekCreators,
            'allTime' => $allTimeCreators
        ]);
    }
}
