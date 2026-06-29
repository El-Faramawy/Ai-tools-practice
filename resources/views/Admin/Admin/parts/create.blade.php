<form id="form" enctype="multipart/form-data" method="POST" action="{{route('admins.store')}}">
    @csrf
    <div class="row mt-0">

        <x-form.input class="col-sm-12" title="الإسم" :required="true">
            <input type="text" class="form-control form-control-solid" placeholder="الإسم" name="name"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="البريد الالكترونى" :required="true">
            <input type="email" class="form-control form-control-solid" placeholder="البريد الالكترونى" name="email"/>
        </x-form.input>

        <x-form.input class="col-sm-12" title="كلمة المرور" :required="true">
            <input type="password" class="form-control form-control-solid" placeholder="كلمة المرور" name="password"/>
        </x-form.input>
    </div>
</form>

