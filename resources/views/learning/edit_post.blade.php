@if (auth()->id() === $item->user_id || auth()->user()->hasAnyRole(['teacher', 'admin']))
<button type="button" class="btn btn-sm btn-warning" onclick="editPost({{ $item->id }})">
    Edit
</button>
@endif