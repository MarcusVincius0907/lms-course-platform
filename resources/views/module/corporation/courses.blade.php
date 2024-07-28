@extends('layouts.master')
@section('title','Corporação')
@section('parentPageTitle', 'Corporação')
@section('content')
    <div class="m-2">
        <a href="{{URL::previous()}}">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </a>
    </div>
    <div class="card m-2">
        <div class="card-header">
            <div class="float-left">
                <h3>Cursos Selecionados</h3>
            </div>
            
        </div>

        <div class="card-body">
            <table class="table table-striped- table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>Index</th>
                    <th>Imagem</th>
                    <th>Título</th>
                </tr>
                </thead>
                <tbody>
                    
                @forelse($courses as  $item)
                    <tr>
                        <td>{{ ($loop->index+1) }}</td>
                        <td>
                            <img src="{{filePath($item->image)}}" class="card-img avatar-xl" alt="Card image">
                        </td>
                        <td>{{$item->title}}</td>
                        
                        
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
