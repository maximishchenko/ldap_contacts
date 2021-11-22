@extends('layouts.default')

@php
use App\Models\Companies;
use App\Models\Share;
use Backpack\Settings\app\Models\Setting;
@endphp

@section('pagetitle')
{{ trans('messages.frontend_phonebook_main_title') }}
@endsection
@section('company')
    @parent
    @php
        echo Companies::getCompanyNameBySlug(request('slug'));
    @endphp
@endsection

@section('content')



<style>
.row {
    margin-right: 0 !important;
}
tbody:nth-child(odd) {
	background: #CCC;
}
</style>

<div class="col-md-3">
    <div class="sidebar">
        <div class="sidebar__title">
            <h5>
                {{ trans('messages.companies') }}
            </h5>
        </div>
        <ul class="sidebar__list tree">
            <li class="sidebar__item {{ (request()->path() == '/phonebook') ? 'active' : '' }}">
                {{-- <a href="{{ config('app.url') }}/phonebook">{{ trans('messages.frontend_companies_all') }}</a> --}}
                <a href="{{ url('/phonebook') }}">{{ trans('messages.frontend_companies_all') }}</a>
            </li>
            @foreach ($model->getParentCompanies() as $key_company => $company)
                <li class="sidebar__item {{ ($model->getChildCompaniesCount($company->id)) ? 'section' : '' }} {{ ($company->slug == request('slug')) ? 'active' : '' }}">
				@if($model->getChildCompaniesCount($company->id))
					<input type="checkbox" id="<?php echo $key_company; ?>" <?php echo ($model->isTreeOpened($company->id, request('slug'))) ? 'checked' : '' ?> />
					<label class="tree__label" for="<?php echo $key_company; ?>" style="">
				@endif
                    {{-- <a href="{{ config('app.url') . '/phonebook/' . $company->slug }}">{!! $company->name !!}</a> --}}
                    <a href="{{ url('/phonebook', ['slug' => $company->slug]) }}">{!! $company->name !!}</a>
				@if($model->getChildCompaniesCount($company->id))
					</label>

					<ul class="sidebar__child__item">
					@foreach ($model->getChildsArray($company->id) as $childCompany)
						<li class="sidebar__item {{ ($childCompany->slug == request('slug')) ? 'active' : '' }}">
							{{-- <a class="child__item" href="{{ config('app.url') . '/phonebook/' . $childCompany->slug }}">{!! $childCompany->name !!}</a> --}}
							<a class="child__item" href="{{ url('/phonebook', ['slug' => $childCompany->slug]) }}">{!! $childCompany->name !!}</a>
						</li>
					@endforeach
					</ul>

				@endif

                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="col-md-9">

    <!--  -->
    <div class="mb-3" id="search">
            <form id="contactsSearch" action="?" method="GET" autocomplete="off">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="form-group__wrap">
                                <label>
                                    {{ trans('messages.search_displayName') }}
                                </label>
                                <input id="displayName" name="displayName" class="form-control" autofocus="on" placeholder="" value="{{ request('displayName') }}">
                                <div id="namesList"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input class="btn btn-block btn-success btn-lg" type="submit" value="{{ trans('messages.search') }}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <a href="{{ config('app.url') }}" class="btn btn-block btn-linkedin btn-lg" >{{ trans('messages.reset') }}</a>
                        </div>
                    </div>
                </div>
            </form>
    </div>
    <!--  -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ trans('messages.table_department') }}</th>
                    <th>{{ trans('messages.table_title') }}</th>
                    <th>{!! trans('messages.table_displayName') !!}</th>
                    {{-- <th class="w100px">{!! trans('messages.table_telephoneNumber') !!}</th> --}}
                    {{-- <th class="w100px">{!! trans('messages.table_homePhone') !!}</th> --}}
                    <th class="w100px">{!! trans('messages.table_phones') !!}</th>
                    <th>{{ trans('messages.table_mail_post') }}</th>
                    <th class="w100px">{{ trans('messages.table_physicalDeliveryOfficeName') }}</th>
                </tr>
            </thead>
			<tbody>
    @foreach ($model->getCompanies(request('slug'), request('displayName')) as $key => $company)
            <tr>
                <td colspan="8" class="table-row-document-title">
                    <div class="row">
                        <div class="col-md-9">
                            <h5>{{ $company->name }}</h5>
                            <fieldset>
                                <legend>
                                    {{ trans('messages.officeal_post_and_reference_details') }}<br/>
                                    <a class="font12px" href="javascript:void(0)" onclick="showHide('{{ $company->slug }}')">{{ trans('messages.hide_show') }}</a>
                                </legend>

                                <div style="display: none;" class="additional" id="{{ $company->slug }}">
                                    <span class="block">
                                        <b>{{ trans('messages.companies_address') }}:</b> {{ $company->address }}
                                    </span>
                                    <span class="block">
                                        <b>{{ trans('messages.companies_reception_phone') }}:</b> {{ $company->reception_phone }}
                                    </span>
                                    <span class="block">
                                        <b>{{ trans('messages.companies_phone') }}:</b> {{ $company->phone }}
                                    </span>
                                    {{-- <span class="block">
                                        <b>{{ trans('messages.companies_fax_city') }}:</b> {{ $company->fax_city }}
                                    </span> --}}
                                    <span class="block">
                                        <b>{{ trans('messages.companies_email') }}:</b> {{ $company->email }}
                                    </span>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-3 text-right">
                            <div style="display: inline-block;">
                                <b>{{ trans('messages.save') }}</b> &nbsp; &nbsp;
                                <a href="{{ url('/phonebook/excel', ['slug' => $company->slug ]) }}" target="_blank">
                                    <i class="icon icon-doc" style="vertical-align: middle">
                                        <span class="icon-doc__type">

                                        </span>
                                    </i>
                                </a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @foreach ($model->getDepartments($company->name, request('displayName')) as $department)
                <tr>
                    <td rowspan="{{ $model->getCountContacts($company->name, $department->department, request('displayName')) }}">
                        <b>{{ $department->department }}</b>
                    </td>
                    @foreach($model->getContacts($company->name, $department->department, request('displayName')) as $contact)
                        <td>
                            {{ $contact->title }}
                        </td>
                        <td>
                            {{ $contact->displayName }}
                        </td>
                        {{-- <td>
                            {{ $contact->telephoneNumber }}
                        </td>
                        <td>
                            {{ $contact->homePhone }}
                        </td> --}}
                        <td>
                            @if (!empty($contact->telephoneNumber))
                                <b>{{ trans('messages.telephoneNumber') }}</b> {{ $contact->telephoneNumber }}
                                <br>
                            @endempty
                            @if (!empty($contact->homePhone))
                                <b>{{ trans('messages.homePhone') }}</b> {{ $contact->homePhone }}
                                <br>
                            @endempty
                            @if (!empty($contact->ipPhone))
                                <b>{{ trans('messages.ipPhone') }}</b> {{ $contact->ipPhone }}
                                <br>
                            @endempty
                            @if (!empty($contact->mobile))
                                <b>{{ trans('messages.mobile') }}</b> {{ $contact->mobile }}
                                <br>
                            @endempty
                        </td>
                        <td>
                            <a href="mailto://{{ $contact->mail }}">{{ $contact->mail }}</a>
                        </td>
                        <td>
                            {{ $contact->physicalDeliveryOfficeName }}
                        </td>
                </tr>
                    @endforeach
            @endforeach
    @endforeach
			</tbody>
        </table>
</div>



@if (Setting::get('ENABLE_AUTOCOMPLETE') == Share::STATUS_ACTIVE)
<script>
// Автодополнение
$(document).ready(function(){
    $('#displayName').keyup(function(){
        var query = $(this).val();
        if(query != '') {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('autocomplete') }}",
                method:"GET",
                data:{displayName:query, company: "<?php echo Companies::getCompanyNameBySlug(request('slug')); ?>", _token:_token},
                success:function(data){
                    $('#namesList').fadeIn();
                    $('#namesList').html(data);
                }
            });
        }
    });

    $(document).on('click', '.autocomplete', function(){
        $('#displayName').val($(this).text());
        $('#namesList').fadeOut();
        $('form#contactsSearch').submit();
    });
});
</script>
@endif

<script>
// Показать/скрыть div по id
function showHide(element_id) {
    var x = document.getElementById(element_id);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>


@stop


