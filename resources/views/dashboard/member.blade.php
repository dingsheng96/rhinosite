@extends('layouts.master', ['title' => __('modules.dashboard'), 'guest_view' => true])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('modules.dashboard') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="user-directory" class="d-xl-none d-block">
    <div class="container">
        <ul class="account">
            <li class="title">
                <a data-toggle="collapse" data-target="#userdirectory" aria-expanded="false" aria-controls="userdirectory" class="collapsed">
                    {{ __('user_dashboard_sidebar_title') }}
                </a>
            </li>
            <div class="collapse" id="userdirectory">
                <li class="active"><a href="profile.html">My Profile</a></li>
                <li><a href="wishlist.html">My Wishlist</a></li>
                <li><a href="changepassword.html">Change Password</a></li>
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        {{ __('labels.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </div>
        </ul>
    </div>
</div>

@endsection