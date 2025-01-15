@can('follow', $user)
  <div class="text-center mt-2 mb-4">
    {{-- 当用户已被关注时，显示的是取消关注的按钮； --}}
    @if (Auth::user()->isFollowing($user->id))
      <form action="{{ route('followers.destroy', $user->id) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-outline-primary">取消关注</button>
      </form>
    {{-- 未被关注时，使用的则是关注按钮。 --}}
      @else
      <form action="{{ route('followers.store', $user->id) }}" method="post">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-sm btn-primary">关注</button>
      </form>
    @endif
  </div>
@endcan
