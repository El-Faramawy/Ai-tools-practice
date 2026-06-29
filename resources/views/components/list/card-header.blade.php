@props(['name' => 'الرئيسية' , 'add_button' => true , 'delete_button' => false ])
<div class="card-header">
    <h3 class="card-title">{{$name}}</h3>
    <div class="mr-auto pageheader-btn">
        @if($add_button)
        <a href="#" id="addBtn" class="btn btn-primary btn-icon text-white">
            <span>
                <i class="fe fe-plus"></i>
            </span> اضافة جديد
        </a>
        @endif
        @if($delete_button)
            <a href="#" id="multiDeleteBtn" class="btn btn-danger btn-icon text-white">
                <span>
                    <i class="fa fa-trash-o"></i>
                </span> حذف المحدد
            </a>
        @endif
        {{$slot}}
    </div>
</div>
