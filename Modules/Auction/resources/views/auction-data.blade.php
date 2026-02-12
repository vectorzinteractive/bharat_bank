@if($projects->isEmpty())
<div id="no-data-message" class="alert alert-warning" style="display:block;">
    No data to display.
</div>
@else
<div class="table-responsive" id="data-table">
    <table class="mtable table table-hover data-custom-table downloads-table" id="data-table">
        <thead>
            <tr class="tablehead">
                <th class="table-checkbox">
                    <div class="form-check mb-0 custom-checkbox">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll"></label>
                    </div>
                </th>
                <th class="sr-no">#</th>
                <th class="des-head sortable sort-column" data-column="description" data-order="{{ $currentSort === 'description' && $currentOrder === 'asc' ? 'desc' : 'asc' }}"> Description @if($currentSort === 'description') <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif </th>
                <th class="des-head sortable sort-column" data-column="description" data-order="{{ $currentSort === 'description' && $currentOrder === 'asc' ? 'desc' : 'asc' }}"> State @if($currentSort === 'description') <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif </th>
                <th class="des-head sortable sort-column" data-column="description" data-order="{{ $currentSort === 'description' && $currentOrder === 'asc' ? 'desc' : 'asc' }}"> City @if($currentSort === 'description') <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif </th>
                <th class="des-head sortable sort-column" data-column="description" data-order="{{ $currentSort === 'description' && $currentOrder === 'asc' ? 'desc' : 'asc' }}"> Town @if($currentSort === 'description') <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif </th>
                <th class="link-head actions-head">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($projects as $index => $data)
            <tr data-id="{{ $data->id }}">
                <td class="table-checkbox">
                    <div class="form-check mb-0 custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="selected" value="{{ $data->id }}" />
                        <label class="form-check-label" for="selected"></label>
                    </div>

                </td>

                <td class="sr-no">{{ $index + 1 }}</td>
                <td class="title-and-cat-wrap col-des">
                    {!! Str::words(strip_tags($data->description), 10, '.....') !!}
                </td>

                <td class="title-and-cat-wrap col-des">
                   {{ Str::title($data->pincode->town->city->state->name ?? '') }}
                </td>
                <td class="title-and-cat-wrap col-des">
                    {{ Str::title($data->pincode->town->city->name ?? '') }}
                </td>
                <td class="title-and-cat-wrap col-des">
                    {{ Str::title($data->pincode->town->name ?? '') }}
                </td>

                <td class="col-actions">
                    <a href="javascript:void(0)" title="View" class="action-view" data-toggle="tooltip" data-placement="top" aria-label="View">
                        <i class="las la-eye"></i>
                    </a>

                    <a href="{{ url('cms-admin/auctions/' . $data->id . '/edit') }}" title="Edit" class="action-edit" data-toggle="tooltip" data-placement="top">
                        <i class='las la-edit'></i>
                    </a>

                    <a href="javascript:void(0)" class="DeleteBtn action-delete" data-id="{{ $data->id }}" data-project-id="{{ $data->id }}" title="Delete" data-toggle="tooltip" data-placement="top">
                        <i class='las la-trash'></i>
                    </a>

                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ $projects->links('pagination::bootstrap-5') }}
</div>
@endif
