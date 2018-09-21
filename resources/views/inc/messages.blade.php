@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i class="fa fa-check-square"></i> {{session('success')}}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i class="fa fa-exclamation-triangle"></i> {{session('warning')}}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <i class="fa fa-close"></i> {{session('error')}}
    </div>
@endif