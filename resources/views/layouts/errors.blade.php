@if (count($errors) > 0)
<div class="form-group">
	<div class="alert alert-danger">
		<ul>
			@foreach($errors->all() as $error)
			<li>
				{{$error}}
			</li>
			@endforeach
		</ul>
	</div>
</div>
@endif
@if(session('success'))
	<div class="alert alert-success">
		{{session('success')}}
	</div>
@endif
@if(session('error'))
	<div class="alert alert-danger">
		{{session('error')}}
	</div>
@endif