@extends('layouts.default')

@php
use App\Models\Companies;
use App\Models\Share;
use Backpack\Settings\app\Models\Setting;
@endphp


@section('pagetitle')
{{ trans('messages.frontend_dashboard_main_title') }}
@endsection

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-6 text-center">

                <a class="panel__link" href="{{route('phonebook')}}">
                    <div class="panel panel-default fill__panel">
                        <div class="panel-body vcenter">
                            {{ trans('messages.frontend_phonebook') }}
                        </div>
                    </div>
                </a>


            </div>
            <div class="col-md-6 text-center fill__panel">

                <a class="panel__link" href="{{route('company')}}">
                    <div class="panel panel-default">
                        <div class="panel-body vcenter">
                            {{ trans('messages.frontend_orgchart') }}
                        </div>
                    </div>
                </a>

            </div>

        </div>
    </div>

@endsection
