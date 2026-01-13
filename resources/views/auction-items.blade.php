@if($auctions->isEmpty())
    <p>No auctions available.</p>
@else
    @foreach($auctions as $item)
        <article class="auction-item">
            <div class="auction-description">{{ $item->description }}</div>
            <div class="auction-details">
                Price: ₹{{ number_format($item->price, 2) }} <br/>
                Sq.Ft: {{ $item->sq_ft ?? 'N/A' }} <br/>
                State: {{ $item->state->name ?? 'N/A' }} <br/>
                City: {{ $item->city->name ?? 'N/A' }}
            </div>
        </article>
    @endforeach
@endif
