@props(['name' => 'اللوجو'])
<div class="card shadow">
    <div class="card-header">
        <h3 class="mb-0 card-title">{{$name}}</h3>
    </div>
    <div class="card-body">
        {{$slot}}
    </div>
</div>
