<form id="form" enctype="multipart/form-data" method="POST" action="{{route('brands.update', $brand->uuid)}}">
    @csrf
    @method('PUT')
    <div class="row mt-0">

        <x-form.input class="col-sm-12" title="اسم الماركة" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="اسم الماركة" name="name" value="{{$brand->name}}"/>
        </x-form.input>

    </div>
</form>
