@extends('mail.app')

@section('content')

<table class="w-100">
    <thead class="mail-header">
        <tr>
            <th>
                <img src="{{ asset('storage/logo.png') }}" alt="logo" class="mail-header-img">
            </th>
        </tr>
    </thead>
    <tbody class="mail-body text-center">
        <tr>
            <td>
                <table class="w-75 mx-auto">
                    <thead>
                        <tr>
                            <th class="mail-title">{{ __('Welcome') }}</th>
                        </tr>
                    </thead>
                    <tr>
                        <td class="mail-text">We're excited to have you get started. First, you need to verify your account. Just press the button below.</td>
                    </tr>
                    <tr>
                        <td><a role="button" href="{{ $verify_route }}" class="mail-button">Verify Account</a></td>
                    </tr>
                    <tr>
                        <td>If that doesn't work, copy and paste the following link to your browser:</td>
                    </tr>
                    <tr>
                        <td>
                            {{ $verify_route }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

@endsection