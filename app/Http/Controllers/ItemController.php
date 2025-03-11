<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class ItemController extends Controller
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
        // dd($this->types, $this->units);
        // バリデーションルール
        $this->validateRules = [
            'type' => ['bail', 'required', Rule::in(array_keys($this->types))],
            'name' => ['bail', 'required', 'string', 'max:100'],
            'stock' => ['bail', 'required', 'numeric', 'digits_between:1,4'],
            'unit' => ['bail', 'required', Rule::in(array_keys($this->units))],
            'safe_stock' => ['bail', 'required', 'numeric', 'digits_between:1,3'],
            'detail' => ['bail', 'required', 'string', 'max:500'],
            'recordType' => ['bail', 'required', 'string'],
            'quantity' => ['bail', 'required', 'numeric', 'digits_between:1,4']
        ];

        // バリデーションメッセージ
        $this->validateMessages = [
            'type.required' => ':attribute は必須項目です。',
            'name.required' => ':attribute は必須項目です。',
            'name.string' => ':attribute は文字列で入力してください。',
            'name.max' => ':attribute は最大 :max 文字です。',
            'stock.required' => ':attribute は必須項目です。',
            'stock.numeric' => ':attribute は 数字で入力してください。',
            'stock.digits_between' => ':attribute は :min ～ :max 桁で入力してください。',
            'unit.required' => ':attribute は必須項目です。',
            'safe_stock.required' => ':attribute は必須項目です。',
            'safe_stock.numeric' => ':attribute は数字で入力してください。',
            'safe_stock.digits_between' => ':attribute は :min ～ :max 桁で入力してください。',
            'detail.required' => ':attribute は必須項目です。',
            'detail.string' => ':attribute は文字列で入力してください。',
            'detail.max' => ':attribute は最大 :max 文字です。',
            'recordType.required' => ':attribute は必須項目です。',
            'recordType.string' => ':attribute は文字列で入力してください。',
            'quantity.required' => ':attribute は必須項目です。',
            'quantity.numeric' => ':attribute は数字で入力してください。',
            'quantity.digits_between' => ':attribute は :min ～ :max 桁で入力してください。',
        ];

        // 属性
        $this->attributes = [
            'type' => '種別',
            'name' => '名前',
            'stock' => '在庫数',
            'unit' => '単位',
            'safe_stock' => '安全在庫数',
            'detail' => '詳細',
            'recordType' => '入庫 / 出庫',
            'quantity' => '数量',
        ];
    }

    /**
     * 商品一覧を表示（管理者向け / 削除済み非表示）
     * 
     * @param $request
     * 
     * @return $response
     */
    public function index(Request $request)
    {
        // 商品一覧に戻ったときに検索条件セッションをクリア
        session()->forget(['searchKeyword', 'searchType', 'searchStockStatus']);

        // 削除されていない商品を取得
        $items = Item::where('is_deleted', '=', 1)->paginate(12);
        // 種別リストをセット
        $types = $this->types;

        return view('item.admin.index', compact('items', 'types'));
    }

    /**
     * 商品一覧を表示（管理者向け / 削除済み表示）
     * 
     * @param $request
     * 
     * @return $response
     */
    public function showDeleted(Request $request)
    {
        // 全ての商品を取得
        $items = Item::paginate(12);
        // 種別リストをセット
        $types = $this->types;

        return view('item.admin.index', compact('items', 'types'));
    }

    /**
     * 商品登録フォームを表示
     * 
     * @param $request
     * 
     * @return $response
     */
    public function create(Request $request)
    {
        return view('item.admin.add');
    }

    /**
     * 商品を登録
     * 
     * @param $request
     * 
     * @return $response
     */
    public function store(Request $request)
    {
        // バリデーションルールから不要なチェック項目を破棄
        $validateRules = $this->validateRules;
        $removeKeys = ['recordType', 'quantity'];
        foreach($removeKeys as $key){
            unset($validateRules[$key]);
        }
        // バリデーションチェック実施
        $validatedData = $request->validate($validateRules, $this->validateMessages, $this->attributes);

        // 【処理を再検討する！】そもそも安定在庫数より大きい場合は在庫状況は必ず“十分”になる？
        // 在庫数が安定在庫数より小さければ
        if($validatedData['stock'] < $validatedData['safe_stock']){
            // 商品登録フォームへリダイレクト
            return back()
                ->withInput()
                ->withErrors([
                    'stock' => '在庫数は安定在庫数より多くなければなりません。'
                ]);
        }

        // 新しい在庫状況を取得
        if($validatedData['stock'] >= $validatedData['safe_stock']){
            $newStockStatus = 1;
        }
        elseif($validatedData['stock'] >= $validatedData['safe_stock'] * 0.7 && $validatedData['stock'] < $validatedData['safe_stock']){
            $newStockStatus = 2;
        }
        else{
            $newStockStatus = 3;
        }

        // DBに新規レコードを追加
        // カラム → バリデーション済み入力値をセット
        Item::create([
            'user_id' => Auth::user()->id,
            'type' => $validatedData['type'],
            'name' => $validatedData['name'],
            'stock' => $validatedData['stock'],
            'unit' => $validatedData['unit'],
            'safe_stock' => $validatedData['safe_stock'],
            'stock_status' => $newStockStatus,
            'detail' => $validatedData['detail'],
        ]);
        // 商品一覧（管理者向け）へリダイレクト
        return redirect()->route('items.table')->with('success', '商品が正常に登録されました。');
    }

    /**
     * 商品編集フォームを表示
     * 
     * @param $id
     * 
     * @return $response
     */
    public function showItemEdit($id)
    {
        // idから商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // 該当するidの商品レコードがなかったら
        if(!$item){
            return redirect()->route('items.table')
                ->with('failure', '商品が見つかりませんでした。');
        }

        // 商品編集フォームを表示
        return view('item.admin.edit-detail', compact('item'));
    }

    /**
     * 商品の編集内容を更新
     * 
     * @param $request
     * @param $id
     * 
     * @return $response
     */
    public function updateItem(Request $request, $id)
    {
        // idから更新対象の商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // バリデーションルールから不要チェック項目を破棄
        $validateRules = $this->validateRules;
        $removeKeys = ['stock', 'recordType', 'quantity'];
        foreach($removeKeys as $key){
            unset($validateRules[$key]);
        }
        // バリデーションチェックを実施
        $validatedData = $request->validate($validateRules, $this->validateMessages, $this->attributes);

        // 新しい在庫状況を取得
        if($item->stock >= $validatedData['safe_stock']){
            $newStockStatus = 1;
        }
        elseif($item->stock >= $validatedData['safe_stock'] * 0.7 && $item->stock < $validatedData['safe_stock']){
            $newStockStatus = 2;
        }
        else{
            $newStockStatus = 3;
        }

        // カラム名 -> バリデーション済み入力値をセット
        $item->user_id = Auth::id(); 
        $item->type = $validatedData['type'];
        $item->name = $validatedData['name']; 
        $item->safe_stock = $validatedData['safe_stock'];
        $item->stock_status = $newStockStatus;
        $item->unit = $validatedData['unit'];
        $item->detail = $validatedData['detail'];
        // 変更を保存
        $item->save();

        // 商品一覧（管理者向け）へリダイレクト
        return redirect()->route('items.table')
            ->with('success', '商品が正常に更新されました。');
    }

    /**
     * 商品を削除(論理削除)
     * 
     * @param $id
     * 
     * @return $response
     */
    public function delete($id)
    {
        // idから削除対象の商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // 削除フラグを更新
        $item->user_id = Auth::id();
        $item->is_deleted = 0;
        // 変更を保存
        $item->save();

        // 商品一覧(管理者向け)へリダイレクト
        return redirect()->route('items.table')
            ->with('success', '商品が正常に削除されました。');
    }

    /**
     * 削除された商品を復元
     * 
     * @param $id
     * 
     * @return $response
     */
    public function restore($id)
    {
        // idから復元対象の商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // 削除フラグを無効
        $item->user_id = Auth::id();
        $item->is_deleted = 1;
        // 変更を保存
        $item->save();

        // 商品一覧(管理者向け)へリダイレクト
        return redirect()->route('items.table')
            ->with('success', '商品が正常に復元されました。');
    }

    /**
     * 入出庫記録フォームを表示
     * 
     * @param $id
     * 
     * @return $response
     */
    public function showStockRecord($id)
    {
        // idから商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // 該当するidの商品レコードがなかったら
        if(!$item){
            return redirect()->route('items.table')
                ->with('failure', '商品が見つかりませんでした。');
        }

        return view('item.admin.record-stock', compact('item'));
    }

    /**
     * 入出庫を記録
     * 
     * @param $request
     * @param $id
     * 
     * @return $response
     */
    public function updateStock(Request $request, $id)
    {
        // idから記録対象の商品レコードを取得
        $item = Item::where('id', '=', $id)->first();

        // バリデーションルールから不要なチェック項目を破棄
        $validateRules = $this->validateRules;
        $removeKeys = ['type', 'name', 'unit', 'stock', 'safe_stock', 'detail'];
        foreach($removeKeys as $key){
            unset($validateRules[$key]);
        }
        // バリデーションチェックを実施
        $validatedData = $request->validate($validateRules, $this->validateMessages, $this->attributes);

        // 在庫数の計算
        if($validatedData['recordType'] === 'incoming'){
            // 入庫ならプラス
            $newStock = $item->stock + $validatedData['quantity'];
        }elseif($validatedData['recordType'] === 'outgoing'){
            // 現在庫数が足りるかチェック
            if($item->stock < $validatedData['quantity']){
                return back()
                ->withInput()
                ->withErrors([
                    'quantity' => '在庫が足りません。'
                ]);
            }else{
            // 出庫ならマイナス
            $newStock = $item->stock - $validatedData['quantity'];
            }
        }

        // 新しい在庫状況を取得
        if($newStock >= $item->safe_stock){
            $newStockStatus = 1; // 十分
        }
        elseif($newStock >= $item->safe_stock * 0.7 && $newStock < $item->safe_stock){
            $newStockStatus = 2; // 少なめ
        }
        else{
            $newStockStatus = 3; // 不足
        }

        // DBに変更を保存
        $item->user_id = Auth::id();
        $item->stock = $newStock;
        $item->stock_status = $newStockStatus;
        $item->save();

        return redirect()->route('items.table')->with('success', '入出庫が正常に記録されました');
    }

    /**
     * 商品を検索(削除済みを含む)
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
        $query = Item::query();

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
        
        return view('item.admin.index', compact('items', 'types', 'cleanedKeyword'));
    }
}