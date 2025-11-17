@extends('layouts.admin.master')
@section('title', 'Edit music')

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
                                <h4>Edit Music </h4>
                                <a href="{{ route('music.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="musicForm" action="{{ route('music.update', $data->id) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="radio_station_id" class="form-label">Radio station</label>
                                            <select  name="radio_station_id" class="form-control" id="radioStation">
                                                <option >Select radio station</option>
                                                @foreach($radioStation as $st)
                                                    <option value="{{$st['id']}}" selected={{$st['id']== $data->radio_station_id}}>{{$st['name']}}</option>
                                                @endforeach

                                            </select>
                                            @error('radio_station_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="category_ids">Music Categories</label>
                                            <select class="form-control multiselect select2" id="category_ids" name="category_ids[]" multiple required>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category['id'] }}"
                                                            @if ($data->musicCategories && $data->musicCategories->contains('id', $category['id'])) selected @endif>
                                                        {{ $category['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('category_ids')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Enter title" value="{{ old('title', $data->title) }}">
                                            @error('title')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea name="description" class="form-control" id="description" placeholder="Enter description">{{ old('description', $data->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="music_author" class="form-label">Music Author</label>
                                            <input type="text" name="music_author" class="form-control" id="music_author"
                                                placeholder="Enter music_author" value="{{ old('music_author', $data->music_author) }}">
                                            @error('music_author')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="music_views" class="form-label">Music Views</label>
                                            <input type="text" name="music_views" class="form-control" id="music_views"
                                                placeholder="Enter music_views" value="{{ old('music_views', $data->music_views) }}">
                                            @error('music_views')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="thumbnail_image" class="form-label">Thumbnail Image</label>
                                            <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control" accept="image/*">
                                            @error('thumbnail_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="thumbnail_preview" src="{{$data->thumbnail_image ? Storage::url($data->thumbnail_image) : asset('defaults/noimage/no_img.jpg')}}" alt="Thumbnail Preview" style="max-width: 200px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="music_file" class="form-label">Music File</label>
                                            <input type="file" name="music_file" class="form-control" id="music_file"
                                                placeholder="Enter music_file" value="{{ old('music_file', $data->music_file) }}">
                                            @error('music_file')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <audio controls>
                                                    <source src="{{$data->music_file ? Storage::url($data->music_file ) : ''}}" type="audio/mpeg">
                                                </audio>
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
                radio_station_id: {
                    required: true,
                },
                category_ids: {
                    required: true,
                },
                title: {
                    required: true,
                },
                music_author: {
                    required: true,
                },
                music_views: {
                    required: true,
                    number: true,
                },
                thumbnail_image: {
                    required: function () {
                        return ("{{ $data && $data->thumbnail_image }}" === "");
                    },
                    accept: "image/*",
                },
                music_file: {
                    required: function () {
                        return ("{{ $data && $data->music_file }}" === "");
                    },
                    accept: "audio/*"
                },

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
            })


        });
    </script>
@endpush
