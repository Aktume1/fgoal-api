@extends('cms.layout')

@section('content')
<div class="m-portlet m-portlet--mobile">
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @include('cms.errors.index')
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{ translate('quarter.quarterList')}}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <span>
                    <button type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" data-toggle="modal"
                        data-target="#add"><i class="la la-plus"></i> {{ translate('activity.add') }}</button>
                </span>

                <li class="m-portlet__nav-item"></li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline" id="dataTables">
            <thead>
                <tr>
                    <th>Num</th>
                    <th>{{ translate('quarter.quarterList') }}</th>
                    <th>{{ translate('quarter.start_date') }}</th>
                    <th>{{ translate('quarter.end_date') }}</th>
                    <th>{{ translate('quarter.expiredQuarter') }}</th>
                    <th>{{ translate('activity.actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @php $num = 0 @endphp
                @foreach ($data->quarters as $quarter)
                <tr class='odd gradeX'>
                    <td>{{ ++$num }}</td>
                    <td>
                        {{ $quarter->name }}

                        @if ($quarter->start_date == $data->current_quarter->start_date && $quarter->end_date == $data->current_quarter->end_date)
                            <i class="far fa-calendar-check"></i>
                        @endif
                    </td>
                    <td>{{ $quarter->start_date }}</td>
                    <td>{{ $quarter->end_date }}</td>
                    <td>
                        @if ($quarter->expried)
                            {{ translate('quarter.expiredQuarter') }}
                        @else
                            {{ translate('quarter.unExpiredQuarter') }}
                        @endif
                    </td>
                    <td class='center'>
                        {!! Form::open(['route' => ['quarters.show', $quarter->id], 'method' => 'GET', 'class' => 'form-admin', 'style' => 'display: inline-block;']) !!}
                            {!! Form::button('<span><i class="fas fa-edit"></i></span>', ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
                        {!! Form::close() !!}
                        <span>
                            <button type="button" data-toggle="modal" data-target="#delete{{ $quarter->id }}" class=" btn btn-danger form-admin"
                                aria-hidden="true"><i class="fa fa-trash" aria-hidden="true" style="display: inline-block;"></i></button>
                        </span>
                    </td>
                </tr>
                <!-- Delete Unit-->
                <div class="modal fade" id="delete{{ $quarter->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ translate('quarter.deleteQuarter') }}</h4>
                            </div>
                            <div class="modal-body">
                                <h5>{{ translate('activity.deleteConfirm') }}</h5>
                            </div>
                            <div class="modal-footer">
                                {!! Form::open(['route' => ['quarters.destroy', $quarter->id], 'method' => 'DELETE']) !!}
                                    {!! Form::button(translate('activity.delete'), ['class' => 'btn btn-danger pull-left', 'type' => 'submit']) !!}
                                    {!! Form::button(translate('activity.close'), ['class' => 'btn btn-secondary', 'data-dismiss' => 'modal', 'type' => 'button']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end -->
                @endforeach
            </tbody>
        </table>
        <!-- Modal create-->
        <div class="modal fade" id="add" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ translate('quarter.addQuarter')}}</h4>
                    </div>
                    {!! Form::open(['method' => 'POST', 'route' => 'quarters.store']) !!}
                    <div class="modal-body">
                        <div class="form-group">
                            <span>{!! Form::label('Name :', '', ['class' => '']) !!}</span>
                            <span>{!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}</span>
                        </div>

                        <div class="form-group">
                            <span>{!! Form::label('Start date', '', ['class' => '']) !!}</span>
                            <span>{!! Form::text('start_date', null, ['class' => 'form-control', 'required']) !!}</span>
                        </div>

                        <div class="form-group">
                            <span>{!! Form::label('End date', '', ['class' => '']) !!}</span>
                            <span>{!! Form::text('end_date', null, ['class' => 'form-control', 'required']) !!}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::button(translate('quarter.addQuarter'), ['class' => 'btn btn-info pull-right', 'type' => 'submit']) !!}
                        {!! Form::button(translate('activity.close'), ['class' => 'btn btn-default pull-left', 'type' => 'button']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
