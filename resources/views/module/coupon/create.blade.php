<div class="card-body">
    <form action="{{route('coupon.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Código <span class="text-danger">*</span></label>
            <input class="form-control" name="code" placeholder="Ex: academy50" required>
        </div>

        <div class="form-group">
            <label>Desconto % <span class="text-danger">*</span></label>
            <input class="form-control" name="percent" placeholder="Ex: 50 %" type="number" min="0" max="100" required>
        </div>

        <div class="form-group">
            <label>Quantidade <span class="text-danger">*</span></label>
            <input class="form-control" name="quantity" placeholder="Ex: 5" type="number" min="0"  required>
        </div>

        <div class="form-group d-flex">
            <div >
                <label>Data de início <span class="text-danger">*</span></label>
                <input class="form-control " type="date" name="start_date" placeholder="Data de inicio" required>
            </div>
            <div class="ml-4">
                <label>Data de fim <span class="text-danger">*</span></label>
                <input class="form-control " type="date" name="end_date" placeholder="Data de fim" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Curso</label>
            <select class="form-control select2 w-100" name="courses">
                <option value="all">Todos os cursos</option>
                @foreach($courses as $item)
                    <option value="{{$item->id}}">{{$item->title}}</option>
                @endforeach
            </select>
        </div>
        
        <div class="float-right">
            <button class="btn btn-primary float-right" type="submit">@translate(Save)</button>
        </div>

    </form>
</div>




