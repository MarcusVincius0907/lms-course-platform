@extends('layouts.master')
@section('title','Coupons')
@section('parentPageTitle', 'Todos os Cupons')
@section('content')

    <div class="card m-2">
        <div class="card-header">
            <div class="float-left">
                <h3>Todos os Cupons</h3>
            </div>
            <div class="float-right">
                <div class="row">
                    <div class="col">
                        <form method="get" action="">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control col-12"
                                       placeholder="Nome do cupom" value="{{Request::get('search')}}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">@translate(Search)</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <a href="#!"
                           onclick="forModal('{{ route("coupon.create") }}', 'Criar Cupom')"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>
                            Novo Cupom
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-striped- table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>Index</th>
                    <th>Código</th>
                    <th>Desconto %</th>
                    <th>Quantidade</th>
                    <th>Disponibilidade</th>
                    <th>Cursos</th>
                    <th>Ativo</th>
                    <th>@translate(Action)</th>
                </tr>
                </thead>
                <tbody>
                    
                @forelse($coupons as  $item)
                    <tr>
                        <td>{{ ($loop->index+1) + ($coupons->currentPage() - 1)*$coupons->perPage() }}</td>
                        <td>
                            {{$item->code}}
                        </td>
                        <td>{{$item->percent}}</td>
                        <td>
                            {{$item->quantity}}
                        </td>
                        <td>
                            {{date('d/m/Y', strtotime($item->start_date))}}{{' - '}}{{ date('d/m/Y', strtotime($item->end_date)) }}
                        </td>
                        <td>
                            
                            {{isset($item->course) ? $item->course->title : 'Todos'}}
                                   
                        </td>

                        <td>
                            <div class="switchery-list">
                                <input type="checkbox" data-url="{{route('coupon.published')}}"
                                       data-id="{{$item->id}}"
                                       class="js-switch-success"
                                       id="category-switch" {{$item->is_published == true ? 'checked' : null}} />
                            </div>
                        </td>
                        <td>
                            <div class="kanban-menu">
                                <div class="dropdown">
                                    <button class="btn btn-link p-0 m-0 border-0 l-h-20 font-16" type="button"
                                            id="KanbanBoardButton1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="feather icon-more-vertical-"></i></button>
                                    <div class="dropdown-menu dropdown-menu-right action-btn"
                                         aria-labelledby="KanbanBoardButton1" x-placement="bottom-end">
                                        <a class="dropdown-item" href="#!"
                                           onclick="forModal(`{{ route('coupon.edit', $item->id) }}`, `Editar Cupom` )">
                                            <i class="feather icon-edit-2 mr-2"></i>@translate(Edit)</a>
                                        <a class="dropdown-item"
                                           onclick="confirm_modal('{{ route('coupon.destroy', $item->id) }}')"
                                           href="#!">
                                            <i class="feather icon-trash mr-2"></i>@translate(Delete)</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr>
                        <td><h3 class="text-center">@translate(No Data Found)</h3></td>
                    </tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                @endforelse
                </tbody>
                <div class="float-left">
                    {{ $coupons->links() }}
                </div>
            </table>
        </div>
    </div>

@endsection
