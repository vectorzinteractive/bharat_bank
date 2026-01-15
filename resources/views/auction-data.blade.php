@if($auctions->count())
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
        @foreach($auctions as $index => $auction)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{!! $auction->description !!}</td>
            <td>{{ $auction->pincode->town->city->state->name ?? 'N/A' }}</td>
            <td>{{ $auction->pincode->town->city->name ?? 'N/A' }}</td>
            <td>{{ $auction->pincode->town->name ?? 'N/A' }}</td>
            <td>{{ number_format($auction->price) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-center text-muted">No auctions available.</p>
@endif
