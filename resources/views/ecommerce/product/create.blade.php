@extends('layouts.master', ['parent_title' => __('modules.ecommerce'), 'title' => trans_choice('modules.submodules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.create', ['module' => trans_choice('modules.submodules.product', 1)]) }}</h3>
                </div>

                <form action="{{ route('ecommerce.products.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

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
                                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
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
                                                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
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
                                                        <option value="{{ $status }}" {{ old('status', 'active') == $status ? 'selected' : null }}>{{ $text }}</option>
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
                                                    <label for="description" class="col-form-label">{{ __('labels.description') }} <span class="text-red">*</span></label>
                                                    <textarea name="description" id="description" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.description'))]) }}"
                                                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                                    @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-media" role="tabpanel" aria-labelledby="vert-tabs-media-tab">
                                        <div class="form-group">
                                            <label for="thumbnail" class="col-form-label">{{ __('labels.upload_thumbnail') }} <span class="text-red">*</span></label>
                                            <div class="row">
                                                <div class="col-12 col-md-3">
                                                    <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <div class="custom-file">
                                                        <input type="file" id="thumbnail" name="thumbnail" class="custom-file-input custom-img-input @error('thumbnail') is-invalid @enderror" required accept=".jpg,.jpeg,.png">
                                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                                        <ul>{!! trans_choice('labels.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG', 'dimension' => '1024x1024']) !!}</ul>
                                                        @error('thumbnail')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                                            <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png">
                                                <div class="dz-default dz-message">
                                                    <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                                    <h4>{{ __('labels.drag_and_drop') }}</h4>
                                                </div>
                                            </div>
                                            @error('files')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul>{!! trans_choice('labels.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files, 'dimension' => '1024x1024']) !!}</ul>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-attributes" role="tabpanel" aria-labelledby="vert-tabs-attributes-tab">
                                        <div class="row">
                                            <div class="col-12 table-responsive">
                                                <table class="table table-bordered" id="attributeDynamicForm">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width: 75%;">{{ trans_choice('labels.attribute', 2) }}</th>
                                                            <th scope="col" style="width: 15%">{{ __('labels.status') }}</th>
                                                            <th scope="col" style="width: 10%">{{ __('labels.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="sku0">{{ __('labels.sku') }}</label>
                                                                            <input type="text" name="attributes[0][sku]" id="sku0" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="stock_type0">{{ __('labels.stock_type') }}</label>
                                                                            <select name="attributes[0][stock_type]" id="stock_type0" class="form-control">
                                                                                <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.stock_type'))]) }} ---</option>
                                                                                @foreach ($stock_types as $type => $text)
                                                                                <option value="{{ $type }}">{{ $text }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="quantity0">{{ __('labels.quantity') }}</label>
                                                                            <input type="number" name="attributes[0][quantity]" id="quantity0" class="form-control" value="0" min="0" step="1">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="validity0">{{ __('labels.validity') . '(' . trans_choice('labels.day', 2) . ')' }}</label>
                                                                            <input type="number" name="attributes[0][validity]" id="validity0" class="form-control" step="1">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="color0">{{ __('labels.color') }}</label>
                                                                            <input type="color" name="attributes[0][color]" id="color0" class="form-control form-control-color">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="icheck-primary">
                                                                        <input type="checkbox" name="attributes[0][is_available]" id="is_available0">
                                                                        <label for="is_available0">{{ __('labels.available') }}</label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-remove-row">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        <tr id="attributeCloneTemplate" hidden="true" aria-hidden="true">
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="sku__REPLACE__">{{ __('labels.sku') }}</label>
                                                                            <input type="text" name="attributes[__REPLACE__][sku]" id="sku__REPLACE__" class="form-control" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="stock_type__REPLACE__">{{ __('labels.stock_type') }}</label>
                                                                            <select name="attributes[__REPLACE__][stock_type]" id="stock_type__REPLACE__" class="form-control" disabled>
                                                                                <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.stock_type'))]) }} ---</option>
                                                                                @foreach ($stock_types as $type => $text)
                                                                                <option value="{{ $type }}">{{ $text }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="quantity__REPLACE__">{{ __('labels.quantity') }}</label>
                                                                            <input type="number" name="attributes[__REPLACE__][quantity]" id="quantity__REPLACE__" class="form-control" value="0" min="0" step="1" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="validity__REPLACE__">{{ __('labels.validity') . '(' . trans_choice('labels.day', 2) . ')' }}</label>
                                                                            <input type="number" name="attributes[__REPLACE__][validity]" id="validity__REPLACE__" class="form-control" min="0" step="1" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 col-12">
                                                                        <div class="form-group">
                                                                            <label for="color__REPLACE__">{{ __('labels.color') }}</label>
                                                                            <input type="color" name="attributes[__REPLACE__][color]" id="color__REPLACE__" class="form-control form-control-color" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group">
                                                                    <div class="icheck-primary">
                                                                        <input type="checkbox" name="attributes[__REPLACE__][is_available]" id="is_available__REPLACE__" disabled>
                                                                        <label for="is_available__REPLACE__">{{ __('labels.available') }}</label>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-remove-row">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3">
                                                                <button type="button" class="btn btn-primary btn-add-row">
                                                                    <i class="fas fa-plus"></i>
                                                                    {{ __('labels.add_more') }}
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('ecommerce.products.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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

</div>

@endsection