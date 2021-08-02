@extends('layouts.master', ['title' => __('modules.checkout'), 'guest_view' => true, 'body' => 'enduser'])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    @include('components.alert')

    <form action="{{ route('checkout.recurring') }}" method="post" enctype="multipart/form-data" role="form">
        @csrf

        <div class="row mt-3">
            <div class="col-12">
                <div class="card card-body p-4 shadow">
                    <h5 class="card-title">{{ __('labels.order_summary') }}</h5>
                    <div class="table-responsive my-3">
                        <table class="table" role="presentation">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%;">{{ __('#') }}</th>
                                    <th scope="col" style="width: 60%;">{{ trans_choice('labels.item', 2) }}</th>
                                    <th scope="col" style="width: 10%; text-align: center;">{{ __('labels.quantity') }}</th>
                                    <th scope="col" style="width: 20%; text-align: right;">{{ __('labels.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carts as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <span class="font-weight-bold">{{ $item['name'] }}</span>
                                        <br>
                                        <p class="text-muted">{{ $item['description'] }}</p>
                                    </td>
                                    <td class="text-center"><span class="form-control form-control-sm">{{ $item['quantity'] }}</span></td>
                                    <td class="text-right">{{ $item['currency'] .' '. $item['price'] }}</td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="text-center">{{ __('messages.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <h4 class="font-weight-bold text-success">
                        {{ __('labels.grand_total') }}
                        <span class="float-right">
                            {{ $cart_currency .' '. number_format($sub_total, 2, '.', '') }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card card-body p-4 shadow">
                    <h5 class="card-title">{{ __('messages.fill_in_recurring_form') }}</h5>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name_as_id') }} <span class="text-red">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="nric" class="col-form-label">{{ __('labels.nric') }} <span class="text-red">*</span></label>
                                <input type="text" name="nric" id="nric" value="{{ old('nric') }}" class="form-control @error('nric') is-invalid @enderror" placeholder="Please enter without '-'">
                                @error('nric')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-red">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Eg: 60123456789">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1') }}">
                                @error('address_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="address_2" class="col-form-label">{{ __('labels.address_2') }}</label>
                                <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2') }}">
                                @error('address_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="country" class="col-form-label">{{ trans_choice('labels.country', 1) }} <span class="text-red">*</span></label>
                                <select name="country" id="country" class="form-control select2 @error('country') is-invalid @enderror country-state-filter">
                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country') == $country->id || $country->set_default ? 'selected' : null }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-red">*</span></label>
                                <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}">
                                @error('postcode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-red">*</span></label>
                                <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state', 0) }}"
                                    data-country-state-route="{{ route('data.countries.country-states', ['__REPLACE__']) }}">
                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country_state', 1))]) }} ---</option>
                                </select>
                                @error('country_state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-red">*</span></label>
                                <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', 0) }}" data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.city', 1))]) }} ---</option>
                                </select>
                                @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @if (count($carts) > 0)
                <button type="submit" class="btn btn-orange btn-lg mt-3 float-right ml-2 shadow" name="pay">
                    {{ strtoupper(__('labels.pay_now')) }}
                </button>
                <button type="submit" class="btn btn-black btn-lg mt-3 float-right mr-2 shadow" name="cancel">
                    {{ strtoupper(__('labels.cancel')) }}
                </button>
                @endif
            </div>
        </div>
    </form>

</div>

@endsection