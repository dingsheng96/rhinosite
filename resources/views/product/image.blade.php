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
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a role="button" href="{{ $thumbnail->full_file_path }}" class="dropdown-item" download>
                        <i class="fas fa-download mr-2 text-success"></i>
                        {{ __('labels.download') }}
                    </a>
                </div>
            </td>
        </tr>
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
                <a role="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('labels.action') }}
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a role="button" href="{{ $image->full_file_path }}" class="dropdown-item" download>
                        <i class="fas fa-download mr-2 text-success"></i>
                        {{ __('labels.download') }}
                    </a>
                    @can('product.delete')
                    <a role="button" href="#" class="dropdown-item" title="{{ __('labels.delete') }}" data-toggle="modal"
                        onclick="event.preventDefault(); deleteAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ route('products.media.destroy', ['product' => $product->id, 'media' => $image->id]) }}')">
                        <i class="fas fa-trash mr-2 text-red"></i>
                        {{ __('labels.delete') }}
                    </a>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">{{ __('messages.no_records') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>