
    <div class="mt-4">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
        
             <div class="form-control mt-4">ステータス
                <textarea rows="2" name="status" class="input input-bordered w-full"></textarea>
            </div>
            
            <div class="form-control mt-4">メッセージ
                <textarea rows="2" name="content" class="input input-bordered w-full"></textarea>
            </div>
        
            <button type="submit" class="btn btn-primary btn-block normal-case">Post</button>
        </form>
    </div>