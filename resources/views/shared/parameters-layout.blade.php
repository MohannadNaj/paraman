<!DOCTYPE html>
<html lang="en">
<head>
    @include('parameters::shared.parameters-head')
</head>
<body>
    <div class="container-fluid" id="app">
      @include('parameters::shared.parameters-navbar')
    	@yield('content')
      <!-- Helpers -->
      <notifications></notifications>
      <modal ref="modal" id="modal"></modal>
      <dropzone-upload ref="dropzone-modal" _target="parameters/addPhoto" _update_target="parameters/updatePhoto"></dropzone-upload>
    </div>
<script src="{{asset('vendor/parameters/js/app.js') . '?v='.Illuminate\Support\Str::random()}}"></script>
</body>
</html>