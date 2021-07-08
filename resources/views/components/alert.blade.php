@if ($errors->any())

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5><i class="icon fas fa-exclamation-circle"></i> {{ __('messages.errors_found') }}</h5>
                <hr>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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