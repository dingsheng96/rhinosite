<form action="{{ route('app.project.index') }}" method="get" role="form" enctype="multipart/form-data">

    <input type="text" name="q" class="searchbar" placeholder="{{ __('app.searchbar') }}" value="{{ str_replace('+', ' ', request()->get('q')) }}">
    <button type="submit" class="home-s1-searchicon"><i class="fa fa-search"></i></button>

</form>