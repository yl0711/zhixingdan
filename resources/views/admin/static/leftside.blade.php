<div class="slide-l fl">
@if (isset($admin_user_authority) && count($admin_user_authority))
	<ul>
	@foreach ($admin_user_authority as $item)
		<li class="slide-item">
			<i></i>
			<h3>{{ $item['aname'] }}</h3>
			<ol>
		@foreach ($item['master'] as $item1)
				<li><a href="{{ $item1['url'] }}" >┣ {{ $item1['aname'] }}</a></li>
		@endforeach
			</ol>
		</li>
	@endforeach
	</ul>
@endif
</div>
