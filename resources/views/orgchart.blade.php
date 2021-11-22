@extends('layouts.default')

@php
use App\Models\Companies;
// use App\Models\Share;
// use Backpack\Settings\app\Models\Setting;
@endphp

@section('pagetitle')
{{ trans('messages.frontend_company_main_title') }}
@endsection

@section('company')
    @parent
    @php
        echo Companies::getCompanyNameBySlug(request('slug'));
    @endphp
@endsection

@section('content')


<div class="col-md-12 w-100">
    {{ Breadcrumbs::render('company.show', request('slug')) }}
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load('current', {packages:["orgchart"]});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');
        data.addRows({!! $data !!});
        var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
        chart.draw(data, {'allowHtml':true});
    }
</script>

<div id="chart_div"></div>

@stop

