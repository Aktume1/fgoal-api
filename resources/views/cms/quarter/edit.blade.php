@extends('cms.layout')

@section('content')
<div class="m-portlet m-portlet--mobile">
    @include('cms.errors.index')
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tab">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon m--hide">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('i18n.updateQuarter')}}
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['method' => 'PUT','route' => ['quarters.update', $data->id]]) !!}
        <div class="m-portlet__body">
            <div class="form-group">
                <span>{!! Form::label('Name :', '', ['class' => '']) !!}</span>
                <span>{!! Form::text('name', $data->name, ['class' => 'form-control', 'required']) !!}</span>
            </div>

            <div class="form-group">
                <span>{!! Form::label('Start date', '', ['class' => '']) !!}</span>
                <span>{!! Form::text('start_date', $data->start_date, ['class' => 'form-control', 'required']) !!}</span>
            </div>

            <div class="form-group">
                <span>{!! Form::label('End date', '', ['class' => '']) !!}</span>
                <span>{!! Form::text('end_date', $data->end_date, ['class' => 'form-control', 'required']) !!}</span>
            </div>

            <div class="form-group">
                <span>{!! Form::label('Expired', '', ['class' => '']) !!}</span>
                <span>{!! Form::text('expried', $data->expried, ['class' => 'form-control']) !!}</span>
            </div>

            <div class="m-form__actions">
                <button type="submit" class="btn btn-brand">{{ __('i18n.update') }}</button>
                <a href="{{ route('quarters.index')}}">
                    <button type="button" class="btn btn-secondary">{{ __('i18n.cancel') }}</button>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
        <!--end::Form-->
    </div>
</div>
@endsection
