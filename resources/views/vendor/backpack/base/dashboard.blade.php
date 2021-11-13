@extends(backpack_view('blank'))

@php
	$widgets['before_content'][] = [
		'type' => 'jumbotron',
		'heading' => trans('backpack::base.welcome'),
		'content' => trans('backpack::base.use_sidebar'),
		'button_link' => backpack_url('logout'),
		'button_text' => trans('backpack::base.logout'),
	];
@endphp

@section('content')
<a class="btn btn-primary" href="{{ config('app.url') }}/sync" target="_blank">
	{{ trans('messages.ldap_sync') }}
</a>
@endsection