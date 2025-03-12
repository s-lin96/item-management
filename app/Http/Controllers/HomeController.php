<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    protected $types;
    protected $units;
    protected $validateRules;
    protected $validateMessages;
    protected $attributes;

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

        // バリデーションルール
        $this->validateRules = [
            'keyword' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', Rule::in(array_keys($this->types))],
            'stockStatus' => ['nullable', Rule::in(['lowStock', 'insufficientStock'])],
        ];

        // バリデーションメッセージ
        $this->validateMessages = [
            'keyword.string' => ':attributes は文字列で入力してください。',
            'keyword.max' => ':attributes は最大 :max 文字までです。',
            'type.in' => ':attributes はプルダウンから選択してください。',
            'stockStatus.in' => ':attributes はプルダウンから選択してください。',
        ];

        // 属性
        $this->attributes = [
            'keyword' => 'キーワード',
            'type' => '商品種別',
            'stockStatus' => '在庫状況',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 在庫不足の商品を取得
        $insufficientStockCount = Item::where('stock_status', '=', 3)->count();
        $lowStockCount = Item::where('stock_status', '=', 2)->count();

        // 削除済み商品レコードを取得
        $deletedItems = Item::where('is_deleted', '=', 0)->get();

        return view('home', compact('insufficientStockCount', 'lowStockCount', 'deletedItems'));
    }

    /**
     * 商品一覧を表示(管理者・一般ユーザー共用)
     *
     * @param $request
     * @return $response
     */
    public function showItemsTable(Request $request)
    {
        // 商品一覧に戻ったときに検索条件セッションをクリア
        session()->forget(['searchKeyword', 'searchType', 'searchStockStatus']);

        // 削除されていない商品を取得
        $items = Item::where('is_deleted', '=', 1)->paginate(12);
        // 種別リストをセット
        $types = $this->types;

        return view('item.index', compact('items', 'types'));
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
        // バリデーションチェック実施
        $request->validate($this->validateRules, $this->validateMessages, $this->attributes);
        
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

        // 在庫状況フィルター(在庫状況が選択されていれば適用)
        if($request->filled('stockStatus')){
            if($request->input('stockStatus') === 'lowStock'){
                $query->where('stock_status', '=', 2);
            }elseif($request->input('stockStatus') === 'insufficientStock'){
                $query->where('stock_status', '=', 3);
            }
        }

        // 検索結果を取得
        $items = $query->paginate(12);
        // 種別リストをセット
        $types = $this->types;

        // セッションに検索条件を保存
        session([
            'searchKeyword' => $cleanedKeyword,
            'searchType' => $request->input('type'),
            'searchStockStatus' => $request->input('stockStatus')
        ]);
        
        return view('item.index', compact('items', 'types', 'cleanedKeyword'));
    }
}
