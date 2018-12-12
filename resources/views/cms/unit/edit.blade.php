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
                        {{ __('i18n.updateUnit')}}
                    </h3>
                </div>
            </div>
        </div>
        <!--begin::Form-->
        {!! Form::open(['method' => 'PUT','route' => ['units.update', $data->id]]) !!}
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <span>{!! Form::label('Unit :', '', array('class' => '')) !!}</span>
                <span>{!! Form::text('unit', $data->unit, array('class' => 'form-control m-input', 'required')) !!}</span>
            </div>
        </div>
        <div class="m-portlet__foot m-portlet__foot--fit">
            <div class="m-form__actions">
                <button type="submit" class="btn btn-brand">{{ __('i18n.update') }}</button>
                <a href="{{ route('units.index')}}">
                    <button type="button" class="btn btn-secondary">{{ __('i18n.cancel') }}</button>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
        <!--end::Form-->
    </div>
</div>
@endsection
