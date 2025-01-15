<a href="#">
  <strong id="following" class="stat">
    {{-- 关注的人 --}}
    {{ count($user->followings) }}
  </strong>
  关注
</a>
<a href="#">
  <strong id="followers" class="stat">
    {{-- 粉丝 --}}
    {{ count($user->followers) }}
  </strong>
  粉丝
</a>
<a href="#">
  <strong id="statuses" class="stat">
    {{-- 获取用户发布过的微博数 --}}
    {{ $user->statuses()->count() }}
  </strong>
  微博
</a>
