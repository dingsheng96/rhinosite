@extends('layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.product', 1)]) }}</h3>
                </div>

                <form action="{{ route('products.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-5 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="project-tabs" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ __('labels.general') }}</a>
                                    <a class="nav-link" id="image-tab" data-toggle="pill" href="#image" role="tab" aria-controls="image" aria-selected="false">{{ trans_choice('labels.image', 2) }}</a>
                                </div>
                            </div>
                            <div class="col-7 col-sm-9">
                                <div class="tab-content" id="project-tabs-tabContent">
                                    <div class="tab-pane text-left fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                                                    @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="category" class="col-form-label">{{ __('labels.category') }} <span class="text-red">*</span></label>
                                                    <select name="category" id="category" class="form-control select2 @error('category') is-invalid @enderror">
                                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.category'))]) }} ---</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category', $product->product_category_id) == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('category')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                                        @foreach ($statuses as $status => $text)
                                                        <option value="{{ $status }}" {{ old('status', $product->status) == $status ? 'selected' : null }}>{{ $text }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                                                    <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                                    @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="thumbnail" class="col-form-label">{{ __('labels.change_thumbnail') }}</label>

                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <input type="file" id="thumbnail" name="thumbnail" class="form-control-file custom-img-input @error('thumbnail') is-invalid @enderror" required accept=".jpg,.jpeg,.png">
                                                            @error('thumbnail')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                            <ul class="pl-3">{!! trans_choice('messages.upload_image_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($max_files > 0)
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                                                    <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png" data-action="update">
                                                        <div class="dz-default dz-message">
                                                            <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                                            <h4>{{ __('messages.drag_and_drop') }}</h4>
                                                            <ul class="list-unstyled">{!! trans_choice('messages.upload_image_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files]) !!}</ul>
                                                        </div>
                                                    </div>
                                                    @error('files')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <hr>

                                        <div class="form-group table-responsive">
                                            @include('product.image')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('products.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-times"></i>
                            {{ __('labels.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.view', ['module' => trans_choice('labels.attribute', 2)]) }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('products.attributes.create', ['product' => $product->id]) }}" role="button" class="btn btn-outline-primary btn-rounded-corner mx-2">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('labels.attribute', 1)]) }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush