<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use Illuminate\Validation\Rule;

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
        $this->types = array_keys(config('types.types'));
        $this->units = array_keys(config('units.units'));

        // バリデーションルール
        $this->validateRules = [
            'type' => ['bail', 'required', Rule::in($this->types)],
            'name' => ['bail', 'required', 'string', 'max:100'],
            'stock' => ['bail', 'required', 'numeric', 'digits_between:1,4'],
            'unit' => ['bail', 'required', Rule::in($this->units)],
            'safe_stock' => ['bail', 'required', 'numeric', 'digits_between:1,3'],
            'detail' => ['bail', 'required', 'string', 'max:500']
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
        ];

        // 属性
        $this->attributes = [
            'type' => '種別',
            'name' => '名前',
            'stock' => '在庫数',
            'unit' => '単位',
            'safe_stock' => '安全在庫数',
            'detail' => '詳細',
        ];
    }

    /**
     * 商品一覧を表示
     */
    public function index()
    {
        // 商品一覧取得
        $items = Item::all();

        return view('item.index', compact('items'));
    }

    /**
     * 商品登録フォームを表示
     */
    public function create()
    {
        return view('item.add');
    }

    /**
     * 商品を登録
     */
    public function store(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーションチェック実施
            $validatedData = $request->validate($this->validateRules, $this->validateMessages, $this->attributes);

            // 在庫数が安定在庫数より多いかチェック
            if($validatedData['stock'] <= $validatedData['safe_stock']){
                return redirect()->route('item.create')->withErrors([
                    'stock' => '在庫数は安定在庫数より多くなければなりません。'
                ]);
            }

            // 新しい在庫状況を取得
            if($validatedData['stock'] >= $validatedData['safe_stock']){
                return $newStockStatus = 1;
            }
            elseif($validatedData['stock'] >= $validatedData['safe_stock'] * 0.7 && $validatedData['stock'] < $validatedData['safe_stock']){
                return $newStockStatus = 2;
            }
            else{
                return $newStockStatus = 3;
            }

            // DBに新規レコードを追加
            // 項目セット　→　保存
            Item::create([
                'user_id' => Auth::user()->id,
                'type' => $request->$validatedData['type'],
                'name' => $request->$validatedData['name'],
                'stock' => $request->$validatedData['stock'],
                'unit' => $request->$validatedData['unit'],
                'safe_stock' => $request->$validatedData['safe_stock'],
                'stock_status' => $request->$newStockStatus,
                'detail' => $request->$validatedData['detail'],
            ]);

            return to_route('items.table')
            ->with('success', '商品を登録しました');
        }

        return view('item.add');
    }
}
