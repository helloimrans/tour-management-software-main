@extends('layouts.admin.master')
@section('title', 'Edit Tour')

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
                                <h4>Edit Tour</h4>
                                <a href="{{ route('tour.index') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>

                            <form id="tourForm" action="{{ route('tour.update', $data->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Tour Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter Tour Name" value="{{ old('name', $data->name) }}">
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
                                                @php
                                                    $imageUrl = $data->image
                                                        ? Storage::url($data->image)
                                                        : asset('defaults/noimage/no_img.jpg');
                                                @endphp
                                                <img id="image_preview" src="{{ $imageUrl }}" alt="Image Preview"
                                                     style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" rows="4"
                                                      placeholder="Enter Description">{{ old('description', $data->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Update
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

    <x-generic-validation-error-toastr />
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
                        .html('<i class="fa-solid fa-spinner fa-spin"></i> Updating...');
                    form.submit();
                }
            });
        });
    </script>
@endpush

