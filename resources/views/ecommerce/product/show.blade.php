@extends('layouts.master', ['parent_title' => __('modules.ecommerce'), 'title' => trans_choice('modules.submodules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.submodules.product', 1)]) }}</h3>
                    <div class="card-tools">
                        <h4>{!! $product->status_label !!}</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-5 col-md-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="true">{{ __('labels.general') }}</a>
                                <a class="nav-link" id="vert-tabs-media-tab" data-toggle="pill" href="#vert-tabs-media" role="tab" aria-controls="vert-tabs-media" aria-selected="false">{{ __('labels.media') }}</a>
                                <a class="nav-link" id="vert-tabs-attributes-tab" data-toggle="pill" href="#vert-tabs-attributes" role="tab" aria-controls="vert-tabs-attributes" aria-selected="false">{{ trans_choice('labels.attribute', 2) }}</a>
                            </div>
                        </div>
                        <div class="col-7 col-md-9">
                            <div class="tab-content" id="vert-tabs-tabContent">

                                <div class="tab-pane fade show active" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                                <p id="name" class="form-control">{{ $product->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="category" class="col-form-label">{{ __('labels.category') }}</label>
                                                <p id="category" class="form-control">{{ $product->productCategory->name }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                                                <textarea id="description" cols="100" rows="5" class="form-control bg-white" disabled>{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-media" role="tabpanel" aria-labelledby="vert-tabs-media-tab">
                                    <div class="form-group">
                                        <table class="table table-bordered table-hover" id="tbl_image">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">{{ __('#') }}</th>
                                                    <th style="width: 20%">{{ __('labels.type') }}</th>
                                                    <th style="width: 60%;">{{ __('labels.filename') }}</th>
                                                    <th style="width: 15%">{{ __('labels.action') }}</th>
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
                                                                onclick="event.preventDefault(); deleteAlert('{{ __('labels.confirm_question') }}', '{{ __('labels.delete_info') }}', '{{ route('ecommerce.products.media.destroy', ['product' => $product->id, 'media' => $image->id]) }}')">
                                                                <i class="fas fa-trash mr-2 text-red"></i>
                                                                {{ __('labels.delete') }}
                                                            </a>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">{{ __('labels.no_records') }}</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="vert-tabs-attributes" role="tabpanel" aria-labelledby="vert-tabs-attributes-tab">
                                    {!! $dataTable->table() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('ecommerce.products.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush