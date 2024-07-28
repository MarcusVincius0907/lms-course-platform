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
                <h3>Estudantes da {{$corporation->name}}</h3>
            </div>
            
        </div>

        <div class="card-body">
            <table class="table table-striped- table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>Index</th>
                    <th>Nome</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                    
                @forelse($students as  $item)
                    <tr>
                        <td>{{ ($loop->index+1) + ($students->currentPage() - 1)*$students->perPage() }}</td>
                        
                        <td>{{$item->name}}</td>
                        <td>
                            {{$item->email}}
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
