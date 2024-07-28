@extends('layouts.master')
@section('title','Instructor Earning')
@section('parentPageTitle', 'All Earning History')
@section('content')
<div class="card m-2">
    <div class="card-header">
        <div class="float-left">
            <h2 class="card-title">
                Lista de Recebimento</h2>
        </div>
        <div class="float-right">
        </div>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-striped- table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>S/L</th>
                    <th>
                        @translate(Package)</th>
                    <th>
                        Curso</th>
                    <th>
                        Preço do Curso</th>
                    <th>
                        Valor a receber</th>
                    <th>
                        Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($earning as $item)
                <tr>
                    <td>{{ ($loop->index+1) + ($earning->currentPage() - 1)*$earning->perPage() }}</td>
                    <td><img src="{{filePath($item->package->image)}}" class="avatar-lg"></td>
                    <td>
                        @if($item->enrollment)
                            <a target="_blank" href="{{ route('course.show',[$item->enrollment->enrollCourse->id,$item->enrollment->enrollCourse->slug])}}">
                                {{$item->enrollment->enrollCourse->title ?? 'N/A'}}
                            </a>
                        @endif

                    </td>
                    <td>
                        {{formatPrice($item->course_price)}}
                    </td>
                    <td>
                        {{formatPrice($item->will_get)}}
                    </td>
                    <td>
                        {{date('d-M-y',strtotime($item->created_at)) ?? 'N/A'}}
                    </td>
                </tr>
                @empty
                    <tr></tr>
                    <tr></tr>
                <tr>
                    <td><h3 class="text-center">@translate(No Data Found)</h3></td>
                </tr>
                    <tr></tr>
                    <tr></tr>
                    <tr></tr>
                @endforelse
            </tbody>
            <div class="float-left">
                {{ $earning->links() }}
            </div>
        </table>
    </div>
</div>

@endsection
