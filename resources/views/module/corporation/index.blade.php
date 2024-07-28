@extends('layouts.master')
@section('title','Corporação')
@section('parentPageTitle', 'Corporação')
@section('content')
@if (session('status-success'))
    <div class="alert alert-success">
        {{ session('status-success') }}
    </div>
@endif
@if (session('status-error'))
    <div class="alert alert-danger">
        {{ session('status-error') }}
    </div>
@endif
    <div class="card m-2">
        <div class="card-header">
            <div class="float-left">
                <h3>Corporação</h3>
            </div>
            <div class="float-right">
                <div class="row">
                    <div class="col">
                        <form method="get" action="">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control col-12"
                                       placeholder="Nome da corporação" value="{{Request::get('search')}}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">@translate(Search)</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <a href="#!"
                           onclick="forModal(`{{ route('corporation.create') }}`, 'Criar Corporação')"
                           class="btn btn-primary">
                            <i class="la la-plus"></i>
                            Nova Corporação
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
                    <th>Logo</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>URL</th>
                    <th>Cores</th>
                    <th>Alunos</th>
                    <th>Cursos</th>
                    <th>Ativo</th>
                    <th>@translate(Action)</th>
                </tr>
                </thead>
                <tbody>
                    
                @forelse($corporation as  $item)
                    <tr>
                        <td>{{ ($loop->index+1) + ($corporation->currentPage() - 1)*$corporation->perPage() }}</td>
                        <td>
                            <img src="{{filePath($item->logo)}}" class="card-img avatar-xl" alt="Card image">
                        </td>
                        <td>{{$item->name}}</td>
                        <td>
                            {{$item->email}}
                        </td>
                        <td>
                            {{$item->path}}
                        </td>
                        <td>
                            
                            {{$item->colors}}
                                   
                        </td>

                        <td>

                            @php
                                $count = App\Model\Student::where('corporation_id', $item->id)->count()
                            @endphp
                            @if ($count > 0)
                                <a class="btn-corp-studants-count" href="{{route('corporation.students', $item->id)}}" > {{$count}} </a>
                            @else
                                <div>Sem estudantes...</div>
                            @endif
                            
                            
                                   
                        </td>

                        <td>

                            @php
                                $courses_array = json_decode($item->courses);
                                $count_course = 0;
                                if(is_array($courses_array))
                                    $count_course = count($courses_array);
                            @endphp
                            @if ($count_course > 0)
                                <a class="btn-corp-studants-count" href="{{route('corporation.courses', $item->id)}}" > {{$count_course}} </a>
                            @else
                                <div>Nenhum curso...</div>
                            @endif
                            
                            
                                   
                        </td>

                        <td>
                            <div class="switchery-list">
                                <input type="checkbox" data-url="{{route('corporation.published')}}"
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
                                           onclick="forModal(`{{ route('corporation.edit', $item->id) }}`, `Editar Cupom` )">
                                            <i class="feather icon-edit-2 mr-2"></i>@translate(Edit)</a>
                                        <a class="dropdown-item"
                                           onclick="confirm_modal('{{ route('corporation.destroy', $item->id) }}')"
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
                {{-- <div class="float-left">
                    {{ $coupons->links() }}
                </div> --}}
            </table>
        </div>
    </div>

@endsection
