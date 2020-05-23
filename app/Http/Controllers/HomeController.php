<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // ログインユーザを取得する
        $user = Auth::user();

        //　ログインユーザに紐づくフォルダを一つ取得する

        $folder = $user->folders()->first();



        //また一つもフォルダを作っていなければホームページをレスポンスする
        if(is_null($folder)) {
            return view('home');
        }


        //フォルダがあればそのフォルダのタスク一覧にリダイレクトする
        return redirect()->route('tasks.index', [
            $folder->id,
        ]);
    }
}