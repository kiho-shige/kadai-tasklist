<div class="mt-4">
    @if (isset($tasks))
        <ul class="list-none">
            @foreach ($tasks as $task)
                <li class="flex items-start gap-x-2 mb-4">
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザ詳細ページへのリンク
                            <a class="link link-hover text-info" href="{{ route('users.show', $task->user->id) }}">{{ $task->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $task->created_at }}</span>--}}
                        </div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e( $task->status)) !!}</p>
                            <p class="mb-0">{!! nl2br(e( $task->content)) !!}</p>
                        </div>
                        
                        {{-- <div>
                             編集
                            @if (Auth::id() == $task->user_id)
                                <a class="btn btn-outline" href="{{ route('tasks.edit', $task->id) }}">このメッセージを編集</a>
                            @endif
                        </div>--}}
                        
                        <div>
                            @if (Auth::id() == $task->user_id)
                                {{-- 投稿削除ボタンのフォーム --}}
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm normal-case" 
                                        onclick="return confirm('Delete id = {{ $task->id }} ?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $tasks->links() }}
    @endif
</div>