

   @if($users->isEmpty())
                                         <div id="no-data-message" class="alert alert-warning" style="display:block;">
                                             No data to display.
                                         </div>
                                     @else
                                     <div class="table-responsive" id="data-table">

                                        <table class="mtable table table-hover data-custom-table downloads-table" id="projectData-table">
                                        <thead>
                                            <tr class="tablehead">
                                                <th class="sr-no">#</th>

                                                <th class="des-head sortable sort-column" data-column="name"
                                                    data-order="{{ $currentSort === 'name' && $currentOrder === 'asc' ? 'desc' : 'asc' }}">
                                                    UserName
                                                    @if($currentSort === 'name')
                                                        <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </th>
                                                <th class="des-head sortable sort-column" data-column="email"
                                                    data-order="{{ $currentSort === 'email' && $currentOrder === 'asc' ? 'desc' : 'asc' }}">
                                                    User Email
                                                    @if($currentSort === 'email')
                                                        <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </th>
                                                <th class="des-head sortable sort-column" data-column="name"
                                                    data-order="{{ $currentSort === 'name' && $currentOrder === 'asc' ? 'desc' : 'asc' }}">
                                                    Role
                                                    @if($currentSort === 'name')
                                                        <i class="las la-arrow-{{ $currentOrder === 'asc' ? 'up' : 'down' }}"></i>
                                                    @endif
                                                </th>

                                                @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
                                                    <th class="link-head actions-head">Actions</th>
                                                @endif

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $index => $user)
                                                <tr data-id="{{$user->id}}">
                                                    <td class="sr-no">{{ $index + 1 }}</td>
                                                    <td class="col-des">
                                                        <div class="blog-cat-wrap mb-2">
                                                            {{$user->name}}

                                                        </div>
                                                    </td>
                                                    <td class="col-des">
                                                        <div class="blog-cat-wrap mb-2">
                                                            {{$user->email}}
                                                        </div>
                                                    </td>
                                                    <td class="col-des">
                                                        <div class="blog-cat-wrap mb-2">
                                                            {{
                                                                $user->is_owner
                                                                    ? 'Owner'
                                                                    : ($user->getRoleNames()->isNotEmpty()
                                                                        ? $user->getRoleNames()
                                                                            ->map(fn ($role) => ucwords(str_replace('-', ' ', $role)))
                                                                            ->implode(', ')
                                                                        : '-')
                                                            }}
                                                        </div>
                                                    </td>
                                                      @if(auth()->user()->hasAnyRole(['super-admin', 'admin']))
                                                        <td class="col-actions">
                                                            <a href="{{ url('cms-admin/users/' . $user->id . '/edit') }}" class="action-edit" title="Edit">
                                                                <i class='las la-edit'></i>
                                                            </a>

                                                            <a href="javascript:void(0)"
                                                            class="userDeleteBtn action-delete"
                                                            data-id="{{ $user->id }}"
                                                            title="Delete">
                                                                <i class='las la-trash'></i>
                                                            </a>
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>

                                    </div>
                                    <div class="mt-4">
                                    {{ $users->links('pagination::bootstrap-5') }}
                                    </div>
                                    @endif
