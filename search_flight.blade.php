@extends('search_flight.app')
<style>
    .dropdown-menu.custom-select-dropdown.show {
        min-width: 261px !important;
    }

    .dropdown-menu.custom-select-dropdown.show .dropdown-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 4px 14px 4px 10px;
    }

    .custom-select.economy-drop .select-selected {
        text-overflow: ellipsis !important;
        white-space: nowrap;
        width: 100px;
        display: block;
        overflow: hidden;
    }
</style>
@php
    $returnDate = \Carbon\Carbon::now()
        ->addDays(3)
        ->format('Y-m-d');
    $minValue = 0;
    if ($formData['trip'] == 'round') {
        $tripTypeForGrids = 'round-trip-grid';
        $tripTypeTile = 'round-trip-tile';
    } else {
        $tripTypeForGrids = 'oneway-trip-grid';
        $tripTypeTile = 'oneway-trip-tile';
    }
@endphp
@section('head')
@endsection
@section('content')

    <div class="container">
        <section class="first-section">
            <div class="top-header">
                <div class="social-icon">
                    {!! setting_item_with_lang('topbar_left_text') !!}
                </div>
                <div class="contact-detail">
                    <div class="flex-avt for-brdr-right">
                        <i class="fa-solid fa-phone phone-icon" style="color: #337ab7; font-size: 14px"></i>
                        <span class="d-inline-block font-size-14 mr-1" style="color: #337ab7">
                            {{ setting_item('phone_contact') }}
                        </span>
                    </div>
                    <div class="flex-avt pl-2">
                        @if (!Auth::id())
                            <img class="user-img" src="{{ asset('new_changes/images/user1.png') }}">
                            <a class="register-det" href="javascript:;" data-toggle="modal" data-target="#login">Sign in or
                                Register</a>
                        @else
                            <div class="d-flex align-items-center text-white dropdown">
                                <img class="user-img" src="{{ asset('new_changes/images/user1.png') }}">
                                <span class="d-inline-block font-size-14 mr-1 dropdown-nav-link" data-toggle="dropdown"
                                    style="color: #337ab7;margin: 0 0 0 10px;">
                                    {{ __('Hi, :name', ['name' => Auth::user()->getDisplayName()]) }}
                                </span>
                                <ul class="dropdown-menu dropdown-menu-user text-left dropdown" style="z-index: 9999">
                                    @if (Auth::user()->hasPermissionTo('admin_dashboard'))
                                        <li class=""><a href="{{ url('/admin') }}"><i class="fa fa-clock-o"></i>
                                                Dashboard</a></li>
                                    @else
                                        @if (!Auth::user()->hasRole('customer'))
                                            @if (empty(setting_item('wallet_module_disable')))
                                                <li class="credit_amount">
                                                    <a href="{{ route('user.wallet') }}"><i class="fa fa-money"></i>
                                                        {{ __('Credit: :amount', ['amount' => auth()->user()->balance]) }}</a>
                                                </li>
                                            @endif
                                        @endif

                                        @if (is_vendor())
                                            <li class=""><a href="{{ route('vendor.dashboard') }}" class=""><i
                                                        class="icon ion-md-analytics"></i> Agent
                                                    Dashboard</a></li>
                                        @endif
                                        <li class="@if (is_vendor())  @endif">
                                            <a href="{{ route('user.profile.index') }}"><i
                                                    class="icon ion-md-construct"></i>
                                                {{ __('My profile') }}</a>
                                        </li>

                                        <li class=""><a
                                                href="{{ route('user.booking_history', ['groupName' => 'flight']) }}"><i
                                                    class="fa fa-clock-o"></i> {{ __('Booking History') }}</a></li>
                                        @if (Auth::user()->getRoleNameAttribute() != 'Customer')
                                            <li class="">
                                                <a href="{{ route('user.change_password') }}"><i class="fa fa-lock"></i>
                                                    {{ __('Change password') }}</a>
                                            </li>
                                        @endif
                                    @endif
                                    <li class="">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i
                                                class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                                    </li>
                                </ul>
                                @if (!\App\Model\Booking\GuestUser::where('central_user_token', Auth::user()->central_user_token)->get()->isEmpty())
                                    <ul class="dropdown-menu dropdown-menu-user text-left dropdown" style="z-index: 9999">
                                        <li class="">
                                            <a href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i
                                                    class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                                        </li>
                                    </ul>
                                @else
                                    <ul class="dropdown-menu dropdown-menu-user text-left dropdown" style="z-index: 9999;">
                                        @if (empty(setting_item('wallet_module_disable')))
                                            <li class="credit_amount">
                                                <a href="{{ route('user.wallet') }}"><i class="fa fa-money"></i>
                                                    {{ __('Credit: :amount', ['amount' => auth()->user()->balance]) }}</a>
                                            </li>
                                        @endif
                                        @if (Auth::user()->hasRole('administrator'))
                                        @else
                                            @if (is_vendor())
                                                <li class=""><a href="{{ route('vendor.dashboard') }}"
                                                        class=""><i class="icon ion-md-analytics"></i>
                                                        {{ __('Vendor Dashboard') }}</a></li>
                                            @endif
                                            <li class="@if (is_vendor())  @endif">
                                                <a href="{{ route('user.profile.index') }}"><i
                                                        class="icon ion-md-construct"></i> {{ __('My profile') }}</a>
                                            </li>
                                            @if (setting_item('inbox_enable'))
                                                <li class=""><a href="{{ route('user.chat') }}"><i
                                                            class="fa fa-comments"></i> {{ __('Messages') }}</a></li>
                                            @endif
                                            <li class=""><a
                                                    href="{{ route('user.booking_history', ['groupName' => 'flight']) }}"><i
                                                        class="fa fa-clock-o"></i> {{ __('Booking History') }}</a></li>
                                            <li class=""><a href="{{ route('user.change_password') }}"><i
                                                        class="fa fa-lock"></i> {{ __('Change password') }}</a></li>
                                            @if (is_admin())
                                                <li class=""><a href="{{ url('/admin') }}"><i
                                                            class="icon ion-ios-ribbon"></i>
                                                        {{ __('Admin Dashboard') }}</a>
                                                </li>
                                            @endif
                                        @endif
                                        <li class="">
                                            <a href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i
                                                    class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                                        </li>
                                    </ul>
                                @endif
                                <ul class="dropdown-menu dropdown-menu-user text-left dropdown" style="z-index: 9999">
                                    @if (Auth::user()->hasRole('administrator'))
                                    @else
                                        @if (empty(setting_item('wallet_module_disable')))
                                            <li class="credit_amount">
                                                <a href="{{ route('user.wallet') }}"><i class="fa fa-money"></i>
                                                    {{ __('Credit: :amount', ['amount' => auth()->user()->balance]) }}</a>
                                            </li>
                                        @endif
                                        @if (is_vendor())
                                            <li class=""><a href="{{ route('vendor.dashboard') }}" class=""><i
                                                        class="icon ion-md-analytics"></i>
                                                    {{ __('Vendor Dashboard') }}</a></li>
                                        @endif
                                        <li class="@if (is_vendor())  @endif">
                                            <a href="{{ route('user.profile.index') }}"><i
                                                    class="icon ion-md-construct"></i>
                                                {{ __('My profile') }}</a>
                                        </li>
                                        @if (setting_item('inbox_enable'))
                                            <li class=""><a href="{{ route('user.chat') }}"><i
                                                        class="fa fa-comments"></i> {{ __('Messages') }}</a></li>
                                        @endif
                                        <li class=""><a
                                                href="{{ route('user.booking_history', ['groupName' => 'flight']) }}"><i
                                                    class="fa fa-clock-o"></i> {{ __('Booking History') }}</a></li>
                                        <li class=""><a href="{{ route('user.change_password') }}"><i
                                                    class="fa fa-lock"></i> {{ __('Change password') }}</a></li>
                                        @if (is_admin())
                                            <li class=""><a href="{{ url('/admin') }}"><i
                                                        class="icon ion-ios-ribbon"></i> {{ __('Admin Dashboard') }}</a>
                                            </li>
                                        @endif
                                    @endif
                                    <li class="">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i
                                                class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                                    </li>
                                </ul>



                                <form id="logout-form-topbar" action="{{ route('auth.logout') }}" method="POST"
                                    style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

        </section>
    </div>
    <section class="Nav-section nav-scroll">
        <nav class="navbar navbar-expand-md">
            <div class="container">
                <a class="navbar-brand" href="{{ url(app_get_locale(false, '/')) }}">
                    @if ($logo_id = setting_item('logo_id'))
                        <?php $logo = get_file_url($logo_id, 'full'); ?>
                        <img src="{{ $logo }}" alt="{{ setting_item('site_title') }}">
                    @endif
                    <span class="u-header__navbar-brand-text">{{ setting_item_with_lang('logo_text') }}</span>
                </a>
                <button class="navbar-toggler pr-0" type="button" data-toggle="collapse"
                    data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fa-solid fa-bars"></i></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <div class="bravo-menu">
                        <?php generate_menu('primary'); ?>
                    </div>
                </div>
            </div>
        </nav>
    </section>

    <div class="container">
        <div class="row pl-3 pr-3">
            <button class="btn btn-primary accordion1" type="button" data-toggle="collapse"
                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Click Here to Search
                Flight</button>
        </div>
    </div>
    <div class="collapse collapse1 pt-lg-2" id="collapseExample">
        <section class="fligh-search-first">
            <div class="container">
                <div class="row row-for-flex">
                    <div class="flex-search-flight">
                        <div class="from-to-detail">
                            <div class="from-city">
                                <p class="from-txt"> From</p>
                                <p class="from-name">{{ $formData['departure_location'] }}</p>
                            </div>
                            <div class="flight-icon">
                                <img src="{{ url('images/icon-plane1.png') }}" style=" width: 25px;">
                            </div>
                            <div class="from-city">
                                <p class="from-txt"> Destination</p>
                                <p class="from-name">{{ $formData['destination'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-search-flight">
                        <div class="from-to-detail">
                            <div class="dpa-date">
                                <div class="departure-date">
                                    <i class="fa fa-calendar"
                                        style="color: rgba(255, 255, 255, 0.6) !important; margin-top: 5px; font-size:14px;"></i>
                                    <p class="from-txt pl-1">Departure date</p>
                                </div>
                                <p class="desti-date">
                                    {{ \Carbon\Carbon::parse($formData['departure_date'])->format('d-F-Y') }}</p>
                            </div>
                            @if ($formData['trip'] == 'round')
                                <div class="dpa-date pl-5">
                                    <div class="departure-date">
                                        <i class="fa fa-calendar"
                                            style="color: rgba(255, 255, 255, 0.6) !important; margin-top: 5px; font-size:14px;"></i>
                                        <p class="from-txt pl-1">Return</p>
                                    </div>
                                    <p class="desti-date">
                                        {{ \Carbon\Carbon::parse($formData['returndate'])->format('d-F-Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex-search-flight">
                        <div class="from-to-detail">
                            <div class="dpa-date">
                                <div class="departure-date">
                                    <i class="fa fa-user-o"
                                        style="color: rgba(255, 255, 255, 0.6) !important; margin-top: 5px; font-size:14px;"></i>
                                    <p class="from-txt pl-1">Class</p>
                                </div>
                                <p class="desti-date">
                                    @if ($formData['flightclass'] == 'Y')
                                        Economy
                                    @elseif($formData['flightclass'] == 'S')
                                        Premium Economy
                                    @elseif($formData['flightclass'] == 'C')
                                        Business
                                    @elseif($formData['flightclass'] == 'J')
                                        Premium Business
                                    @elseif($formData['flightclass'] == 'F')
                                        First
                                    @elseif($formData['flightclass'] == 'P')
                                        Premium First
                                    @endif
                                </p>
                            </div>
                            <div class="dpa-date dpa-txt">
                                <div class="departure-date">
                                    <i class="fa fa-user-o"
                                        style="color: rgba(255, 255, 255, 0.6) !important; margin-top: 5px; font-size:14px;"></i>
                                    <p class="from-txt pl-1">Passengers</p>
                                </div>
                                <div class="psn-flex">
                                    <p class="desti-date">
                                        {{ $formData['madult'] }}
                                        Adults, {{ $formData['mchildren'] }}
                                        Children, {{ $formData['minfant'] }} Infants
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-search-flight-btn">
                        <button class="btn btn-modify" type="button" data-toggle="collapse"
                            data-target="#collapseExample2" aria-expanded="false"
                            aria-controls="collapseExample2">Modify</button>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <section class="new-fligh-search">
        <div class="container">
            <form method="post" action="{{ route('searchflights') }}" id="flightSearchForm">
                @csrf
                <div class="collapse" id="collapseExample2">
                    <div class="row switch-field-row" style="margin-bottom: 15px; margin-top: 15px; margin-left: 0px;">
                        <div class="switch-field">
                            <input type="radio" id="radio-three" onchange="oneWay_modify()" name="switch_two"
                                value="oneway" checked="">
                            <label class="custom" for="radio-three" id="radioTexts">One Way</label>
                            <input type="radio" id="radio-four" onchange="roundTrip_modify()" name="switch_two"
                                value="round">
                            <label class="custom round-trip" for="radio-four" id="radioTexts" name="returndate"
                                disabled="disabled">Round-Trip</label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-2">
                            <label class="label-txt">From</label>
                            <div class="item">
                                <span class="d-block text-gray-1 mb-0 text-left" style="font-weight: 500!important;">
                                </span>
                                <div class="mb-4">
                                    <div class="input-group py-2" style="border-bottom: 2px solid #FD9501;">
                                        <i class="d-flex align-items-center mr-2 font-weight-semi-bold"
                                            style="color:  #FD9501;">
                                            <img src="{{ asset('customIcons/airplane-takeoff-64.png') }}" alt="Image"
                                                style="height: 20px; width: 20px;"></i>
                                        <div
                                            class="smart-search border-0 p-0 form-control text-left  height-40  bg-transparent">
                                            <div class="autocomplete">
                                                <input id="from_where" name="from_where1" type="text"
                                                    style="border: none;background-image:unset !important">
                                                <input id="from_where1" type="text" placeholder="Country"
                                                    name="from_where" hidden="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Input -->
                            </div>
                        </div>
                        <div class="item item-button">
                            <div class="mb-4 form-content">
                                <div class="py-2 flex-nowrap">
                                    <button id="swapperBtn" type="button" class="btn btn-primary mt-4"
                                        style="border-radius: 50%; color: white; border: none;height: 40px;">
                                        <img src="{{ asset('customIcons/swapper.png') }}"
                                            style="height: 18px; width: 18px;">
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-2">
                            <label class="label-txt">To</label>
                            <div class="item">
                                <span class="d-block text-gray-1 mb-0 text-left" style="font-weight: 500!important;">
                                </span>
                                <div class="mb-4">
                                    <div class="input-group py-2" style="border-bottom: 2px solid #FD9501;">
                                        <i class="d-flex align-items-center mr-2 font-weight-semi-bold"
                                            style="color:  #FD9501;">
                                            <img src="{{ asset('customIcons/airplane-9-64.png') }}" alt="Image"
                                                style="height: 20px; width: 20px;"></i>
                                        <div
                                            class="smart-search border-0 p-0 form-control text-left  height-40  bg-transparent">
                                            <div class="autocomplete">
                                                <input id="to_where" name="to_where1" type="text"
                                                    style="border: none;background-image:unset !important">
                                                <input id="to_where1" type="text" placeholder="Country"
                                                    name="to_where" hidden="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Input -->
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="label-txt">Departure</label>
                            <div class="item">
                                <span class="d-block text-gray-1 text-left font-weight-normal"
                                    style="font-weight: 500 !important;"></span>
                                <div class="border-bottom-1 mb-4 form-content"
                                    style="border-bottom: 2px solid #FD9501!important; width: 100%;">
                                    <div class="u-datepicker input-group py-2 flex-nowrap form-date-search">
                                        <div class="input-group-prepend">
                                            <span class="d-flex align-items-center mr-2">
                                                <i class="fa fa-calendar" style="color: #FD9501"></i>
                                            </span>
                                        </div>
                                        <div class="date-wrapper height-40 font-size-13 ml-1 shadow-none form-control hero-form bg-transparent border-0 flatpickr-input p-0"
                                            style="">
                                            <input type="date"
                                                value="{{ $formData['departure_date'] ? $formData['departure_date'] : $returnDate }}"
                                                class="chek-in-out" name="start"
                                                style="border: none;background: no-repeat;margin-bottom: -5px;padding:0px;font-size:14px"
                                                id="txtDateModify">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="label-txt">Return</label>
                            <div class="item">
                                <span class="d-block text-gray-1 text-left font-weight-normal"
                                    style="font-weight: 500 !important;"></span>
                                <div class="border-bottom-1 mb-4 form-content"
                                    style="border-bottom: 2px solid #FD9501!important; width: 100%;">
                                    <div class="u-datepicker input-group py-2 flex-nowrap form-date-search">
                                        <div class="input-group-prepend">
                                            <span class="d-flex align-items-center mr-2">
                                                <i class="fa fa-calendar" style="color: #FD9501"s></i>
                                            </span>
                                        </div>
                                        <div class="date-wrapper height-40 font-size-13 ml-1 shadow-none form-control hero-form bg-transparent border-0 flatpickr-input p-0"
                                            style="">
                                            <input type="date" class="chek-in-input"
                                                value="{{ $formData['trip'] == 'round' ? $formData['returndate'] : $returnDate }}"
                                                name="start2"
                                                style="border: none;background: no-repeat;margin-bottom: -5px;padding:0px;font-size:14px"
                                                id="txtDate1Modify" readonly>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-2">
                            <label class="label-txt">Travellers</label>
                            <div class="item" style="margin-bottom: 20px;">
                                <div class="dropdown-custom px-0 mb-4 custom-select-dropdown-parent">
                                    <span class="d-block text-gray-1 text-left"
                                        style="font-weight: 500 !important;"></span>
                                    <div class="flex-horizontal-center py-2 d-flex  dropdown-toggle"
                                        data-toggle="dropdown" style="width: 100%; border-bottom: 2px solid #FD9501;">
                                        <i class="flaticon-plus d-flex align-items-center mr-2 font-weight-semi-bold"
                                            style="color:  #FD9501;"></i>
                                        <div
                                            class="text-black font-size-13 mr-auto height-40 d-flex align-items-center overflow-hidden">
                                            <div class="render">
                                                @php
                                                    
                                                    unset($seatType[3]);
                                                    unset($seatType[4]);
                                                    
                                                @endphp
                                                @foreach ($seatType as $type)
                                                    <?php
                                                    
                                                    $inputRender = 'seat_type_' . $type->code . '_render';
                                                    
                                                    $inputValue = $seatTypeGet[$type->code] ?? $minValue;
                                                    if ($type->code == 'madult') {
                                                        $inputValue = 1;
                                                        $minValue = 1;
                                                    }
                                                    if ($type->code == 'mchildren') {
                                                        $inputValue = 0;
                                                        $minValue = 0;
                                                    }
                                                    if ($type->code == 'minfant') {
                                                        $inputValue = 0;
                                                        $minValue = 0;
                                                    }
                                                    ?>
                                                    <span class="" id="{{ $inputRender }}">
                                                        <span
                                                            class="one @if ($inputValue > $minValue) d-none @endif">{{ __(':min :name', ['min' => $minValue, 'name' => $type->name]) }}</span>
                                                        <span
                                                            class="@if ($inputValue <= $minValue) d-none @endif multi"
                                                            data-html="{{ __(':count ' . $type->name) }}">{{ __(':count' . $type->name, ['count' => $inputValue ?? $minValue]) }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        
                                        unset($seatType[3]);
                                        unset($seatType[4]);
                                        
                                    @endphp
                                    <div class="dropdown-menu custom-select-dropdown"
                                        style="padding-top: 0px;padding-bottom:0px;width:97%">
                                        @foreach ($seatType as $type)
                                            <?php
                                            $inputName = 'seat_type_' . $type->code;
                                            $inputValue = $seatTypeGet[$type->code] ?? $minValue;
                                            if ($type->code == 'madult') {
                                                $inputValue = 1;
                                                $minValue = 1;
                                            }
                                            if ($type->code == 'mchildren') {
                                                $inputValue = 0;
                                                $minValue = 0;
                                            }
                                            if ($type->code == 'minfant') {
                                                $inputValue = 0;
                                                $minValue = 0;
                                            }
                                            ?>

                                            <div class="dropdown-item-row">
                                                <div class="label">{{ __(' :type', ['type' => $type->name]) }}</div>
                                                <div class="val">
                                                    <span class="btn-minus" data-input="{{ $inputName }}"
                                                        data-input-attr="id"><i class="icon ion-md-remove"></i></span>
                                                    <input class="shadow" id="{{ $inputName }}" type="number"
                                                        name="{{ $type->code }}" value="{{ $inputValue }}"
                                                        min="{{ $minValue }}" width="5%"
                                                        style="border: none; text-align: center;width:100px;padding:5px"
                                                        readonly>
                                                    <span class="btn-add" data-input="{{ $inputName }}"
                                                        data-input-attr="id"><i class="icon ion-ios-add"></i></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-2 flight-top">
                            <label class="label-txt m-0 fli-class">Flight Class</label>
                            <div class="item" style="margin-bottom: 20px;">
                                <div class="d-flex custom-select economy-drop" style="  ">
                                    <i class="fa fa-ticket d-flex align-items-center mr-2 font-weight-semi-bold"
                                        style="color: #FD9501"></i>
                                    <select name="flightclass"
                                        style="width: 125px; border:none; background-color:transparent; padding-top:3px;">
                                        <option class="options" value="Y">Economy</option>
                                        <option class="options" value="S">Premium Economy</option>
                                        <option class="options" value="C">Business</option>
                                        <option class="options" value="J">Premium Business</option>
                                        <option class="options" value="F">First</option>
                                        <option class="options" value="P">Premium First</option>
                                    </select>

                                    <div class="select-items select-hide">
                                        <div>Economy</div>
                                        <div>Premium Economy</div>
                                        <div>Business</div>
                                        <div>Premium Business</div>
                                        <div>First</div>
                                        <div>Premium First</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4">
                            <button type="submit" class="btn btn-search"><i class="fas fa-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <div class="container">
        <div class="row pl-3 pr-3 pt-3">
            <button class="btn btn-primary accordion1" type="button" data-toggle="collapse"
                data-target="#collapseExample3" aria-expanded="false" aria-controls="collapseExample3">Click for
                filter</button>
        </div>
    </div>

    <section class="flight-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-12">
                    <div class="collapse collapse1 " id="collapseExample3">
                        <div class="card card-body border-0 p-0">
                            <div class="row">
                                <div class="col-12">
                                    <div class="panel panel-info" style="margin-top: 14px;box-shadow:none;">
                                        <div class="panel-heading btn btn-block panal-heading-set"
                                            style="font-size:16px; background: linear-gradient(to bottom, #25C2DA 0%, #0899af 100%);">
                                            <a class="flight-search-filter"> <span
                                                    class="flight-search-filter1">Filters</span></a>
                                            <a type="button" id="clearFilters" class="mytm-penal-heading">Clear all
                                            </a>
                                        </div>
                                        <br>
                                        <div class="price-main-div">
                                            <div class="card" style="height: 11rem;">
                                                <div class="card-header"
                                                    style="background: linear-gradient(to bottom, #fff 0%, #ececec 100%);padding:10px;font-size:14px">
                                                    PRICE RANGE
                                                </div>
                                                <div class="card-body text-center">
                                                    <div id="slider-container" class="skeleton"></div>
                                                    <p>
                                                        <input type="text" id="amount"
                                                            class="price-range-amount w-auto" />
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="card" style="height: 11rem;margin-top:2rem">
                                                <div class="card-header"
                                                    style="background: linear-gradient(to bottom, #fff 0%, #ececec 100%);padding:10px;font-size:14px">
                                                    STOP
                                                </div>
                                                <div class="card-body rad-flex">
                                                    <div class="switch-field pt-0 stop-btn-switch"
                                                        style="border:none; overflow:visible !important;">
                                                        <label class="rad-tab-st">
                                                            <input type="radio" name="stops" value="0"
                                                                data-filtertype="stops" class="filterRadio"
                                                                id="stopzero" checked="" style="color: black; ">
                                                            <span class="radio-label btn btn-default">
                                                                Direct
                                                            </span>
                                                        </label>
                                                        <label class="rad-tab-st">
                                                            <input type="radio" name="stops" value="1"
                                                                data-filtertype="stops" class="filterRadio ">
                                                            <span class="radio-label btn btn-default">
                                                                1 Stop
                                                            </span>
                                                        </label>
                                                        <label class="rad-tab-st">
                                                            <input type="radio" name="stops" value="2"
                                                                data-filtertype="stops" class="filterRadio">
                                                            <span class="radio-label btn btn-default ">2 Stop</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card" style="margin-top: 2rem;">
                                                <div class="card-header"
                                                    style="background: linear-gradient(to bottom, #fff 0%, #ececec 100%);padding:10px;font-size:14px">
                                                    AIRLINES
                                                </div>
                                                <div class="card-body">
                                                    @if ($airlines)
                                                        @foreach ($airlines as $key => $airline)
                                                            <p style="padding:2px;border-bottom:1px solid #e2e2e2"
                                                                class="{{ $key }}">
                                                                <label>
                                                                    <input data-airline-code="{{ $key }}"
                                                                        id="flightsRadio-{{ $key }}"
                                                                        type="radio"
                                                                        class="filterRadio airline-filter-radio"
                                                                        name="flights" value="{{ $key }}"
                                                                        data-filtertype="carrier">

                                                                    <img style="height: 50px;width: 50px;"
                                                                        src="{{ $airline['airLineImg'] }}"
                                                                        alt="img">
                                                                    <span>{{ $airline['airLineName'] }}</span>
                                                                </label>
                                                            </p>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-9 col-12 flight-sectio-sty">
                    <button class="btn btn-primary w-100 for-testing" type="button" data-toggle="collapse"
                        data-target="#collapseExample4" aria-expanded="false" aria-controls="collapseExample4">
                        Click for more option
                    </button>
                    <div class="collapse" id="collapseExample4">
                        <div class="card card-body p-0 border-0">
                            <div id="flightGrid" class="collapse show"
                                style="border: 1px solid rgb(8, 153, 175); margin-top: -1px; border-radius: 4px; background-color: white;">
                            </div>
                        </div>
                    </div>
                    <div class="all-flight-detail">

                        @if ($pricedItinerariesGroup)
                            @php
                                $isFirst = true;
                                $count = 1;
                            @endphp
                            @foreach ($pricedItinerariesGroup as $key => $itinerariess)
                                @php
                                    $itinsCount = count($itinerariess);
                                    $haveSamePriced = $itinsCount > 1;
                                    $firstItin = $itinerariess[0];
                                    if ($haveSamePriced) {
                                        unset($itinerariess[0]);
                                        $itinerariess = array_values($itinerariess);
                                    }
                                @endphp
                                @include('search_flight.single-flight', [
                                    'is_return' => $firstItin['flightDiresction'] === 'round' ? true : false,
                                ])
                                @php
                                    $count++;
                                    $isFirst = false;
                                @endphp
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <footer>
        <div class="container contain-footer">
            <div class="row">
                <div class="col-12">
                    @if (!empty(($info_contact = clean(setting_item_with_lang('footer_info_text')))))
                        {!! clean($info_contact) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="copy-right">
            <div class="container context pt-4 pl-4 pr-4">
                <div class="row">
                    <div class="col-md-12 fter-flex p-0">
                        <p class="resrvd-txt">© 2022 Binham Group. All rights reserved</p>
                        <div class="f-visa">
                            <img src="{{ $logo }}" alt="{{ setting_item('site_title') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <div id="flightDetailsShow" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content" style="width: 110%">
                <div class="modal-header btn_bg">
                    <h3 class="modal-title text-center primecolor">Flight Details</h3>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body" style="overflow: hidden;" id="flightDetailsShowBody">


                </div>
            </div>

        </div>
    </div>
    @include('Layout::parts.login-register-modal')
@endsection

@section('script')
    <!-- jQuery -->
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="{{ asset('new_changes/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('new_changes/js/popper.min.js') }}"></script>
    <script src="{{ asset('new_changes/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('new_changes/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('new_changes/js/main.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script type="text/javascript">
        let airports = [];
        let airlines = [];
        let airportcitycountry = [];

        function oneWay_modify() {
            $("#dateOne").show();
            $("#dateTwo").css("pointer-events", "none");
            $("#dateTwo").css("opacity", ".7");
            $('.chek-in-input').attr('readonly', true)
        }

        function roundTrip_modify() {
            $("#dateTwo").css("pointer-events", "auto");
            $("#dateTwo").css("opacity", "1");
            $("#dateOne").show();
            $('.chek-in-input').attr('readonly', false)
        }

        function fromSearchAuto(inp, arr) {
            let currentFocus;
            inp.addEventListener("input", function(e) {
                let a, b, i, val = this.value;
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(a);
                for (i = 0; i < arr.length; i++) {
                    if (arr[i]['title'].substr(0, val.length).toUpperCase() == val.toUpperCase() || arr[i][
                            'sub_title'
                        ].substr(0, val.length).toUpperCase() == val.toUpperCase() || arr[i]['iata_code'].substr(0,
                            val.length).toUpperCase() == val.toUpperCase()) {
                        b = document.createElement("DIV");
                        b.className = "";
                        b.innerHTML = "<p class='item_list_custom'>" + arr[i]['title'] +
                            "<span class='flight-codes'> (" + arr[i]['iata_code'] + ") </span>" + "</p>";
                        b.innerHTML += "<input type='hidden' value='" + arr[i]['title'] + ">" + arr[i][
                            'iata_code'
                        ] + "'>";
                        b.addEventListener("click", function(e) {
                            let customData = this.getElementsByTagName("input")[0].value;
                            inp.value = customData.substring(0, customData.indexOf('>')) + " (" + customData
                                .substring(customData.indexOf('>') + 1) + " )";
                            document.getElementById("from_where1").value = customData.substring(customData
                                .indexOf('>') + 1);
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            inp.addEventListener("keydown", function(e) {
                let x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    currentFocus++;
                    addActive(x);
                } else if (e.keyCode == 38) {
                    currentFocus--;
                    addActive(x);
                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    if (currentFocus > -1) {
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                if (!x) return false;
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                for (let i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                let x = document.getElementsByClassName("autocomplete-items");
                for (let i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        function toSearchAuto(inp, arr) {
            let currentFocus;
            inp.addEventListener("input", function(e) {
                let a, b, c, i, val = this.value;
                closeAllLists();
                if (!val) {
                    return false;
                }
                currentFocus = -1;
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                this.parentNode.appendChild(a);
                for (i = 0; i < arr.length; i++) {
                    if (arr[i]['title'].substr(0, val.length).toUpperCase() == val.toUpperCase() || arr[i][
                            'sub_title'
                        ].substr(0, val.length).toUpperCase() == val.toUpperCase() || arr[i]['iata_code'].substr(0,
                            val.length).toUpperCase() == val.toUpperCase()) {
                        b = document.createElement("DIV");
                        b.className = "";
                        b.innerHTML = "<p class='item_list_custom'>" + arr[i]['title'] + "<span> (" + arr[i][
                            'iata_code'
                        ] + ") </span>" + "</p>";
                        b.innerHTML += "<input type='hidden' value='" + arr[i]['title'] + ">" + arr[i][
                            'iata_code'
                        ] + "'>";
                        b.addEventListener("click", function(e) {
                            let customData = this.getElementsByTagName("input")[0].value;
                            inp.value = customData.substring(0, customData.indexOf('>')) + " (" + customData
                                .substring(customData.indexOf('>') + 1) + " )";
                            document.getElementById("to_where1").value = customData.substring(customData
                                .indexOf('>') + 1);
                            closeAllLists();
                        });
                        a.appendChild(b);
                    }
                }
            });
            inp.addEventListener("keydown", function(e) {
                let x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                    currentFocus++;
                    addActive(x);
                } else if (e.keyCode == 38) {
                    currentFocus--;
                    addActive(x);
                } else if (e.keyCode == 13) {
                    e.preventDefault();
                    if (currentFocus > -1) {
                        if (x) x[currentFocus].click();
                    }
                }
            });

            function addActive(x) {
                if (!x) return false;
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                x[currentFocus].classList.add("autocomplete-active");
            }

            function removeActive(x) {
                for (let i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }

            function closeAllLists(elmnt) {
                let x = document.getElementsByClassName("autocomplete-items");
                for (let i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }

            document.addEventListener("click", function(e) {
                closeAllLists(e.target);
            });
        }

        $(document).ready(function() {
            $.ajax({
                'url': '/get-location',
                'type': 'GET',
                success: function(data) {
                    let mapData = data;

                    let from = document.getElementById("from_where");
                    let to = document.getElementById("to_where");

                    let from1 = document.getElementById("from_where1");
                    let to1 = document.getElementById("to_where1");
                    // let from = document.getElementById("from_where");
                    // let to = document.getElementById("to_where");
                    from.value = "{{ $departureloaction[0]['name'] }}";
                    to.value = "{{ $destinationloaction[0]['name'] }}";
                    from1.value = "{{ $departureloaction[0]['iata_code'] }}";
                    to1.value = "{{ $destinationloaction[0]['iata_code'] }}";


                    fromSearchAuto(from, mapData);
                    toSearchAuto(to, mapData);
                }
            });

            $.when(airportsAjax(), airlinesAjax()).done(function(a1, a2, a3, a4) {
                $('.show-details').removeAttr('disabled');
                $('.show-details').removeClass('bg-processing');
                $('.show-details').html('Details');
            });

            function airportsAjax() {
                return $.ajax({
                    'url': "{{ route('airportsdata') }}",
                    'type': 'GET',
                    success: function(data) {
                        airports = data;
                    }
                });
            }

            function airlinesAjax() {
                return $.ajax({
                    'url': "{{ route('airlinesdata') }}",
                    'type': 'GET',
                    success: function(data) {
                        airlines = data;
                    }
                });
            }


            $(".custom-select-dropdown .btn-minus").on("click", function(e) {
                e.stopPropagation();
                let parent = $(this).closest(".custom-select-dropdown-parent");
                let inputAttr = $(this).data("input-attr");
                if (typeof inputAttr == "undefined") {
                    inputAttr = "name";
                }
                let input = parent.find(
                    ".custom-select-dropdown [" +
                    inputAttr +
                    "=" +
                    $(this).data("input") +
                    "]"
                );
                let min = parseInt(input.attr("min"));
                let old = parseInt(input.val());

                if (old <= min) {
                    return;
                }
                input.val(old - 1);
                updateCustomSelectDropdown(input);
            });

            $(".custom-select-dropdown .btn-add").on("click", function(e) {
                e.stopPropagation();
                let parent = $(this).closest(".custom-select-dropdown-parent");
                let inputAttr = $(this).data("input-attr");
                if (typeof inputAttr == "undefined") {
                    inputAttr = "name";
                }
                let input = parent.find(
                    ".custom-select-dropdown [" +
                    inputAttr +
                    "=" +
                    $(this).data("input") +
                    "]"
                );
                let max = parseInt(input.attr("max"));
                let old = parseInt(input.val());

                if (old >= max) {
                    return;
                }
                input.val(old + 1);
                updateCustomSelectDropdown(input);
            });

            $(".custom-select-dropdown input").on("keyup", function(e) {
                updateCustomSelectDropdown($(this));
            });
            $(".custom-select-dropdown input").on("change", function(e) {
                updateCustomSelectDropdown($(this));
            });

            function updateCustomSelectDropdown(input) {
                let parent = input.closest(".custom-select-dropdown-parent");
                let target = input.attr("id");
                let number = parseInt(input.val());
                let render = parent.find("[id=" + target + "_render]");

                let htmlString = render.find(".multi").data("html");
                let min = input.attr("min");
                console.log(render);
                if (number > min) {
                    render
                        .find(".multi")
                        .removeClass("d-none")
                        .html(htmlString.replace(":count", number));
                    render.find(".one").addClass("d-none");
                } else {
                    render.find(".multi").addClass("d-none");
                    render.find(".one").removeClass("d-none");
                }
            }
            $(".custom-select-dropdown .dropdown-item-row").on("click", function(e) {
                e.stopPropagation();
            });

            $('.flight-search-filter').click(function() {

                if ($(window).width() <= 767) {
                    $('.price-main-div').toggleClass('filtered-mob-resp');

                    if ($(".flight-search-filter").text() === 'Filters') {
                        $(".flight-search-filter").text('Click Here');
                    } else {
                        $(".flight-search-filter").text('Filters');
                    }
                } else {
                    console.log('Umer');
                }

            })

            $('[data-toggle="tooltip"]').tooltip();
            let showMore = false;

            $("body").on('change', ".airline-filter-radio", function() {
                let airCode = $(this).attr('data-airline-code');
                $(this).prop('checked');
            });

            // let val1=from.val();
            // alert(val1);

            $('#btnSearch').on("click", function(e) {
                $("#from_where").each(function() {
                    if ($(this).val() == '') {
                        e.preventDefault();
                        $('#from_where').css({
                            "background-color": "rgb(253 149 0 / 22%)",
                            "width": "58%"
                        });
                    } else {
                        $('#from_where').css("background-color", "unset");
                    }
                });

                $("#to_where").each(function() {
                    if ($(this).val() == '') {
                        e.preventDefault();
                        $('#to_where').css({
                            "background-color": "rgb(253 149 0 / 22%)",
                            "width": "58%"
                        });
                    }
                });
            });

            $(".filterRadio").click(function() {

                let showAll = true;
                $('.card-flight-leg').hide();
                $('.search-result-card').hide();

                $('.filterRadio').each(function() {

                    if ($(this)[0].checked) {
                        showAll = false;
                        let value = $(this).val();
                        console.log(value);

                        console.log($(this).data('filtertype'));
                        if ($(this).data('filtertype') === 'carrier') {
                            if ($("div").hasClass(value)) {
                                $('.' + value).parent().parent().show();
                                $('.' + value).show();
                            }
                            console.log('div[data-carrier="' + value + ':0"]');
                            //$('div[data-carrier="' + value +'"]').show();
                        } else if ($(this).data('filtertype') === 'stops') {
                            console.log('div[data-stops="' + value + ':0"]');
                            $('div[data-stops="' + value + ':0"]').show();
                            let str = $('.flightBox').first().attr('style');
                            let result = str.search('none');
                            console.log("checp restul" + result);
                            if (result == true || result == "true") {
                                $('#cheapestAlert').hide();
                            } else {
                                $('#cheapestAlert').show();
                            }
                        }
                    }
                });
                if (showAll) {
                    $('.card-flight-leg').show();
                    $('.search-result-card').show();
                }
            });
            let qry = {!! !empty($qry) ? json_encode($qry) : json_encode(\Illuminate\Support\Facades\Session::get('qry', 'default')) !!};
            if (qry['trip'] == "round" || qry['trip'] == "oneway") {
                $.ajax({
                    type: 'GET',
                    data: {
                        departure_location: qry['departure_location'],

                        destination: qry['destination'],
                        departure_date: qry['departure_date'],
                        totalManualPassenger: qry['totalManualPassenger'],
                        flightclass: qry['flightclass'],
                        trip: qry['trip'],
                        madult: qry['madult'],
                        mchildren: qry['mchildren'],
                        minfant: qry['minfant'],
                        returndate: qry['returndate']
                    },
                    url: '/api/search-grid'
                }).then(function(data) {
                    if (data) {
                        $('#flightGrid').html(data);
                    }
                });
                $.ajax({
                    'url': "{{ route('airportcitycountry') }}",
                    'type': 'GET',
                    success: function(data) {
                        airportcitycountry = data;
                    }
                });
            }

        });
        $("#from_where").focus(function() {
            $('#from_where').css("background-color", "unset");
        });
        //
        $("#to_where").focus(function() {
            $('#to_where').css("background-color", "unset");
        });

        $('#clearFilters').click(function() {
            console.log('hello');
            $(".filterRadio").prop('checked', false)
            $('.flightBox').show();
        });
        $('#clearFilters').triggerHandler("click");


        $(".filterRadio").click(function() {


            $('.flightBox').show();
            $('.filterRadio').each(function() {
                if ($(this)[0].checked) {
                    let value = $(this).val();
                    let filtertype = $(this).data('filtertype');
                    console.log(filtertype);
                    // console.log(value);
                    if (filtertype == 'carrier') {
                        $('.flightBox').each(function() {
                            if ($(this).data('carrier') != value)
                                $(this).hide();
                        });
                    } else {
                        $('.flightBox').each(function() {
                            if ($(this).data('stops') != value)
                                $(this).hide();
                        });
                    }

                }
            });

        });
        $(function() {
            min = {{ $minPrice }};
            max = {{ $maxPrice }};
            console.log('min and max price of flights', min, max);
            $('#slider-container').slider({
                range: true,
                min: {{ $minPrice }},
                max: {{ $maxPrice }},
                values: [{{ $minPrice }}, {{ $maxPrice }}],
                create: function() {
                    $("#amount").val("PKR {{ $minPrice }} - PKR {{ $maxPrice }}");
                },
                slide: function(event, ui) {
                    $("#amount").val("PKR " + ui.values[0] + " - " + "PKR " + ui.values[1]);
                    let mi = ui.values[0];
                    let mx = ui.values[1];
                    console.log('slid:', ui.values)
                    filterSystem(mi, mx);
                }
            })
        });

        function filterSystem(minPrice, maxPrice) {

            $(".ticket-containers").hide().filter(function(e) {

                let price = parseInt($(this).attr('data-price'));
                return price >= minPrice && price <= maxPrice;
            }).show();
        }
        let x, i, j, l, ll, selElmnt, a, b, c;
        /*look for any elements with the class "custom-select":*/
        x = document.getElementsByClassName("custom-select");
        l = x.length;
        for (i = 0; i < l; i++) {
            selElmnt = x[i].getElementsByTagName("select")[0];
            ll = selElmnt.length;
            /*for each element, create a new DIV that will act as the selected item:*/
            a = document.createElement("DIV");
            a.setAttribute("class", "select-selected");
            a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
            x[i].appendChild(a);
            /*for each element, create a new DIV that will contain the option list:*/
            b = document.createElement("DIV");
            b.setAttribute("class", "select-items select-hide");
            for (j = 1; j < ll; j++) {
                /*for each option in the original select element,
                create a new DIV that will act as an option item:*/
                c = document.createElement("DIV");
                c.innerHTML = selElmnt.options[j].innerHTML;
                c.addEventListener("click", function(e) {
                    /*when an item is clicked, update the original select box,
                    and the selected item:*/
                    let y, i, k, s, h, sl, yl;
                    s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                    sl = s.length;
                    h = this.parentNode.previousSibling;
                    for (i = 0; i < sl; i++) {
                        if (s.options[i].innerHTML == this.innerHTML) {
                            s.selectedIndex = i;
                            h.innerHTML = this.innerHTML;
                            y = this.parentNode.getElementsByClassName("same-as-selected");
                            yl = y.length;
                            for (k = 0; k < yl; k++) {
                                y[k].removeAttribute("class");
                            }
                            this.setAttribute("class", "same-as-selected");
                            break;
                        }
                    }
                    h.click();
                });
                b.appendChild(c);
            }
            x[i].appendChild(b);
            a.addEventListener("click", function(e) {
                /*when the select box is clicked, close any other select boxes,
                and open/close the current select box:*/
                e.stopPropagation();
                closeAllSelect(this);
                this.nextSibling.classList.toggle("select-hide");
                this.classList.toggle("select-arrow-active");
            });
        }

        function closeAllSelect(elmnt) {
            /*a function that will close all select boxes in the document,
            except the current select box:*/
            let x, y, i, xl, yl, arrNo = [];
            x = document.getElementsByClassName("select-items");
            y = document.getElementsByClassName("select-selected");
            xl = x.length;
            yl = y.length;
            for (i = 0; i < yl; i++) {
                if (elmnt == y[i]) {
                    arrNo.push(i)
                } else {
                    y[i].classList.remove("select-arrow-active");
                }
            }
            for (i = 0; i < xl; i++) {
                if (arrNo.indexOf(i)) {
                    x[i].classList.add("select-hide");
                }
            }
        }
        /*if the user clicks anywhere outside the select box,
        then close all select boxes:*/
        document.addEventListener("click", closeAllSelect);

        function swapLocations(swapperBtn) {
            swapperBtn.addEventListener("click", function(e) {
                let swapE = document.getElementById("from_where").value;
                document.getElementById("from_where").value = document.getElementById("to_where").value;
                document.getElementById("to_where").value = swapE;

                let swapH = document.getElementById("from_where1").value;
                document.getElementById("from_where1").value = document.getElementById("to_where1").value;
                document.getElementById("to_where1").value = swapH;
            });
        }

        swapLocations(swapperBtn);
        $(function() {
            let dtToday = new Date();

            let month = dtToday.getMonth() + 1;
            let day = dtToday.getDate();
            let year = dtToday.getFullYear();
            if (month < 10)
                month = '0' + month.toString();
            if (day < 10)
                day = '0' + day.toString();

            let minDate = year + '-' + month + '-' + day;



            $('#txtDate1Modify').attr('min', minDate);
        });
        $(function() {
            let dtToday = new Date();

            let month = dtToday.getMonth() + 1;
            let day = dtToday.getDate();
            let year = dtToday.getFullYear();
            if (month < 10)
                month = '0' + month.toString();
            if (day < 10)
                day = '0' + day.toString();

            let minDate = year + '-' + month + '-' + day;

            $('#txtDateModify').attr('min', minDate);
        });
        document.getElementById('txtDateModify').onchange = function() {

            let textfield81 = document.getElementById('txtDateModify');
            let textfield91 = document.getElementById('txtDate1Modify');
            textfield81.setAttribute('max', 'min', this.value);
            textfield91.setAttribute('min', this.value);
            if (textfield81.value) {
                textfield91.value = this.value;
            }

        };

        $('body').on('click', '.show-details', function(e) {
            e.preventDefault();
            tabId = $(this).attr('target-tab-id');
            let segContainer = $('#segments-' + tabId).find('.append-details-' + tabId);
            let fareContainer = $('#Fare-' + tabId).find('.append-details-' + tabId);
            let baggageContainer = $('#Baggage-' + tabId).find('.append-details-' + tabId);
            console.log(baggageContainer);
            if (!segContainer.children().length) {
                segments = JSON.parse(atob($(this).attr('data-segments')));
                segmentsDom = getSegments(segments);
                segContainer.append(segmentsDom);
            }
            if (!fareContainer.children().length) {
                fare = JSON.parse(atob($(this).attr('data-fare-details')));
                fareTable = getFares(fare);
                fareContainer.append(fareTable);
            }
            if (!baggageContainer.children().length) {
                baggage = JSON.parse(atob($(this).attr('data-baggae')));
                baggageTable = getBaggage(baggage);
                console.log(baggageTable);
                baggageContainer.append(baggageTable);
            }
        });

        function getSegments(segments) {
            let outBound = segments.outbound;
            console.log(outBound);
            let inbound = segments.inbound ?? segments.inbound;
            content = '<div class="card-header head2"> Flight from ' +
                outBound.flightStartAirline + ' To ' + outBound.flightEndAirline + ' On ' + dateFormat(outBound
                    .flightStartDate) +
                '</div>';
            outBound.segments.forEach(data => {
                segment = '';
                if ('layoverInMins' in data) {
                    layover = '<div class="layove-tg text-center">' +
                        '<span class="layover"><img src="{{ asset('images/layover.png') }}"> Layover ' +
                        timeConvert(data.layoverInMins) + ' in ' + checkAirportByCode(data.departure.airport
                            .code) + '</span>' +
                        '</div>';
                    segment += layover;
                }
                segment +=
                    '<div class="card-body row pl-0 text-direction align-items-center" style="padding-right: 0px;padding-bottom:1rem;margin:0px;padding-top:2rem">' +
                    '<div class="col-xl-2 col-md-12 col-sm-12 col-12" style="font-size: 9px;">' +
                    '<img class="img-fluid img-icon-baggag" src="' + checkAirlineByCode(data.OperatingAirline) +
                    '" alt="GF">' +
                    '<br>' +
                    ' <div class="img-fluid-details">' +
                    '<span style="text-overflow: ellipsis">' + checkAirlineByCode(data.OperatingAirline, 'name') +
                    '</span>' +
                    '<ul class="list-default"> ' + getClass(data.flightDetail.cabinCode) + '</ul>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-12 col-sm-12 col-xl-7 text-center" style="font-size:14px;">' +
                    '<div class="row">' +
                    '<div class="col-sm-4 left-flight-detials">' +
                    '<span class="location-dep font-weight-bold"> ' + checkAirportByCode(data.departure.airport
                        .code) + ' </span>' +
                    '<br>' +
                    '<span>' +
                    dateFormat(data.departure.date) +
                    '<br>' +
                    '<span class="font-weight-bold">' + data.departure.time + '</span>' +
                    '<br>' +
                    '<span>Terminal:</span>' +
                    '<span class="location-dep ml-0">' + data.departure.airport.terminal + '</span>' +
                    '<br>' +
                    '</span>' +
                    '</div>' +
                    '<div class="col-sm-4 center-flight-details">' +
                    '<span style="font-size: 12px;">' + data.estimatedTime + '</span><br>' +
                    '<span class="line-stop"><img class="baggageIcone seg-plane-svg" src="{{ asset('images/plane-listing.png') }}"></span>' +
                    '</div>' +
                    '<div class="col-sm-4 pt-0 right-flight-details">' +
                    '<span class="location-dep font-weight-bold"> ' + checkAirportByCode(data.arrival.airport
                        .code) + '</span>' +
                    '<br>' +
                    '<span class="terminal-details">' + dateFormat(data.arrival.date) + '</span><br>' +
                    '<span class="terminal-details font-weight-bold">' + data.arrival.time + '</span>' +
                    '<br>' +
                    '<span class="terminal-details">Terminal: </span><span class="ml-0">' + data.arrival.airport
                    .terminal + '</span>' +
                    '</div>' +
                    ' </div>' +
                    '</div>' +
                    ' <div class="col-12 col-sm-12 col-xl-3 flight-right-details">' +
                    '<ul class="list-default list-bgrz mb-0">' +
                    '<li class="for-plane"><i class="fa fa-plane pr-2" style="color: #ed7023"></i>' +
                    'Flight:<span>' + data.OperatingAirline + '-' + data.flightNumber +
                    '</span>' +
                    '</li>' +
                    '<li class="for-list"><i class="fa fa-list-alt pr-2" style="color: #ed7023"></i>Booking' +
                    'class:' +
                    '<span>' + data.flightDetail.bookingCode + '</span>' +
                    '</li>' +
                    '<li class="for-bag"><i class="fa fa-list-alt pr-2" style="color: #ed7023"></i>' +
                    'Cabin code:' +
                    '<span>' + data.flightDetail.cabinCode + '</span>' +
                    '</li>' +
                    '<li class="for-briefcase"><i class="fa fa-list-alt pr-2" style="color: #ed7023"></i>' +
                    'Meal code: ' + data.flightDetail.mealCode +
                    '</li>' +
                    '</ul>' +
                    '</div>' +
                    '</div>';
                content += segment;
            });
            return content;
        }

        function getFares(fare) {
            table = '<table class="table table-borderless" style="margin-bottom: 0px">' +
                '<tbody>' +
                '<tr>' +
                '<td class="text-left" style="font-size: 14px">Base Fare:</td>' +
                '<td class="text-right" style="font-size: 14px">' + fare.currency + ' ' + fare.totalBaseFare + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="text-left" style="font-size: 14px">Handling Charges:</td>' +
                '<td class="text-right" style="font-size: 14px">' + fare.currency + ' ' + fare.handlingCharges + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="text-left" style="font-size: 14px">Taxes &amp;Fees:' +
                '</td>' +
                '<td class="text-right" style="font-size: 14px">' + fare.currency + ' ' + fare.totalTaxAmount + '</td>' +
                '</tr>' +
                '<tr>' +
                '<td class="text-left" style="font-size: 14px">Total (incl.VAT):</td>' +
                ' <td class="text-right" style="font-size: 14px">' + fare.currency + ' ' + fare.totalPrice + '</td>' +
                '</tr>' +
                '</tbody>' +
                '</table>';
            return table;
        }

        function getBaggage(baggageDetails) {
            let baggae = '';
            $.each(baggageDetails, function(key, value) {
                console.log(value);
                $.each(value, function(keybag, baggage) {
                    baggae += '<tr>' +
                        '<td>' + key + '</td>' +
                        '<td>' + baggage.airlineCode + '</td>' +
                        '<td>' + baggage.provisionType + '</td>' +
                        '<td>' + baggage.provision + '</td>' +
                        '<td>' + getBaggageUnitDetails(baggage) +
                        '</td>' +
                        '</td>'
                });
            });
            return baggae;
        }

        function getBaggageUnitDetails(baggage) {
            if (('weight' in baggage) && ('unit' in baggage) && ('pieceCount' in baggage)) {
                return baggage.weight + ' ' + baggage.unit + ' / ' + baggage.pieceCount + ' piece';
            } else if (('weight' in baggage) && ('unit' in baggage)) {
                return baggage.weight + ' ' + baggage.unit
            } else {
                return baggage.pieceCount + ' piece';
            }
        }

        function dateFormat(data) {
            const parsedDate = new Date(data).toLocaleDateString('en-uk', {
                year: "numeric",
                month: "long",
                day: "numeric"
            });
            return parsedDate;
        }

        function checkAirportByCode(code) {
            if (code in airports) {
                airPortName = airports[code].sub_title;
                return airPortName;
            }
        }

        function checkAirlineByCode(code, attr = 'full_image_url') {
            if (code in airlines) {
                console.log(airlines[code]);
                data = airlines[code][attr];
                return data;
            }
        }

        function getClass(code) {
            switch (code) {
                case 'Y':
                    return 'Economy class';
                    break;
                case 'W':
                    return 'Premium class';
                    break;
                case 'J':
                    return ' Business class';
                    break;
                case 'F':
                    return 'First class';
                    break;
            }
        }

        function timeConvert(n) {
            let num = n;
            let hours = (num / 60);
            let rhours = Math.floor(hours);
            let minutes = (hours - rhours) * 60;
            let rminutes = Math.round(minutes);
            return rhours + " hour(s) and " + rminutes + " minute(s)";
        }

        $('.collapse').on('shown.bs.collapse', function() {
            $('#' + $(this).attr('data-btn')).html('Show less <i class="fa-solid fa-arrow-up pl-3"></i>');

        });
        $('.collapse').on('hidden.bs.collapse', function() {
            let elem = $('#' + ($(this).attr('data-btn')));
            console.log(elem);
            let text = 'Click For ' + elem.attr('data-how') +
                ' Flights <i class="fa-solid fa-arrow-down pl-3"></i>';
            elem.html(text);
        });

        function checkFields() {
            let r = $('#guest_name');
            let e = $('#guest_email');
            $(r).on("blur", function() {
                if ($(this).val().match('^[a-z A-Z]{3,16}$')) {
                    r.removeClass('has-error');
                    r.addClass('pass_details_txt_input')
                    r.next().hide();
                } else {
                    r.addClass('has-error');
                    r.removeClass('pass_details_txt_input')
                    r.next().show();
                }
            });
            $(e).on("blur", function() {
                let regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if ($(this).val().match(regex)) {
                    e.removeClass('has-error');
                    e.addClass('pass_details_txt_input')
                    e.next().hide();
                } else {
                    e.addClass('has-error');
                    e.removeClass('pass_details_txt_input')
                    e.next().show();
                }
            });
        }

        function IsEmail(email) {
            let regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }

        function loginFunction() {
            $("#otp-send").hide();
            $('#generating').show();
            let check = false;
            let guest_name = $('#guest_name');
            let guest_email = $('#guest_email');
            let guest_phone = $('#guest_phone');


            if (guest_name.val() == '' || guest_name.val() == 'undefined') {
                guest_name.addClass('has-error');
                guest_name.removeClass('pass_details_txt_input')
                guest_name.next().show();
                let check_name = false;
            } else {
                guest_name.removeClass('has-error');
                guest_name.addClass('pass_details_txt_input')
                guest_name.next().hide();
                let check_name = true;
            }
            if (guest_email.val() == '' || guest_email.val() == 'undefined' || IsEmail(guest_email.val()) == false) {
                guest_email.addClass('has-error');
                guest_email.removeClass('pass_details_txt_input')
                guest_email.next().show();
                let check_email = false;
            } else {
                guest_email.removeClass('has-error');
                guest_email.addClass('pass_details_txt_input')
                guest_email.next().hide();
                let check_email = true;
            }
            if (guest_phone.val() == '' || guest_phone.val() == 'undefined' || guest_phone.val().length < 10 || guest_phone
                .val().length > 10) {
                guest_phone.addClass('has-error');
                guest_phone.removeClass('pass_details_txt_input')
                guest_phone.next().show();
                let check_phone = false;
            } else {
                // alert(guest_phone.val().length);
                guest_phone.removeClass('has-error');
                guest_phone.addClass('pass_details_txt_input')
                guest_phone.next().hide();
                let check_phone = true;
            }
            if (check_name && check_email && check_phone) {
                let check = true;
            }


            if (check) {



                function startTimer(duration, display) {
                    let timer = duration,
                        minutes, seconds;
                    let x = setInterval(function() {
                        minutes = parseInt(timer / 60, 10);
                        seconds = parseInt(timer % 60, 10);

                        minutes = minutes < 10 ? "0" + minutes : minutes;
                        seconds = seconds < 10 ? "0" + seconds : seconds;

                        display.textContent = minutes + ":" + seconds;

                        if (--timer < 0) {
                            timer = duration;
                        } else if (timer == 0) {
                            clearInterval(x);
                            $("#demo").hide();
                            $('#otp-send').show();
                            $("#otp-verify").hide();
                            $(".otp-veri-box").hide();
                            $(".optSendMessage").hide();
                            $('#verification_number').hide();
                            $('.countdown-verify').hide();
                        }
                    }, 1000);
                }

                urlTo = '{{ route('getotp') }}';


                $.ajax({
                    url: urlTo,
                    contentType: "application/json",
                    type: 'GET',
                    data: {
                        'guest_name': $('#guest_name').val(),
                        'guest_email': $('#guest_email').val(),
                        'guest_phone': $('#guest_phone').val(),
                        'guest_phone_code': $('#country_code').val(),

                    },
                    success: function(data, textStatus, xhr) {
                        if (data.success == true) {
                            $successMsg =
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                data.message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>';
                            $('#demo').show();
                            $(".optSendMessage").hide().show('medium');
                            $(".otp-veri-box").show();
                            $("#otp-send").hide();
                            $('#verification_number').show();
                            $('#otp-verify').show();
                            $('#otpsendDiv').hide();
                            $('#generating').hide();
                            let fiveMinutes = 60 * 2,
                                display = document.querySelector('#demo');
                            $('.countdown-verify').show();
                            $('#login-form-error-msg-section').html($successMsg);
                            startTimer(fiveMinutes, display);
                        } else {
                            $('#generating').hide();
                            $("#otp-send").show();
                            $errorMsg =
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                data.message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>';
                            $('#login-form-error-msg-section').html($errorMsg);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {

                    }
                });
            } else {
                $("#otp-send").show();
                $('#generating').hide();
            }


        };

        function verifyOTP() {
            $('#otp-verify').hide();
            $('#verifing').show();
            let check = false;
            let guest_name = $('#guest_name');
            let guest_email = $('#guest_email');
            let guest_phone = $('#guest_phone');
            let verification_number = $('#verification_number');

            if (guest_name.val() == '' || guest_name.val() == 'undefined') {
                guest_name.addClass('has-error');
                guest_name.removeClass('pass_details_txt_input')
                guest_name.next().show();
                let check_name = false;
            } else {
                guest_name.removeClass('has-error');
                guest_name.addClass('pass_details_txt_input')
                guest_name.next().hide();
                let check_name = true;
            }
            if (guest_email.val() == '' || guest_email.val() == 'undefined') {
                guest_email.addClass('has-error');
                guest_email.removeClass('pass_details_txt_input')
                guest_email.next().show();
                let check_email = false;
            } else {
                guest_email.removeClass('has-error');
                guest_email.addClass('pass_details_txt_input')
                guest_email.next().hide();
                let check_email = true;
            }
            if (guest_phone.val() == '' || guest_phone.val() == 'undefined') {
                guest_phone.addClass('has-error');
                guest_phone.removeClass('pass_details_txt_input')
                guest_phone.next().show();
                let check_phone = false;
            } else {
                guest_phone.removeClass('has-error');
                guest_phone.addClass('pass_details_txt_input')
                guest_phone.next().hide();
                let check_phone = true;
            }
            if (verification_number.val() == '' || verification_number.val() == 'undefined') {
                verification_number.addClass('has-error');
                verification_number.removeClass('pass_details_txt_input')
                verification_number.next().show();
                let check_verification_number = false;
            } else {
                verification_number.removeClass('has-error');
                verification_number.addClass('pass_details_txt_input')
                verification_number.next().hide();
                let check_verification_number = true;
            }
            if (check_name && check_email && check_phone && verification_number) {
                let check = true;
            }

            urlTo = '{{ route('verifyotp') }}';
            if (check) {
                $.ajax({
                    url: urlTo,
                    // contentType: "application/json",
                    type: 'POST',
                    data: {
                        'guest_phone': $('#guest_phone').val(),
                        'guest_email': $('#guest_email').val(),
                        'verification_number': $('#verification_number').val(),
                    },
                    success: function(data, textStatus, xhr) {
                        if (data.success) {
                            $successMsg =
                                '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                data.message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>';
                            $('#login-form-error-msg-section').html($successMsg);
                            $('#otp-verify').hide();
                            $('#verifing').hide();
                            location.reload()
                        } else {
                            $('#otp-verify').show();
                            $('#verifing').hide();
                            $errorMsg =
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                data.message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div>';
                            $('#login-form-error-msg-section').html($errorMsg);
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log('Error in Operation');
                    }
                });
            }
        };


        document.onreadystatechange = function() {
            let lastScrollPosition = 0;
            const navbar = document.querySelector('.nav-scroll');
            window.addEventListener('scroll', function(e) {
                lastScrollPosition = window.scrollY;

                if (lastScrollPosition > 40)
                    navbar.classList.add('navbar-dark');
                else
                    navbar.classList.remove('navbar-dark');
            });
        }

        function showFlightDetails(data, encdata) {

            airportCityCountry = airportcitycountry;
            airlines = airlines;
            let qry = {!! !empty($qry) ? json_encode($qry) : json_encode(\Illuminate\Support\Facades\Session::get('qry', 'default')) !!};
            let is_return = data.inbound.flightStartAirline ? true : false;
            let flight =
                '<div class="flightBox oneway-trip-tile bg-white rounded ticket-containers flight-div-border" style="display: block" id="fullSegment" show="block" >' +
                '<div class="col-sm-12" style="padding:0px">' +
                '<div class="flight-details-return-round ">' +
                '<div class="sec flights-maj-info">' +
                '<div class="flight-data-section" style="color:#113F6D;">' +
                '<form action="{{ route('postCheckout') }}" method="POST" class="flightForm flightformrz">' +
                '{{ csrf_field() }}' +
                '<div class="row text-center px-0">' +
                '<div class="flex-drct pl-3">';
            if (is_return) {
                flight += '<div class="fle-direction-top">' +
                    getFlightBound(data, 'outbound', is_return) +
                    getFlightBound(data, 'inbound', is_return) +
                    '</div>';
            } else {
                flight += getFlightBound(data, 'outbound', is_return);
            }
            flight += '<div class="' + (is_return ? 'btn-section' : 'btn-section-single-search py-2 px-4') + '">' +
                '<p class="price-deta ' + (is_return ? '' : 'mb-1') + '">PKR ' + formatNumber(data.allSegmentsPrice) +
                '</p>' +
                '<button type="submit" class="btn btn-book btn-sm ' + (is_return ? 'px-4 py-2' : '') +
                '">View details</button>' +
                '</div>' +
                '</div>' +
                '</div>';
            for (var key in qry) {
                flight += '<input type="hidden" name="' + key + '" value="' + qry[key] + '" />';
            }
            flight += '<input type = "hidden" name = "more_option" value = "1" >';
            flight += '<input type="hidden" name="itinerarie" value="' + encdata + '" />';
            flight += '</form>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

            $('#flightDetailsShowBody').html(flight);
            $('#flightDetailsShow').modal('show');
        }

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        function getFlightBound(data, type, is_return) {
            let FlightAirlineName = "";
            let FlightAirlineLogo = "";

            if (data.airLineCode in airlines) {
                FlightAirlineName = airlines[data.airLineCode].name;
                FlightAirlineLogo = airlines[data.airLineCode].thumbnail;
            }
            console.log(FlightAirlineName, FlightAirlineLogo);
            let flightArray = data[type]['flightArray'];
            flightArray = Object.values(flightArray);
            let dateA = moment(data[type]['flightStartDate']);
            let dateB = moment(data[type]['flightEndDate']);
            let timeA = moment(data[type]['flightStartTime'], "hh:mm");
            let timeZ = moment(data[type]['flightEndTime'], "hh:mm");
            bound = '<div class="' + (is_return ? 'flex-for-flight-show align-items-center ' + ($boundType == 'inbound' ?
                    'border-top' : '') : 'flex-for-flight-show-single align-items-center') + '">' +
                '<div class="small-screen-flex">' +
                '<div class="flight-show-txt">' +
                '<img class="fligh-logo" src="' + FlightAirlineLogo + '">' +
                '<p class="flight-name truncate">' + FlightAirlineName + '</p>' +
                '</div>' +
                '<div class="flight-show-txt pt-4">' +
                '<p class="flight-time mb-2">' + timeA.format('h:mm a') + '</p>' +
                '<p class="flight-date">' + dateA.format('ddd DD MMM,YYYY') + '</p>' +
                '</div>' +
                '</div>' +
                '<div class="small-screen-flex">' +
                '<div class="flight-show-txt pt-4">' +
                '<p class="direct-stay-fligt">' + data[type]['totalElapsedTime'] + '</p>' +
                '<div class="flight-direction">';
            flightArray.forEach(sFlight => {
                bound += '<p class="first-di">' + checkAirportByCode(sFlight) + '</p>';
                if (sFlight !== last(sFlight)) {
                    bound += '<p class="strait-line"></p>' +
                        '<img class="plane-img" src="{{ asset('new_changes/images/plane-img.svg') }}">';
                }

            });
            let stops = data[type]['totalStops'];
            bound += '</div>' +
                '<p class="' + (stops != 0 ? "text-danger " : '') + 'direct-stay-fligt mb-0">' + (stops == 0 ? 'Direct' :
                    stops +
                    " stop(s)") + '</p>' +
                '</div>' +
                '</div>' +
                '<div class="small-screen-flex">' +
                '<div class="flight-show-txt pt-4">' +
                '<div>' +
                '<p class="flight-time mb-2">' + timeZ.format('h:mm a') + '</p>' +
                '<p class="flight-date">' + dateB.format('ddd DD MMM,YYYY') + '</p>' +
                '</div>' +

                '</div>' +
                '</div>' +
                '</div>';
            return bound;

        }

        function last(array) {
            return array[array.length - 1];
        }
    </script>
@endsection
