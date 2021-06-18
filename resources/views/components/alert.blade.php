@if ($errors->any())

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ __('labels.errors_found') }}
                </h5>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <hr>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@endif

@if (Session::has('success') || Session::has('fail'))

@push('scripts')

<script type="text/javascript">
    let status = "{{ Session::has('success') ? 'success' : 'error' }}";
    let message = "{{ Session::has('success') ? Session::get('success') : Session::get('fail') }}"

    customAlert(message, status);
</script>

@endpush

@endif