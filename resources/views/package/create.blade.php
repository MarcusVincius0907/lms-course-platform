
@include('layouts.include.form.form_css')

<div class="card-body">
    <form action="{{route('packages.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Título <span class="text-danger">*</span></label>
            <input class="form-control" name="title" placeholder="Título" type="text" required>
            @error('title') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
        </div>
        <div class="form-group row">
            <label class="col-lg-3 col-form-label" for="val-requirement">
                Itens <span class="text-danger">*</span></label>
            <div class="col-lg-9">
                <div class="bootstrap-tagsinput">
                    <input type="text"  class="@error('requirement') is-invalid @enderror" placeholder="Itens" id="val-requirement" name="items" data-role="tagsinput">
                      @error('items') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror
                </div>
            </div>
        </div>
        {{-- <div class="form-group">
            <label>@translate(Price) <span class="text-danger">*</span></label>
            <input class="form-control" placeholder="@translate(Enter Price)" type="number" min="0" name="price" required readonly>
            <small class="font-weight-bold">In USD</small>
        </div> --}}
        <div class="form-group">
            <label>@translate(Commission) %<span class="text-danger">*</span></label>
            <input placeholder="@translate(Commission) %" step="0.01" class="form-control" min="0" type="number" name="commission"
                   required>
            @error('commission') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror

        </div>
        <div class="form-group">
            <label class="col-form-label text-md-right">@translate(Image) <span class="text-danger">*</span></label>
            <div class="custom-file">

                <input class="custom-file-input package" id="customFile" name="image" type="hidden" required>
                @error('image') <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong> </span> @enderror

                 <img class="package_preview rounded shadow-sm d-none" width="60" src="" alt="#Package thumbnail">  

                      <input type="hidden" name="package_url" class="package_url" value="">
                    <br>
                      
                      @if (MediaActive())
                       {{-- media --}}
                      <a href="javascript:void()" onclick="openNav('{{ route('media.slide') }}', 'package')" class="btn btn-primary media-btn mt-2 p-2">Upload From Media <i class="fa fa-cloud-upload ml-2" aria-hidden="true"></i> </a>
                      @endif


            </div>
        </div>
        <div class="float-right">
            <button class="btn btn-primary float-right" type="submit">@translate(Save)</button>
        </div>

    </form>
</div>

@include('layouts.include.form.form_js')