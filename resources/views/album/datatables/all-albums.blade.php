<table class="table table-striped" id="tableOne">
    <thead>
    <tr>
        <th></th>
        <th class="align-middle pl-2">Name</th>
        <th class="align-middle pl-2">Genre</th>
        <th class="align-middle pl-2">Album Status</th>
        <th class="align-middle pl-2">Request Status</th>
        <th class="align-middle">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($albums as $album)
        <x-slot name="title">
            Server Error
        </x-slot>
        <tr>
            <td class="align-middle pl-2">
                <img
                    width="50"
                    height="50"
                    src="{{ $album->cover_route }}"
                    alt=""
                >
            </td>
            <td class="align-middle pl-2">{{ $album->title }}</td>
            <td class="align-middle pl-2">{{ $album->genre->name }}</td>
            <td class="align-middle pl-2">
                <span class="text-{{ $album->getStatusColor() }}">{{ $album->getStatusText() }}</span>
            </td>

            @php
                $request = App\Models\UserRequest::Where('album_id', $album->id)
                    ->orderby('created_at', 'DESC')
                    ->take(1)
                    ->get();
            @endphp
            @forelse($request as $rq)
                <td class="align-middle pl-2">
                    @if ($rq->status == 0 && $rq->isUpdated == 0)
                        <span class="text-warning">Pending</span>
                    @endif
                    @if ($rq->status == 1 && $rq->isUpdated == 0)
                        <span class="text-success">Accepted</span>
                    @endif
                    @if ($rq->status == 1 && $rq->isUpdated == 1)
                        <span class="text-success">Updated</span>
                    @endif
                    @if ($rq->status == 2 && $rq->isUpdated == 0)
                        <span class="text-danger">Declined</span>
                    @endif
                </td>
            @empty
                <td class="align-middle pl-2">
                    <span class="text-dark">Not Requested</span>
                </td>
            @endforelse
            <td class="align-middle pl-2">
                <div class="table-row-action-group">
                    <a
                        href="{{ route('album', $album->id) }}"
                        class="link text-dark">
                        <i class="fas fa-eye"></i>View
                    </a>
                    <a
                        href="{{ route('album.edit', $album->id) }}"
                        class="link text-info">
                        <i class="fas fa-edit"></i>Edit
                    </a>
                    <a href="{{ route('album.request', $album->id) }}"
                       class="link text-danger">
                        <i
                            class="fas fa-edit"></i>Request
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
