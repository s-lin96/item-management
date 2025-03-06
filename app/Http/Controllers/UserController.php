<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
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

        // バリデーションルール
        $this->validateRules = [
            'name' => ['bail', 'required', 'string', 'max:100'],
            'email' => ['bail', 'required', 'email', 'max:255'],
            'is_admin' => ['bail', 'required', Rule::in([0,1])],
        ];

        $this->validateMessages = [
            'name.required' => ':attribute は必須項目です。',
            'name.string' => ':attribute は文字列で入力してください。',
            'name.max' => ':attribute は最大 :max 文字です。',
            'email.required' => ':attribute は必須項目です。',
            'email.email' => ':attribute は@ドメインまで含んだ形式で入力してください。',
            'email.max' => ':attribute は最大 :max 文字です。',
            'email.required' => ':attribute は必須項目です。',
            'is_admin.required' => ':attribute は必須項目です。'
        ];

        $this->attributes = [
            'name' => '名前',
            'email' => 'メールアドレス',
            'is_admin' => '権限',
        ];
    }

    /**
     * ユーザーアカウント一覧を表示（削除済み非表示）
     * 
     * @param $request
     * 
     * @return $response
     */
    public function index(Request $request)
    {
        // 削除されていないユーザーを取得
        $users = User::where('is_deleted', '=', 1)->get();

        return view('user.index', compact('users'));
    }
}
