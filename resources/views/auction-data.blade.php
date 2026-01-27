@if($data->count())
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Description</th>
            <th>State</th>
            <th>City</th>
            <th>Town</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{!! $item->description !!}</td>
            <td>{{ $item->pincode->town->city->state->name ?? 'N/A' }}</td>
            <td>{{ $item->pincode->town->city->name ?? 'N/A' }}</td>
            <td>{{ $item->pincode->town->name ?? 'N/A' }}</td>
            <td>{{ number_format($item->price) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-center text-muted">No auctions available.</p>
@endif
