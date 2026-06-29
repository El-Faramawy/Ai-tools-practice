@props(['name' => 'الاسم'])

<div {{$attributes}} class="d-flex flex-column mb-2 fv-row">
    <label class="d-flex align-items-center fs-6 fw-bold form-label ">
        <span class="required">{{$name}}</span>
        <i class="fa fa-asterisk ms-2 fs-7 text-danger " style="font-size: 9px" title="{{$name}}"></i>
    </label>
    <div class="d-flex align-items-center mb-3">
        {{$slot}}
    </div>
</div>
