@props(['responsive' => false])

<div class="card-body">
    <div class="{{$responsive ? 'table-responsive' : ''}}">
        <table id="exportexample" class="table table-striped table-responsive-lg  card-table table-vcenter text-nowrap mb-0 table-primary align-items-center mb-0">
            <thead class="bg-primary text-white">
                <tr>
                    {{$slot}}
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>
