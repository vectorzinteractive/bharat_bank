@if($data->count())
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
            <th>Description</th>
            <th>URDN</th>
            <th>State</th>
            <th>City</th>
            <th>Town</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->name}}</td>
            <td>{!! $item->description !!}</td>
            <td>{{ $item->udrn_id }}</td>
            <td>{{ $item->pincode->town->city->state->name ?? 'N/A' }}</td>
            <td>{{ $item->pincode->town->city->name ?? 'N/A' }}</td>
            <td>{{ $item->pincode->town->name ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-center text-muted">No records available.</p>
@endif
