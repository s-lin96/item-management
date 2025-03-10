<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;

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
        $items = Item::where('is_deleted', '=', 1)->paginate(12);
        // 種別リストをセット
        $types = $this->types;

        return view('item.index', compact('items', 'types'));;
    }

    /**
     * 商品詳細を表示(管理者・一般ユーザー共用)
     *
     * @param $id
     * @return $response
     */
    public function showDetail($id)
    {
        $item = Item::where('id', '=', $id)->first();

        // 該当するidの商品レコードがなかったら
        if(!$item){
            return redirect()->route('items.table.readonly')
                ->with('failure', '商品が見つかりませんでした。');
        }

        // 種別 & 単位リストをセット
        $types = $this->types;
        $units = $this->units;

        return view('item.detail', compact('item', 'types', 'units'));
    }

    /**
     * 商品を検索
     * 
     * @param $request
     * 
     * @return $response
     */
    public function searchItem(Request $request)
    {
        // 初期化：置換後のキーワードを入れる変数
        $cleanedKeyword = '';
        // 削除されていない商品の中から
        $query = Item::where('is_deleted', '=', 1);

        // キーワード検索(キーワードがあれば適用)
        if($request->filled('keyword')){
            // 記号や絵文字を「半角スペース」に置換
            $cleanedKeyword = preg_replace('/[^\p{L}\p{N}\s]/u', '', $request->input('keyword'));
            $query->where(function (Builder $query) use ($cleanedKeyword) {
                $query->where('name', 'LIKE', "%{$cleanedKeyword}%")
                    ->orWhere('detail', 'LIKE', "%$cleanedKeyword%");
            });
        }

        // 種別検索(種別が選択されていれば適用)
        if($request->filled('type')){
            $query->where('type', '=', $request->input('type'));
        }

        // 検索結果を取得
        $items = $query->paginate(12);
        // 種別リストをセット
        $types = $this->types;

        // セッションに検索条件を保存
        session([
            'searchKeyword' => $cleanedKeyword,
            'searchType' => $request->input('type'),
        ]);
        
        return view('item.index', compact('items', 'types', 'cleanedKeyword'));
    }
}
