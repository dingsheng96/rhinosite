<div class="table-responsive">

    <table id="tbl_image" class="table w-100 table-hover">

        <thead>
            <tr>
                <th scope="col">{{ __('#') }}</th>
                <th scope="col">{{ __('labels.type') }}</th>
                <th scope="col">{{ __('labels.items') }}</th>
                @if (isset($action) && $action)
                <th scope="col">{{ __('labels.action') }}</th>
                @endif
            </tr>
        </thead>

        <tbody>

            @forelse ($media as $medium)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $medium->type }}</td>
                <td>
                    <a href="{{ $medium->full_file_path }}" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i>
                        {{ $medium->original_filename }}
                    </a>
                </td>

                @if (isset($action) && $action)
                <td>
                    @include('admin.components.btn_action', ['no_action' => $medium->is_thumbnail, 'download' => ['route' => $medium->full_file_path, 'attribute' => 'download']])
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ (isset($action) && $action) ? 4 :  3 }}" class="text-center">
                    {{ __('messages.no_records') }}
                </td>
            </tr>
            @endforelse

        </tbody>

    </table>

</div>