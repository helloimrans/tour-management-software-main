@extends('layouts.admin.master')
@section('title', 'Create Tour')

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
                                <h4>Create Tour</h4>
                                <a href="{{ route('tour.index') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-circle-left"></i> Back
                                </a>
                            </div>

                            <form id="tourForm" action="{{ route('tour.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Tour Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter Tour Name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Tour Image</label>
                                            <input type="file" name="image" class="form-control" id="image"
                                                   accept="image/*">
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="image_preview" src="" alt="Image Preview"
                                                     style="max-width: 200px; max-height: 200px; display: none; border-radius: 5px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="4"
                                                      placeholder="Enter Description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Submit
                                        </button>
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
            const form = $('#tourForm');

            initImagePreview('#image', '#image_preview');

            const rules = {
                name: {
                    required: true,
                },
                image: {
                    accept: "image/*"
                }
            };

            form.validate({
                rules: rules,
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function(form) {
                    const submitButton = $(form).find('button[type="submit"]');
                    submitButton.prop('disabled', true)
                        .html('<i class="fas fa-sync-alt fa-spin"></i> Submitting...');
                    form.submit();
                }
            });
        });
    </script>
@endpush

