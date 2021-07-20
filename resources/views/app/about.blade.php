@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.about')])

@section('content')

<div id="about-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>About Rhinosite</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <form>
                        <input type="text" name="search" class="searchbar" placeholder="Search Your Preferences">
                        <button type="submit" class="searchicon"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="about-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-lg-block d-none">
                <img src="{{ asset('storage/assets/about/about-2.PNG') }}" alt="services_2_image" class="res-img px-4">
            </div>
            <div class="col-lg-4">
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
                <div class="row align-items-center mb-4">
                    <div class="col-4">
                        <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_light_bulb" class="about-icon">
                    </div>
                    <div class="col-8">
                        <span class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-lg-none d-block">
                <img src="{{ asset('storage/assets/about/about-2.PNG') }}" alt="services_2_image" class="res-img px-4">
            </div>
        </div>
    </div>
</div>

<div id="about-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-xxl-5 mx-auto">
                <h2>Lorem ipsum dolor sit amet</h2>
                <p class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
            </div>
            <div class="col-8 col-md-5 col-xxl-5 mx-auto">
                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="about_3_image" class="res-img">
            </div>
        </div>
    </div>
</div>

<div id="about-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-8 col-md-5 col-xxl-5 mx-auto d-md-block d-none">
                <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_4_image" class="res-img">
            </div>
            <div class="col-md-6 col-xxl-5 mx-auto">
                <h2>Lorem ipsum dolor sit amet</h2>
                <p class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod</p>
            </div>
            <div class="col-8 col-md-5 col-xxl-5 mx-auto d-md-none d-block">
                <img src="{{ asset('storage/assets/home/6.png') }}" alt="about_4_image" class="res-img">
            </div>
        </div>
    </div>
</div>


@endsection