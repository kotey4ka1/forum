<form method="POST" action="{{ route('comment.update', $comment->id) }}" class="mt-2 edit-comment-form">
    @csrf
    @method('PUT')
    <div class="mb-2">
        <textarea class="form-control" name="content" rows="3" required>{{ old('content', $comment->content) }}</textarea>
    </div>
    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
    <button type="button" class="btn btn-sm btn-secondary cancel-edit">Отмена</button>
</form>
