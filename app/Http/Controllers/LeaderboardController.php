<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;


class LeaderboardController extends Controller
{

    public function index()
    {

        $leaderboard = Leaderboard::orderBy('time', 'ASC')->get();

        return view('welcome', [
            'leaderboard' => $leaderboard,
        ]);
    }

    public function store()
    {

        $data = request()->validate([
            'name' => 'required|max:30',
            'time' => 'required|integer|gt:0'
        ]);

        $player = Leaderboard::firstOrNew([
            'name' => $data['name']
        ]);

        $player->time = empty($player->time) ? $data['time'] : min($player->time, $data['time']);

        $player->save();

        return redirect('/');
    }
}
