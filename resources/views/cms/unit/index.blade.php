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
                    {{ __('i18n.unitlist') }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <span>
                    <i class="la la-plus"></i>
                    <button type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" data-toggle="modal"
                        data-target="#myModal">{{ __('i18n.add') }}</button>
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
                        <th>{{ __('i18n.unit')}}</th>
                        <th>{{ __('i18n.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $unit)
                    <td>{{ $unit->id }}</td>
                    <td>{{ $unit->id }}</td>
                    <td>{{ $unit->unit}}</td>
                    <td>
                        <span>
                            <a href="{{ route('units.show', $unit->id) }}">
                                <button type="button" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air">{{
                                    __('i18n.update') }}</button>
                            </a>
                        </span>
                        <span>
                            <span>
                                <button type="button" data-toggle="modal" data-target="#delete{{$unit->id}}" class="btn btn-danger m-btn m-btn--custom m-btn--icon m-btn--air">{{
                                    __('i18n.delete') }}</button>
                            </span>
                        </span>
                        </tr>
                        <!-- Modal create-->
                        <div class="modal fade" id="myModal" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">{{ __('i18n.addUnit')}}</h4>
                                    </div>
                                    {!! Form::open(['method' => 'POST','route' => 'units.store']) !!}
                                    <div class="modal-body">
                                        <span>{!! Form::label('Unit :', '', array('class' => '')) !!}</span>
                                        <span>{!! Form::text('unit', null, array('class' => 'form-control',
                                            'required')) !!}</span>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-info pull-right">{{ __('i18n.addUnit') }}</button>
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{
                                            __('i18n.close') }}</button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Unit-->
                        <div class="modal fade" id="delete{{ $unit->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <h4 class="modal-title">{{ __('i18n.deleteUnit') }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>{{ trans('i18n.deleteConfirm') }}</h5>
                                    </div>
                                    <div class="modal-footer">
                                        {!! Form::open(['route' => ['units.destroy', $unit->id], 'method' => 'DELETE'])
                                        !!}
                                        <button type="submit" class="btn btn-danger pull-left">{{ __('i18n.delete') }}</button>
                                        {!! Form::close() !!}
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{
                                            trans('i18n.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end -->
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
