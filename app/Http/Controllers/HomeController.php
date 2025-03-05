<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class HomeController extends Controller
{
    protected $types;
    protected $units;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

        // 設定ファイル（種別・単位）の値をコンストラクタで取得
        $this->types = config('types.types');
        $this->units = config('units.units');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * 商品一覧を表示(管理者・一般ユーザー共用)
     *
     * @param $request
     * @return $response
     */
    public function showItemsTable(Request $request)
    {
        // 削除されていない商品を取得
        $items = Item::where('is_deleted', '=', 1)->get();

        return view('item.index', [
            'items' => $items,
            'types' => $this->types,
        ]);
    }
}
