@if($data->isEmpty())
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
                <th class="des-head sortable sort-column" data-column="name" data-order="{{ $currentSort === 'name' && $currentOrder === 'asc' ? 'desc' : 'asc' }}"> State @if($currentSort === 'name') <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif </th>
                <th class="date-head sortable sort-column" data-column="created_at" data-order="{{ $currentSort === 'created_at' && $currentOrder === 'asc' ? 'desc' : 'asc' }}">
                    Date @if($currentSort === 'created_at')
                    <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i> @endif
                </th>
                <th class="link-head actions-head">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $dataValue)
            <tr data-id="{{ $dataValue->id }}">
                <td class="table-checkbox">
                    <div class="form-check mb-0 custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="selected" value="{{ $dataValue->id }}" />
                        <label class="form-check-label" for="selected"></label>
                    </div>
                </td>
                <td class="sr-no">{{ $index + 1 }}</td>
                <td class="title-and-cat-wrap col-des">{{ $dataValue->name ?? 'Uncategorized' }}</td>
                <td class="title-and-cat-wrap col-date">
                    {{ $dataValue->created_at->format('d F Y') }}
                </td>
                <td class="col-actions">

                    <a href="{{ url('cms-admin/states/' . $dataValue->id . '/edit') }}" class="edit action-edit" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class='las la-edit'></i>
                    </a>

                    <a href="javascript:void(0)" class="DeleteBtn action-delete" data-id="{{ $dataValue->id }}" data-project-id="{{ $dataValue->id }}" title="Delete" data-toggle="tooltip" data-placement="top">
                        <i class='las la-trash'></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif
