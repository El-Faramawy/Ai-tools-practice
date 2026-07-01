@extends('layouts.admin.app')
@section('page_title')
    الماركات
@endsection
@section('content')
    <x-list.card>
        <x-list.card-header name="الماركات" :add_button="true" :delete_button="false"></x-list.card-header>
        <x-list.card-body :responsive="true">
            <th class="text-white">#</th>
            <th class="text-white">اسم الماركة</th>
            <th class="text-white">تاريخ الإضافة</th>
            <th class="text-white">تحكم</th>
        </x-list.card-body>
    </x-list.card>

    <x-list.modal name="الماركات" :save_button="true"></x-list.modal>

@endsection
@push('admin_js')

    <script>
        var columns = [
            {data: 'id',         name: 'id'},
            {data: 'name',       name: 'name'},
            {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
            {data: 'action',     name: 'action',     orderable: false, searchable: false},
        ];
    </script>
    @include('layouts.admin.inc.ajax', ['url' => 'brands'])

@endpush
