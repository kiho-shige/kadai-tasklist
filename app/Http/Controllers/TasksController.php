<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;

class TasksController extends Controller
{ 
    // getでtasks/にアクセスされた場合の「一覧表示処理」
  public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            // ユーザの投稿の一覧を作成日時の降順で取得
            //このユーザの投稿のみ取得
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            "user_id" =>$request->user_id,
            "status" => $request->status,
            'content' => $request->content,
        ]);
        
        // 前のURLへリダイレクトさせる
        //return back();
        
        /* メッセージを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();  */

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
        ]);
        }
        
        // 前のURLへリダイレクトさせる
        return redirect('/')
            ->with('View Failed'); 

    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = \App\Models\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を編集
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
        ]);
        } 

        // 前のURLへリダイレクトさせる
        return redirect('/')
            ->with('Edit Failed'); 
    
    
         /*メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);*/
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $task = \App\Models\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/')
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return redirect('/')
            ->with('Delete Failed');
    }
}