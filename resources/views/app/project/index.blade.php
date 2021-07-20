@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant')])

@section('content')

<div id="merchant-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Find Me A Merchant</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 position-relative">
                    <form>
                        <input type="text" name="search" class="searchbar" placeholder="Search Your Preferences">
                        <button type="submit" class="searchicon"><i class="fa fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="merchant-category">
    <div class="container">
        <div class="d-flex px-3">
            <span>Top Search Categories</span>
            <ul>
                <li class="active">Awning</li>
                <li class="active">Partition</li>
                <li>Wall Drilling & Mounting</li>
                <li>Flooring Installation</li>
                <li>Glasswork</li>
            </ul>
        </div>
    </div>
</div>

<div id="merchant-2">
    <div class="container">
        <div class="d-flex">
            <div class="sidebar">
                <ul class="service">
                    <li class="title">Related Services</li>
                    <li>Service 1</li>
                    <li>Service 2</li>
                    <li>Service 3</li>
                    <li>Service 4</li>
                    <li>Service 5</li>
                    <li class="end">View All</li>
                </ul>
                <ul class="range">
                    <li class="title">Price Range</li>
                    <li>
                        <form action="">
                            <input type="text" class="range" placeholder="Min"><span> - </span><input type="text" class="range" placeholder="Max">
                            <button type="submit"><i class="fa fa-arrow-right"></i></button>
                        </form>
                    </li>
                </ul>
                <ul class="radio">
                    <form action="">
                        <li>
                            <input type="radio" id="rm1000" name="price" value="1000">
                            <label for="rm1000">
                                < RM 1,000 </label> </li> <li>
                                    <input type="radio" id="rm5000" name="price" value="5000">
                                    <label for="rm5000">
                                        < RM 5,000 </label> </li> <li>
                                            <input type="radio" id="rm10000" name="price" value="10000">
                                            <label for="rm10000">
                                                < RM 10,000 </label> </li> <li>
                                                    <input type="radio" id="rm15000" name="price" value="15000">
                                                    <label for="rm15000">
                                                        < RM 15,000 </label> </li> <li>
                                                            <input type="radio" id="rm20000" name="price" value="20000">
                                                            <label for="rm20000">
                                                                < RM 20,000 </label> </li> </form> </ul> <ul class="radio">
                        <li class="title">Locations</li>
                        <form action="">
                            <li>
                                <input type="radio" id="kualalumpur" name="location" value="kualalumpur">
                                <label for="kualalumpur">Kuala Lumpur</label>
                            </li>
                            <li>
                                <input type="radio" id="selangor" name="location" value="selangor">
                                <label for="selangor">Selangor</label>
                            </li>
                            <li>
                                <input type="radio" id="melaka" name="location" value="melaka">
                                <label for="melaka">Melaka</label>
                            </li>
                            <li>
                                <input type="radio" id="perak" name="location" value="perak">
                                <label for="perak">Perak</label>
                            </li>
                            <li>
                                <input type="radio" id="johor" name="location" value="johor">
                                <label for="johor">Johor</label>
                            </li>
                        </form>
                </ul>
                <ul class="radio rating">
                    <li class="title">Rating</li>
                    <form action="">
                        <li>
                            <input type="radio" id="star1" name="rating" value="star1">
                            <label for="star1">
                                <i class="fa fa-star"></i>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="star2" name="rating" value="star2">
                            <label for="star2">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star pl-2"></i>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="star3" name="rating" value="star3">
                            <label for="star3">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="star4" name="rating" value="star4">
                            <label for="star4">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                            </label>
                        </li>
                        <li>
                            <input type="radio" id="star5" name="rating" value="star5">
                            <label for="star5">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                                <i class="fa fa-star pl-2"></i>
                            </label>
                        </li>
                    </form>
                </ul>
            </div>
            <div class="gap"></div>
            <div class="content">
                <div class="container">
                    <h2>Construction</h2>
                    <div class="search-filter-result">
                        <span>50 items found for "Construction"</span>
                        <button id="compare" name="compare" class="btn btn-orange ml-auto">Compare Merchant</button>
                    </div>
                    <div class="search-filter-result compare d-none">
                        <span>Choose 2 contractor to compare now</span>
                        <span class="ml-auto mr-2">Selected : 2</span>
                        <a href="#" class="btn btn-black">View Result</a>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row justify-content-center">
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card ad-boost">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_1" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <img src="{{ asset('storage/adboost.png') }}" class="ad-boost-img">
                                                <p class="merchant-title">Magna Klasik Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Magna Klasik</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,200</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare d-none">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/2.jpg') }}" alt="merchant_2" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Zie Global Trading (M) Sdn Bhd</p>
                                                <p class="merchant-subtitle">Zie Global</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM10,000</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/4.png') }}" alt="merchant_4" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Sansel Business Solutions Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Sansel Business</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM2,800</span>
                                                <span class="merchant-footer-right">Selangor</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/5.jpg') }}" alt="merchant_5" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">ARTSYSTEM (M) SDN. BHD.</p>
                                                <p class="merchant-subtitle">ARTSYSTEM</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,500</span>
                                                <span class="merchant-footer-right">Selangor</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_1" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Magna Klasik Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Magna Klasik</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,200</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/4.png') }}" alt="merchant_4" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Sansel Business Solutions Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Sansel Business</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM10,000</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/5.jpg') }}" alt="merchant_5" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">ARTSYSTEM (M) SDN. BHD.</p>
                                                <p class="merchant-subtitle">ARTSYSTEM</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM2,300</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_1" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Magna Klasik Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Magna Klasik</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,500</span>
                                                <span class="merchant-footer-right">Selangor</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/2.jpg') }}" alt="merchant_2" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Zie Global Trading (M) Sdn Bhd</p>
                                                <p class="merchant-subtitle">Zie Global</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,200</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/4.png') }}" alt="merchant_4" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Sansel Business Solutions Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Sansel Business</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM2,300</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/5.jpg') }}" alt="merchant_5" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">ARTSYSTEM (M) SDN. BHD.</p>
                                                <p class="merchant-subtitle">ARTSYSTEM</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM1,200</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card">
                                        <a href="{{ route('app.project.show') }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ asset('storage/assets/home/1.jpg') }}" alt="merchant_1" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                <p class="merchant-title">Magna Klasik Sdn. Bhd.</p>
                                                <p class="merchant-subtitle">Magna Klasik</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From RM10,000</span>
                                                <span class="merchant-footer-right">Kuala Lumpur</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare" style="display: none;">Add to Compare</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="pagination">
                                <li class="page-item ml-auto"><a class="page-link arrow" href="#">&laquo;</a></li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item mr-auto"><a class="page-link arrow" href="#">&raquo;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection