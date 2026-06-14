@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Подтверждение email') }}</div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Новая ссылка для подтверждения отправлена на ваш email.') }}
                        </div>
                    @endif
                    <p>{{ __('Для завершения регистрации, пожалуйста, подтвердите свой email, перейдя по ссылке в письме.') }}</p>
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Отправить повторно') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection