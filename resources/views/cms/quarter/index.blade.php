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
                    {{ __('i18n.quarterList') }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <span>
                    <button type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" data-toggle="modal"
                        data-target="#myModal"><i class="la la-plus"></i> {{ __('i18n.add') }}</button>
                </span>
                </a>
                <li class="m-portlet__nav-item"></li>
            </ul>
        </div>
    </div>
    <div class="m-portlet__body">
        <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                id="m_table_1" role="grid" aria-describedby="m_table_1_info" style="width: 1210px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('i18n.quarter')}}</th>
                        <th>{{ __('i18n.start_date') }}</th>
                        <th>{{ __('i18n.end_date') }}</th>
                        <th>{{ __('i18n.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->quarters as $quarter)
                    <tr>
                        <td>{{ $quarter->id }}</td>
                        <td>
                            {{ $quarter->name }}

                            @if ($quarter->start_date == $data->current_quarter->start_date && $quarter->end_date ==
                            $data->current_quarter->end_date)
                            <i class="fas fa-calendar-check"></i>
                            @endif
                        </td>
                        <td>{{ $quarter->start_date }}</td>
                        <td>{{ $quarter->end_date }}</td>
                        <td>
                            <span>
                                <a href="{{ route('quarters.show', $quarter->id) }}">
                                    <button type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air">{{
                                        __('i18n.update') }}</button>
                                </a>
                            </span>
                            <span>
                                <span>
                                    <button type="button" data-toggle="modal" data-target="#delete{{ $quarter->id }}"
                                        class="btn btn-danger m-btn m-btn--custom m-btn--icon m-btn--air">{{
                                        __('i18n.delete') }}</button>
                                </span>
                            </span>
                        </td>
                        <!-- Modal create-->
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ __('i18n.addQuarter')}}</h4>
                                    </div>

                                    {!! Form::open(['method' => 'POST', 'route' => 'quarters.store']) !!}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <span>{!! Form::label('Name', '', ['class' => '']) !!}</span>
                                            <span>{!! Form::text('name', null, ['class' => 'form-control', 'required'])
                                                !!}</span>

                                            <div class="form-group">
                                                <span>{!! Form::label('Start date', '', ['class' => '']) !!}</span>
                                                <span>{!! Form::text('start_date', null, ['class' => 'form-control',
                                                    'required']) !!}</span>
                                            </div>

                                            <div class="form-group">
                                                <span>{!! Form::label('End date', '', ['class' => '']) !!}</span>
                                                <span>{!! Form::text('start_date', null, ['class' => 'form-control',
                                                    'required']) !!}</span>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-info pull-right">{{
                                                __('i18n.addQuarter') }}</button>
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{
                                                __('i18n.close') }}</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Quarter-->
                            <div class="modal fade" id="delete{{ $quarter->id }}" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title">{{ __('i18n.deleteQuarter') }}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <h5>{{ trans('i18n.deleteConfirm') }}</h5>
                                        </div>
                                        <div class="modal-footer">
                                            {!! Form::open(['route' => ['quarters.destroy', $quarter->id], 'method' =>
                                            'DELETE']) !!}
                                            <button type="submit" class="btn btn-danger pull-left">{{ __('i18n.delete')
                                                }}</button>
                                            {!! Form::close() !!}
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{
                                                trans('i18n.close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end -->
                        </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
