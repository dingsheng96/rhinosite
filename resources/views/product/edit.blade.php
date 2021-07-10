@extends('layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.product', 1)]) }}</h3>
                </div>

                <form action="{{ route('products.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">

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
                                    <textarea name="description" id="description" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.description'))]) }}"
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail" class="col-form-label">{{ __('labels.upload_thumbnail') }}</label>
                            <div class="custom-file">
                                <input type="file" id="thumbnail" name="thumbnail" class="custom-file-input custom-img-input @error('thumbnail') is-invalid @enderror" required accept=".jpg,.jpeg,.png">
                                <label class="custom-file-label" for="thumbnail">Choose file</label>
                                @error('thumbnail')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="row my-3">
                                <div class="col-12 col-md-5">
                                    <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                </div>
                                <div class="col-12 col-md-7">
                                    <ul>{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG', 'dimension' => '1024x1024']) !!}</ul>
                                </div>
                            </div>
                        </div>

                        @if ($max_files > 0)
                        <div class="form-group">
                            <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                            <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png" data-action="update">
                                <div class="dz-default dz-message">
                                    <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                    <h4>{{ __('messages.drag_and_drop') }}</h4>
                                </div>
                            </div>
                            @error('files')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <ul>{!! trans_choice('messages.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files, 'dimension' => '1024x1024']) !!}</ul>
                        </div>
                        @endif

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

        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.view', ['module' => trans_choice('labels.image', 2)]) }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    @include('product.image')
                </div>
            </div>

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