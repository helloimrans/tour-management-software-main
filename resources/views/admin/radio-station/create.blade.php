@extends('layouts.admin.master')
@section('title', 'Create Radio Station')

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
                                <h4>Create Radio Station</h4>
                                <a href="{{ route('radio.stations.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="radioStaionForm" action="{{ route('radio.stations.store') }}" method="post"
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
                                            <label for="live_radio_url" class="form-label">Live Radio Url</label>
                                            <input type="text" name="live_radio_url" class="form-control"
                                                id="live_radio_url" placeholder="Enter live radio url"
                                                value="{{ old('live_radio_url') }}">
                                            @error('live_radio_url')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="live_youtube_url" class="form-label">Live YouTube Url</label>
                                            <input type="text" name="live_youtube_url" class="form-control"
                                                id="live_youtube_url" placeholder="Enter live youtube url"
                                                value="{{ old('live_youtube_url') }}">
                                            @error('live_youtube_url')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number" class="form-label">Phone Number</label>
                                            <input type="text" name="phone_number" class="form-control" id="phone_number"
                                                placeholder="Enter phone number" value="{{ old('phone_number') }}">
                                            @error('phone_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="whatsapp_number" class="form-label">WhatsApp Number</label>
                                            <input type="text" name="whatsapp_number" class="form-control"
                                                id="whatsapp_number" placeholder="Enter whatsapp number"
                                                value="{{ old('whatsapp_number') }}">
                                            @error('whatsapp_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-label">Logo</label>
                                            <input type="file" name="logo" id="logo" class="form-control">
                                            @error('logo')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="logo-preview" src="#" alt="Logo Preview"
                                                    style="display: none; width: 100px;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                                            <input type="file" name="thumbnail_image" id="thumbnail_image"
                                                class="form-control">
                                            @error('thumbnail_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="thumbnail-preview" src="#" alt="Thumbnail Preview"
                                                    style="display: none; width: 100px;" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="is_under_maintenence" name="is_under_maintenence">
                                                <label class="custom-control-label fs-15" for="is_under_maintenence">Is
                                                    Under Maintenance</label>
                                            </div>
                                            @error('is_under_maintenence')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group" id="maintenance-image-group" style="display: none;">
                                                    <label for="under_maintenence_image" class="form-label">Under Maintenance
                                                        Image</label>
                                                    <input type="file" name="under_maintenence_image"
                                                        id="under_maintenence_image" class="form-control">
                                                    @error('under_maintenence_image')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <div class="mt-2">
                                                        <img id="under-maintenance-preview" src="#"
                                                            alt="Under Maintenance Preview"
                                                            style="display: none; width: 100px;" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-switch mt-4">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="is_relaks_tv" name="is_relaks_tv" value="1" @if (old('is_relaks_tv') == '1') checked @endif>
                                                <label class="custom-control-label fs-15" for="is_relaks_tv">Is Relaks Tv</label>
                                            </div>

                                            <div class="relaks_tv_show mt-3"
                                                @if (old('is_relaks_tv') == 1) style="display:block;" @else style="display:none;" @endif>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="youtube_url_1">YouTube Url (1)</label>
                                                            <input type="test" name="youtube_url_1" id="youtube_url_1" placeholder="Enter url"
                                                                class="form-control"
                                                                value="{{ old('youtube_url_1') }}">

                                                            @error('youtube_url_1')
                                                                <div class="text-danger">{{ $message }}
                                                                </div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="youtube_url_2">YouTube Url (2)</label>
                                                            <input type="test" name="youtube_url_2" id="youtube_url_2" placeholder="Enter url"
                                                                class="form-control"
                                                                value="{{ old('youtube_url_2') }}">

                                                            @error('youtube_url_2')
                                                                <div class="text-danger">{{ $message }}
                                                                </div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label" for="youtube_url_3">YouTube Url (3)</label>
                                                            <input type="test" name="youtube_url_3" id="youtube_url_3" placeholder="Enter url"
                                                                class="form-control"
                                                                value="{{ old('youtube_url_3') }}">

                                                            @error('youtube_url_3')
                                                                <div class="text-danger">{{ $message }}
                                                                </div>
                                                            @enderror

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-primary mt-4"><i class="fa fa-save"></i> Submit</button>
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
            let htmlForm = $('#radioStaionForm');
            let validationTimer;

            $.validator.addMethod("filesize", function(value, element, param) {
                if (element.files.length > 0) {
                    return element.files[0].size <= param;
                }
                return true;
            }, function(param, element) {
                let maxSizeInMB = (param / 1024 / 1024);
                return "File size must be less than " + maxSizeInMB + " MB";
            });

            let rules = {
                youtube_url_1: {
                    required: true,
                },
                youtube_url_2: {
                    required: true,
                },
                youtube_url_3: {
                    required: true,
                },
                name: {
                    required: true,
                },
                live_radio_url: {
                    required: true,
                },
                live_youtube_url: {
                    required: true,
                },
                logo: {
                    required: true,
                    accept: "image/*",
                    filesize: 5242880
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

            $.each(rules, function(key, item) {
                if (typeof item.required == "function" ? item.required() : item.required) {
                    $('label[for="' + key + '"]').first().append(
                        '<span style="vertical-align: text-top; font-family: Verdana,sans-serif;" class="text-danger">&nbsp;*</span>'
                    );
                }
            });


            $('#logo').change(function() {
                readURL(this, 'logo-preview');
            });

            $('#thumbnail_image').change(function() {
                readURL(this, 'thumbnail-preview');
            });

            $('#under_maintenence_image').change(function() {
                readURL(this, 'under-maintenance-preview');
            });

            $("#is_relaks_tv").click(function() {
                if ($(this).is(":checked")) {
                    $(".relaks_tv_show").slideDown();
                } else {
                    $(".relaks_tv_show").slideUp();
                }
            });

            $('#is_under_maintenence').change(function() {
                if ($(this).is(':checked')) {
                    $('#maintenance-image-group').slideDown();
                } else {
                    $('#maintenance-image-group').slideUp();
                }
            });

        });

        function readURL(input, previewElementId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + previewElementId).attr('src', e.target.result).show();
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
