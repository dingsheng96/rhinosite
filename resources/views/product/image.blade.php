<table class="table-hover table w-100" id="tbl_image">
    <thead>
        <tr>
            <th style="width: 10%;">{{ __('#') }}</th>
            <th style="width: 20%">{{ __('labels.type') }}</th>
            <th style="width: 50%;">{{ __('labels.filename') }}</th>
            <th style="width: 20%">{{ __('labels.action') }}</th>
        </tr>
    </thead>
    <tbody>

        @if ($thumbnail)
        <tr>
            <td>1</td>
            <td>{{ $thumbnail->type }}</td>
            <td>
                <img src="{{ $thumbnail->full_file_path }}" alt="{{ $thumbnail->filename }}" class="table-img-preview">
                <a href="{{ $thumbnail->full_file_path }}" target="_blank" class="ml-2">
                    <i class="fas fa-external-link-alt"></i>
                    {{ $thumbnail->filename }}
                </a>
            </td>
            <td>
                <a role="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('labels.action') }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a role="button" href="{{ $thumbnail->full_file_path }}" class="dropdown-item" download>
                        <i class="fas fa-download mr-2 text-success"></i>
                        {{ __('labels.download') }}
                    </a>
                </div>
            </td>
        </tr>
        @endif

        @forelse ($images as $image)
        <tr>
            <td>{{ ($loop->iteration + 1) }}</td>
            <td>{{ $image->type }}</td>
            <td>
                <img src="{{ $image->full_file_path }}" alt="{{ $image->filename }}" class="table-img-preview">
                <a href="{{ $image->full_file_path }}" target="_blank" class="ml-2">
                    <i class="fas fa-external-link-alt"></i>
                    {{ $image->filename }}
                </a>
            </td>
            <td>
                @include('components.action', [
                'download' => [
                'route' => $image->full_file_path,
                'attribute' => 'download'
                ],
                'delete' => [
                'permission' => 'product.delete',
                'route' => route('products.media.destroy', ['product' => $product->id, 'medium' => $image->id])
                ]
                ])

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center">{{ __('messages.no_records') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>