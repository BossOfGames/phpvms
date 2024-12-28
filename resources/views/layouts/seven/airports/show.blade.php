@extends('app')
@section('title', $airport->full_name)

@section('content')
    <div class="row">
        <div class="col-12">
            <h2>
                {{ $airport->full_name }}
                @if (filled($airport->elevation))
                    <span class="float-right">{{ '| ' . $airport->elevation . 'ft' }}</span>
                @endif
            </h2>
        </div>

        {{-- Show the weather widget in one column --}}
        <div class="col-5">
            <div class="card">
                <div class="card-title">
                    <h4 class="card-header">Weather</h4>
                </div>
                <div class="card-body">
                    {{ Widget::Weather(['icao' => $airport->icao]) }}
                </div>
            </div>
        </div>

        {{-- Show the airspace map in the other column --}}
        <div class="col-7">
            <div class="card">
                <div class="card-title">
                    <h4 class="card-header">Map</h4>
                </div>
                <div class="card-body">
                    {{ Widget::AirspaceMap(['width' => '100%', 'height' => '400px', 'lat' => $airport->lat, 'lon' => $airport->lon]) }}
                </div>
            </div>

            @if (filled($airport->notes))
                <div class="card mt-4">
                    <div class="card-title">
                        <h4 class="card-header">Airport Notes</h4>
                    </div>
                    <div class="card-body">
                        {!! $airport->notes !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
    @if (count($airport->files) > 0 && Auth::check())
        {{-- There are files uploaded and a user is logged in --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-title">
                        <h4 class="card-header">{{ trans_choice('common.download', 2) }}</h4>
                    </div>
                    <div class="card-body">
                        @include('downloads.table', ['files' => $airport->files])
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row mt-4">
        <div class="col-6">
            <div class="card">
                <div class="card-title">
                    <h4 class="card-header">@lang('flights.inbound')</h4>
                </div>
                <div class="card-body">
                    @if (!$inbound_flights)
                        <div class="jumbotron text-center">
                            @lang('flights.none')
                        </div>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-left">@lang('airports.ident')</th>
                                    <th class="text-left">@lang('airports.departure')</th>
                                    <th>@lang('flights.dep')</th>
                                    <th>@lang('flights.arr')</th>
                                </tr>
                            </thead>
                            @foreach ($inbound_flights as $flight)
                                <tr>
                                    <td class="text-left">
                                        <a href="{{ route('frontend.flights.show', [$flight->id]) }}">
                                            {{ $flight->ident }}
                                        </a>
                                    </td>
                                    <td class="text-left">{{ optional($flight->dpt_airport)->name }}
                                        (<a
                                            href="{{ route('frontend.airports.show', ['id' => $flight->dpt_airport_id]) }}">{{ $flight->dpt_airport_id }}</a>)
                                    </td>
                                    <td>{{ $flight->dpt_time }}</td>
                                    <td>{{ $flight->arr_time }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-title">
                    <h4 class="card-header">@lang('flights.outbound')</h4>
                </div>
                <div class="card-body">
                    @if (!$outbound_flights)
                        <div class="jumbotron text-center">
                            @lang('flights.none')
                        </div>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-left">@lang('airports.ident')</th>
                                    <th class="text-left">@lang('airports.arrival')</th>
                                    <th>@lang('flights.dep')</th>
                                    <th>@lang('flights.arr')</th>
                                </tr>
                            </thead>
                            @foreach ($outbound_flights as $flight)
                                <tr>
                                    <td class="text-left">
                                        <a href="{{ route('frontend.flights.show', [$flight->id]) }}">
                                            {{ $flight->ident }}
                                        </a>
                                    </td>
                                    <td class="text-left">{{ $flight->arr_airport->name }}
                                        (<a
                                            href="{{ route('frontend.airports.show', ['id' => $flight->arr_airport->icao]) }}">{{ $flight->arr_airport->icao }}</a>)
                                    </td>
                                    <td>{{ $flight->dpt_time }}</td>
                                    <td>{{ $flight->arr_time }}</td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
