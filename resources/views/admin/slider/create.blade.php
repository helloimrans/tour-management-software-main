@extends('layouts.admin.master')
@section('title', 'Create Slider')
@push('css')
@endpush

@php
    $daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
 @endphp

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card dashboard-custom-card">
                        <div class="card-body">
                            <div class="custom-card-header d-flex justify-content-between">
                                <h4>Add New Slider</h4>
                                <a href="{{ route('slider.index') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-circle-left"></i> Back
                                </a>
                            </div>
                            <form id="musicForm" action="{{ route('slider.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="radio_station_id" class="form-label">Radio Station</label>
                                            <select  name="radio_station_id" class="form-control" id="radio_station_id">
                                                <option value="">Select Radio Station</option>
                                                @foreach($radioStation as $st)
                                                    <option value="{{$st['id']}}">{{$st['name']}}</option>
                                                @endforeach

                                            </select>
                                            @error('radio_station_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Enter title" value="{{ old('title') }}">
                                            @error('title')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <input type="text" name="description" class="form-control" id="description"
                                                placeholder="Enter description" value="{{ old('description') }}">
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="weekday" class="form-label">Week Days</label>
                                            <select  name="weekday[]" class="form-control multiselect select2" id="weekday" multiple>
                                                @foreach($daysOfWeek as $day)
                                                    <option value="{{$day}}">{{$day}}</option>
                                                @endforeach

                                            </select>
                                            @error('weekday')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="local_time" class="form-label">Local Time</label>
                                            <input type="time" name="local_time" class="form-control" id="music_views"
                                                placeholder="Enter local time" value="{{ old('local_time') }}">
                                            @error('local_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="usa_time" class="form-label">USA Time</label>
                                            <input type="time" name="usa_time" class="form-control" id="usa_time" value="{{ old('usa_time') }}">
                                            @error('usa_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Other fields remain unchanged -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                                            <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control" accept="image/*">
                                            @error('thumbnail_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="thumbnail_preview" src="" alt="Thumbnail Preview" style="max-width: 200px; max-height: 200px; display: none;">
                                            </div>
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
    <x-generic-validation-error-toastr />
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            let htmlForm = $('#musicForm');
            let validationTimer;

            let thumbnailInput = $('#thumbnail_image');
            let thumbnailPreview = $('#thumbnail_preview');


            // Preview thumbnail image
            thumbnailInput.change(function(event) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    thumbnailPreview.attr('src', e.target.result);
                    thumbnailPreview.show();
                }
                reader.readAsDataURL(event.target.files[0]);
            });

            // Custom validation rule for file size
            $.validator.addMethod("filesize", function(value, element, param) {
                if (element.files.length > 0) {
                    return element.files[0].size <= param;
                }
                return true;
            }, function(param, element) {
                let maxSizeInMB = (param / 1024 / 1024).toFixed(2);
                return "File size must be less than " + maxSizeInMB + " MB";
            });

            let rules = {
                radio_station_id: {
                    required: true,
                },
                title: {
                    required: true,
                },

                thumbnail_image: {
                    required: false,
                    accept: "image/*",
                }
            };

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

            // Add asterisk for required fields
            $.each(rules, function(key, item) {
                if (typeof item.required == "function" ? item.required() : item.required) {
                    $('label[for="' + key + '"]').first().append(
                        '<span class="text-danger">&nbsp;*</span>'
                    );
                }
            });
        });
    </script>
@endpush

