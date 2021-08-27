@section('title', 'Login')
<div class="vertical-align-wrap">
	<div class="vertical-align-middle auth-main">
		<div class="auth-box">
            <div class="top">
                {{-- <img src="{{url('/')}}/assets/img/logo-white.svg" alt="Lucid"> --}}
                <img src="{{get_setting('logo')}}" alt="{{get_setting('company')}}">
            </div>
			<div class="card">
                <div class="header">
                    <p class="lead">{{__('Login to your account')}}</p>
                </div>
                <div class="body">
                    <form class="form-auth-small" method="POST" wire:submit.prevent="login" action="">
                        @if($message)
                       <p class="text-danger">{{$message}}</p>
                        @endif
                        <div class="form-group">
                            <label for="signin-email" class="control-label sr-only">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="signin-email" wire:model="email" placeholder="{{ __('Email') }}">
                            @error('email')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="signin-password" class="control-label sr-only">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="signin-password" wire:model="password" placeholder="{{ __('Password') }}">
                            @error('password')
                            <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group clearfix">
                            <label class="fancy-checkbox element-left">
                                <input type="checkbox">
                                <span>{{__('Remember me')}}</span>
                            </label>								
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('LOGIN') }}</button>
                        {{-- <div class="bottom">
                            <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="#">{{ __('Forgot password?') }}</a></span>
                            <span>{{ __("Don't have an account?") }} <a href="#">{{ __('Register') }}</a></span>
                        </div> --}}
                    </form>
                </div>
            </div>
		</div>
	</div>
</div>