@extends('layouts.admin.master')
@section('title', 'Create Music Category')

@push('css')
@endpush

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Create Music Category</h4>
                                <a href="{{ route('music.category.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="musicCategoryForm" action="{{ route('music.category.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="Enter name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <input type="text" name="description" class="form-control"
                                                id="description" placeholder="Enter description"
                                                value="{{ old('description') }}">
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                  
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                                    </div>
                                </div>

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('js')
    <script>
        $(document).ready(function() {
            let htmlForm = $('#musicCategoryForm');
            let validationTimer;

            htmlForm.validate({
                onfocusout: function(element) {
                    this.element(element);
                },
                onkeyup: function(element) {
                    let validator = this;
                    clearTimeout(validationTimer);
                    validationTimer = setTimeout(function() {
                        validator.element(element);
                    }, 1000);
                },
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: rules,
                submitHandler: function(htmlForm) {
                    let button = $(htmlForm).find('button[type="submit"]:focus');
                    button.attr("disabled", true).css("cursor", "default");
                    button.html(
                        '<span class="submitting"><i class="fas fa-sync-alt"></i> Loading...</span>'
                    );
                    htmlForm.submit();
                }
            });

            $.each(rules, function(key, item) {
                if (typeof item.required == "function" ? item.required() : item.required) {
                    $('label[for="' + key + '"]').first().append(
                        '<span style="vertical-align: text-top; font-family: Verdana,sans-serif;" class="text-danger">&nbsp;*</span>'
                    );
                }
            })



        });
    </script>
@endpush
