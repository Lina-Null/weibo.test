<li class="d-flex mt-4 mb-4">
  <a class="flex-shrink-0" href="{{ route('users.show', $user->id )}}">
    <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="me-1 gravatar"/>
  </a>
  <div class="flex-grow-1 ms-3">
    {{--$status->created_at->diffForHumans() 将日期进行友好化处理 --}}
    <h5 class="mt-0 mb-1">{{ $user->name }} <small> / {{ $status->created_at->diffForHumans() }}</small></h5>
    {{ $status->content }}
  </div>
</li>
