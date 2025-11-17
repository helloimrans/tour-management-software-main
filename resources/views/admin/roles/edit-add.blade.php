@php
    $edit = !empty($role->id);
@endphp
@extends('layouts.admin.master')

@section('title')
    {{ $edit?'Edit Role':'Create Role' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card  dashboard-custom-card">
                    <div class="card-body">
                        <div class="custom-card-header d-flex justify-content-between">
                            <h3 class="card-title font-weight-bold">{{ $edit?'Edit Role':'Create Role' }}</h3>

                            <div class="card-tools">
                                <a href="{{route('roles.index')}}" class="create-button">
                                    <i class="fas fa-backward"></i> Back
                                </a>
                            </div>
                        </div>
                        <form
                            action="{{$edit ? route('roles.update', $role->id) : route('roles.store')}}"
                            method="POST" class="edit-add-form">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{$edit ? $role->name : old('name')}}"
                                           placeholder="Name" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="display_name">Display Name <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control" name="display_name" id="display_name"
                                           value="{{$edit ? $role->display_name : old('display_name')}}"
                                           placeholder="Display Name" required>
                                </div>



                                <div class="form-group col-md-6">
                                    <label for="description">Description</label>
                                    <textarea type="text" class="form-control" name="description" id="description"
                                              placeholder="Description">{{$edit ? $role->description : old('description')}}</textarea>
                                </div>


                            </div>

                                   <button class="btn btn-primary"><i class="fa fa-save"></i> {{ $edit?'Update Role':'Create Role' }}</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <style>
        .custom-radio-is-deletable{
            margin-right: 25px;
        }
    </style>
@endpush
@push('js')
    <x-generic-validation-error-toastr/>
    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                display_name: {
                    required: true,
                },
                name: {
                    required: true
                },
                // is_deletable: {
                //     required: true
                // }
            },
            messages:{
                name: {
                    pattern: "This field is required in English.",
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush


