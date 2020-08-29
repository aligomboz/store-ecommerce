@if(session()->has('erorr'))
<div class="row mr-2 ml-2">
    <button type="text" class="btn btn-lg btn-block btn-outline-danger mb-2" id="type-error"> 
        {{Session::get('erorr')}}
    </button>
</div>
@endif