@extends('layouts.admin.master')
@section('title', 'Create Service Provider')

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
                                <h4>Create Service Provider</h4>
                                <a href="{{ route('serviceProvider.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="serviceProviderForm" action="{{route('serviceProvider.update', $data->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="radio_station_id" class="form-label">Radio Station</label>
                                            <select  name="radio_station_id" class="form-control" id="radioStation">
                                                <option value="" >Select radio station</option>
                                                @foreach($radioStations as $st)
                                                    <option value="{{$st['id']}}" selected="{{$data['radio_station_id'] == $st['id'] ?? 'false'}}">{{$st['name']}}</option>
                                                @endforeach

                                            </select>
                                            @error('radio_station_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="service_id" class="form-label">Service</label>
                                            <select  name="service_id" class="form-control" id="radioStation">
                                                <option value="" >Select Service</option>
                                                @foreach($services as $st)
                                                    <option value="{{$st['id']}}" selected="{{$data['service_id'] == $st['id'] ?? false}}">{{$st['name']}}</option>
                                                @endforeach

                                            </select>
                                            @error('service_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                   placeholder="Enter name" value="{{ $data['name'] }}">
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="url" class="form-label">Url</label>
                                            <input type="text" name="url" class="form-control"
                                                   id="description" placeholder="Enter description"
                                                   value="{{ $data['url']}}">
                                            @error('url')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="form-label">Logo</label>
                                            <input type="file" name="logo" class="form-control"
                                                   id="logo">
                                            @error('logo')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mt-2">
                                            <img id="logo-preview" src="{{Storage::url($data['logo'])}}" alt="Thumbnail Preview" class="rounded-circle" style="max-width: 200px; max-height: 200px; display: none;">
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
            let htmlForm = $('#serviceProviderForm');
            let validationTimer;

            let logo = $('#logo');
            let logoPreview = $('#logo-preview')
            logoPreview.show()

            logo.change(function(event) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.attr('src', e.target.result);
                    logoPreview.show();
                }
                if(event.target.files[0]){
                    reader.readAsDataURL(event.target.files[0]);
                }else{
                    logoPreview.hide();
                }

            });

            $.validator.addMethod("imageSizeType", function (value, element) {
                // Check if any file is selected
                if (element.files && element.files.length) {
                    const file = element.files[0];

                    // Check file type
                    const validTypes = ["image/jpeg", "image/png", "image/gif"];
                    const isValidType = validTypes.includes(file.type);

                    // Check file size (in bytes)
                    const maxSize = 5 * 1024 * 1024; // Convert MB to bytes
                    const isValidSize = file.size <= maxSize;

                    return isValidType && isValidSize;
                }
                return true;
            }, "Please select a valid image file (JPEG, PNG, GIF) and ensure it is less than {0} MB.");

            let rules = {
                radio_station_id: {
                    required: false,
                },
                name: {
                    required: true,
                },
                url:{
                    url:true,
                },
                logo:{
                    imageSizeType:true
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
            })
        });
    </script>
@endpush
