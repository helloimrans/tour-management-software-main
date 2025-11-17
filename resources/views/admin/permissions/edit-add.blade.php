@php
    $edit = !empty($permission->id);
@endphp
@extends('layouts.admin.master')

@section('title')
    {{ $edit ? 'Edit Permission' : 'Create Permission' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card dashboard-custom-card">
                    <div class="card-body">
                        <div class="custom-card-header d-flex justify-content-between">
                            <h4 class="">{{ $edit ? 'Edit Permission' : 'Create Permission' }}</h4>

                            <a href="{{ route('permissions.index') }}" class="create-button">
                                <i class="fas fa-backward"></i> Back
                            </a>
                        </div>
                        <form
                            action="{{ $edit ? route('permissions.update', $permission->id) : route('permissions.store') }}"
                            method="POST" class="edit-add-form">
                            @csrf
                            @if ($edit)
                                @method('put')
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="key">Name <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $edit ? $permission->name : old('name') }}"
                                        placeholder="Enter unique permission name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="display_name">Display Name <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control" name="display_name" id="display_name"
                                        value="{{ $edit ? $permission->display_name : old('display_name') }}"
                                        placeholder="Display Name" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="group_name">Group name <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control" name="group_name" id="group_name"
                                        value="{{ $edit ? $permission->group_name : old('group_name') }}"
                                        placeholder="Group name" required>
                                </div>
                            </div>
                                <button class="btn btn-primary"><i class="fa fa-save"></i> {{ $edit ? 'Update Permission' : 'Create Permission' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <x-generic-validation-error-toastr />
    <script>
        const EDIT = !!'{{ $edit }}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                name: {
                    required: true
                },
                display_name: {
                    required: true
                }
            },
            submitHandler: function(htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
