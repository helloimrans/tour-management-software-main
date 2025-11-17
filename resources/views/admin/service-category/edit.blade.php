@extends('layouts.admin.master')
@section('title', 'Create Service Category')
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
                                <h4>Create Service Category</h4>
                                <a href="{{ route('serviceCategory.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="serviceCategory" action="{{ route('serviceCategory.update', $data->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="radio_station_id" class="form-label">Radio Station</label>
                                            <select  name="radio_station_id" class="form-control" id="radioStation">
                                                <option value="" selected>Select radio station</option>
                                                @foreach($radioStations as $st)
                                                    <option value="{{$st['id']}}" @if($data['radio_station_id'] == $st['id']) selected @endif >{{$st['name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('radio_station_id')
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
            let htmlForm = $('#serviceCategory');
            let validationTimer;
            let rules = {
                radio_station_id: {
                    required: false,
                },
                name: {
                    required: true,
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

