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

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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
        $users = User::where('is_deleted', '=', 1)->paginate(12);

        return view('user.index', compact('users'));
    }

    /**
     * ユーザーアカウント一覧を表示（削除済み表示）
     * 
     * @param $request
     * 
     * @return $response
     */
    public function showDeleted(Request $request)
    {
        // 削除済みを含むすべてのユーザーレコードを取得
        $users = User::paginate(12);

        return view('user.index', compact('users'));
    }
    
    /**
     * アカウント編集フォームを表示
     * 
     * @param $id
     * 
     * @return $response
     */
    public function showUserEdit($id)
    {
        // idから更新対象のユーザーレコードを取得
        $user = User::where('id', '=', $id)->first();

        // 対象のユーザーレコードがなかったら
        if(!$user){
            return redirect()->route('users.table')
                ->with('failure', 'ユーザーが見つかりませんでした。');
        }

        // 対象ユーザーレコードが削除されていたら
        if($user->is_deleted === 0){
            return redirect()->route('users.table')
                ->with('failure', '削除されたアカウントです。操作を行うにはアカウントを復元する必要があります。');
        }

        return view('user.edit-user', compact('user'));
    }

    /**
     * アカウント情報を更新
     * 
     * @param $request
     * @param $id
     * 
     * @return $response
     */
    public function updateUser(Request $request, $id)
    {
        // idから更新対象のユーザーレコードを取得
        $user = User::where('id', '=', $id)->first();

        // バリデーションルール
        $validateRules = [
            'name' => ['bail', 'required', 'string', 'max:100'],
            'email' => ['bail', 'required', 'email', 'max:255', Rule::unique('users','email')->ignore($user->id)],
            'isAdmin' => ['bail', 'required', Rule::in([0,1])]
        ];

        // バリデーションメッセージ
        $validateMessages = [
            'name.required' => ':attribute は必須項目です。',
            'name.string' => ':attribute は文字列で入力してください。',
            'name.max' => ':attribute は最大 :max 文字です。',
            'email.required' => ':attribute は必須項目です。',
            'email.email' => ':attribute は@ドメインまで含んだ形式で入力してください。',
            'email.max' => ':attribute は最大 :max 文字です。',
            'email.unique' => 'この :attribute はすでに登録されています。',
            'isAdmin.required' => ':attribute は必須項目です。'
        ];

        // 属性
        $attributes = [
            'name' => '名前',
            'email' => 'メールアドレス',
            'is_admin' => '権限',
        ];
    
        // バリデーションチェックを実施
        $validatedData = $request->validate($validateRules, $validateMessages, $attributes);

        // カラム名 -> バリデーション済み入力値をセット
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->is_admin = intval($validatedData['isAdmin']); // 数値に変換
        // 変更を保存
        $user->save();

        // ユーザー管理画面へリダイレクト
        return redirect()->route('users.table')
            ->with('success', 'アカウント情報が正常に更新されました。');
    }

    /**
     * ユーザーアカウントを削除
     * 
     * @param $id
     * 
     * @return $response
     */
    public function delete($id)
    {
        // idから対象ユーザーのレコードを取得
        $user = User::where('id', '=', $id)->first();

        // 削除フラグを更新して保存
        $user->is_deleted = 0;
        $user->save();

        return redirect()->route('users.table')
            ->with('success', 'ユーザーアカウントが正常に削除されました。');
    }

    /**
     * 削除されたユーザーアカウントを復元
     * 
     * @param $id
     * 
     * @return $response
     */
    public function restore($id)
    {
        // idから復元対象のユーザーレコードを取得
        $user = User::where('id', '=', $id)->first();

        // 削除フラグを無効にして保存
        $user->is_deleted = 1;
        $user->save();

        return redirect()->route('users.table')
            ->with('success', 'ユーザーアカウントが正常に復元されました。');
    }
}
