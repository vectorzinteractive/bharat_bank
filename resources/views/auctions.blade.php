<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Auction Filter UI</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      min-height: 100vh;
    }
    .filter-panel {
      background: white;
      width: 320px;
      padding: 20px;
      box-shadow: 2px 0 8px rgb(0 0 0 / 0.1);
      overflow-y: auto;
      height: 100vh;
      box-sizing: border-box;
    }
    .filter-section {
      margin-bottom: 30px;
    }
    .filter-section strong {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 16px;
      color: #222;
    }
    label {
      display: block;
      margin: 6px 0;
      cursor: pointer;
      user-select: none;
      font-size: 14px;
      color: #333;
    }
    input[type=checkbox] {
      margin-right: 8px;
      cursor: pointer;
      vertical-align: middle;
    }
    input[type=range] {
      width: 100%;
      cursor: pointer;
      margin-top: 6px;
    }
    .sqft-inputs {
      display: flex;
      gap: 12px;
    }
    .sqft-inputs input[type=number] {
      flex: 1;
      padding: 8px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    #price-label {
      font-weight: 600;
      color: #007bff;
    }
    .content-area {
      flex-grow: 1;
      padding: 40px;
      overflow-y: auto;
    }
    .auction-item {
      background: white;
      border-radius: 6px;
      padding: 15px 20px;
      margin-bottom: 20px;
      box-shadow: 0 0 8px rgb(0 0 0 / 0.05);
    }
    .auction-description {
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 6px;
      color: #333;
    }
    .auction-details {
      color: #555;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <aside class="filter-panel" aria-label="Filters">
    <div class="filter-section">
      <strong>State</strong>
      @foreach ($states as $state)
        <label>
          <input type="checkbox" name="state[]" value="{{ $state->id }}" class="state-checkbox" />
          {{ $state->name }}
        </label>
      @endforeach
    </div>

    <div class="filter-section">
      <strong>City</strong>
      @foreach ($cities as $city)
        <label>
          <input type="checkbox" name="city[]" value="{{ $city->id }}" class="city-checkbox" />
          {{ $city->name }}
        </label>
      @endforeach
    </div>

    <div class="filter-section">
      @php
        $maxPrice = $auction->max('price') ?? 10000000;
      @endphp
      <label for="price">Max Price: <span id="price-label">0</span></label>
      <input
        type="range"
        id="price"
        name="price"
        min="0"
        max="{{ $maxPrice }}"
        value="0"
        step="1000"
      />
    </div>

    <div class="filter-section">
      <strong>Sq.Ft</strong>
      <div class="sqft-inputs">
        <input
          type="number"
          id="sqft-min"
          name="sqft_min"
          min="0"
          step="0.01"
          placeholder="Min Sq.Ft"
          aria-label="Minimum square feet"
        />
        <input
          type="number"
          id="sqft-max"
          name="sqft_max"
          min="0"
          step="0.01"
          placeholder="Max Sq.Ft"
          aria-label="Maximum square feet"
        />
      </div>
    </div>
  </aside>

  <main class="content-area" aria-label="Auction Listings">
    <h1>Auction Listings</h1>
    @php
      $latestAuctions = $auction->sortByDesc('created_at');
    @endphp
    @forelse ($latestAuctions as $item)
      <article class="auction-item">
        <div class="auction-description">{{ $item->description }}</div>
        <div class="auction-details">
          Price: ₹{{ number_format($item->price, 2) }} <br />
          Sq.Ft: {{ $item->sq_ft ?? 'N/A' }} <br />
          State: {{ $item->state->name ?? 'N/A' }} <br />
          City: {{ $item->city->name ?? 'N/A' }}
        </div>
      </article>
    @empty
      <p>No auctions available.</p>
    @endforelse
  </main>

  <script src="{{ asset('/assets/js/custom.js') }}" type="text/javascript"></script>
</body>
</html>
