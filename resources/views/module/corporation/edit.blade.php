@extends('layouts.master')

<div class="card-body">
    <form action="{{route('corporation.update')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$corporation->id}}">
        
        <div class="form-group">
            <label>Logo <span class="text-danger">*</span></label>

            
            <label for="imgInp" class="btn btn-primary media-btn mt-2 p-2">Selecione o logo</label>
            <input id="imgInp" name="logo" style="visibility:hidden;" type="file" >
            <input type="hidden" name="logoChanged" id="logoChanged" value="false">
            <img id="preview-image-corp" class="preview-corp-img" src="{{filePath($corporation->logo)}}" alt="your image" />

        </div>

        <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input value="{{$corporation->name}}" class="form-control" name="name" type="text" required>
        </div>

        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input value="{{$corporation->email}}" class="form-control" name="email" type="email" required readonly>
        </div>

        <div class="form-group">
            <label>URL <span class="text-danger">*</span></label>
            <input value="{{$corporation->path}}" class="form-control" name="path" type="text" required>
        </div>

        <div class="form-group">
            <div class="form-group">
                <label>Cores <span class="text-danger">*</span></label>
                <input value="{{$corporation->colors}}" class="form-control" name="colors" type="text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group">
                <label>Estudantes </label>
                <div>
                    <a href="{{filePath('uploads/studentsUpload/example.xlsx')}}" download>Baixar exemplo</a>
                    <label for="studentsUpload" class="btn btn-primary media-btn mt-2 p-2">Upload de estudantes</label>
                    <input id="studentsUpload"  style="visibility:hidden;" type="file">
                    <input type="hidden" name="students" id="studentsCorpInput" >
                    <div id="studentCorpList">
                        <div class="table-container">
                            <table class="table table-striped- table-bordered table-hover text-center">
                                <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($corporation->students as $item)
                                    <tr>
                                        <td>{{($loop->index+1)}}</td>
                                        
                                        <td>
                                            {{$item->name}}
                                        </td>
                                        <td>
                                            {{$item->email}}
                                        </td>
                                        <td>
                                            {{$item->cpf}}
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
        
                            </table>
                            
                        </div>
                    </div>
               
                    
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <div class="form-group">
                <label>Cursos </label>
                <input type="hidden" name="courses" id="courseCorp" value="{{$corporation->courses}}">
                <div class="table-container">
                    <table class="table table-striped- table-bordered table-hover text-center">
                        <thead>
                        <tr>
                            <th>Index</th>
                            <th>Capa</th>
                            <th>Título</th>
                            <th>Instrutor</th>
                            <th>Selecionado</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $item)
                            <tr>
                                <td>{{($loop->index+1)}}</td>
                                <td>
                                    <img src="{{filePath($item->image)}}" width="150" alt="">
                                </td>
                                <td>
                                    {{$item->title}}
                                </td>
                                <td>
                                    {{$item->relationBetweenInstructorUser->name}}
                                </td>
                                <td > 
                                    <div class="switchery-list">
                                        <input 
                                            type="checkbox" 
                                            data-url=""
                                            data-id=""
                                            class="js-switch-success"
                                            id="category-switch"
                                            onchange="selectCourse('{{$item->id}}')"
                                            @php
                                                $is_checked = '';
                                                if($corporation->courses)
                                                    if(in_array( $item->id , json_decode($corporation->courses)))
                                                        $is_checked = 'checked';
                                            @endphp
                                            
                                            {{ $is_checked }}
                                        />
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

                    </table>
                    
                </div>
            </div>
        </div>
        
        
        
        <div class="float-right">
            <button class="btn btn-primary float-right" type="submit">@translate(Save)</button>
        </div>

    </form>
</div>

<script src="{{ assetC('assets/js/custom/corporation-dashboard.js') }}"></script>