@props(['title' => 'الاسم' , 'required' => false ])

<div {{$attributes->merge(['class' => "d-flex flex-column mb-2 mt-0 "])}} >
    <label class="d-flex align-items-center fs-6 fw-bold form-label ">
        <span class="required">{{$title}} </span>
        @if($required)
            &nbsp;<i class="fa fa-asterisk ms-2 text-danger " style="font-size: 9px" title="{{$title}}"></i>
        @endif
    </label>
    {{$slot}}
</div>
