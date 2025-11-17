@extends('layouts.admin.master')
@section('title', 'Edit Admin User')

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
                                <h4>Edit Admin User</h4>
                                <a href="{{ route('admin.user.index') }}" class="btn btn-primary"><i
                                        class="fa fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <form id="musicCategoryForm" action="{{ route('admin.user.update', $data->id) }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="radio_station_id" class="form-label">Radio Station</label>
                                            <select  name="radio_station_id" class="form-control" id="radio_station_id">
                                                <option value="">All Radio Station</option>
                                                @foreach($radioStations as $radioStation)
                                                    <option value="{{$radioStation->id}}" @if ($radioStation->id == $data->radio_station_id)
                                                        selected
                                                    @endif>{{$radioStation->name}}</option>
                                                @endforeach

                                            </select>
                                            @error('radio_station_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" name="first_name" class="form-control" id="first_name"
                                                   placeholder="Enter First Name" value="{{ $data->first_name }}">
                                            @error('first_name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control"
                                                   id="last_name" placeholder="Last Name"
                                                   value="{{ $data->last_name }}">
                                            @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" class="form-control"
                                                   id="email" placeholder="example@gmail.com"
                                                   value="{{ $data->email }}">
                                            @error('email')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control"
                                                   id="phone" placeholder="Enter Phone Number"
                                                   value="{{ $data->phone }}">
                                            @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="role_id">Roles</label>
                                            <select class="form-control multiselect select2" id="role_id" name="role_id[]" multiple required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ in_array($role->id, $role_ids) ? 'selected' : '' }}>
                                                        {{ $role->display_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('role_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">New Password</label>
                                            <input type="password" name="password" class="form-control"
                                                   id="password" placeholder="Enter Password"
                                                   value="{{ old('password')}}">
                                            @error('password')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="profile_pic" class="form-label">Profile Photo</label>
                                            <input type="file" name="profile_pic" class="form-control" id="profile_pic">
                                            @error('profile_pic')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="profile_preview" src="{{ $data->profile_pic ? Storage::url($data->profile_pic) : asset('defaults/noimage/no_img.jpg') }}" alt="Profile Photo Preview" style="max-width: 200px;">
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
            let htmlForm = $('#musicCategoryForm');
            let validationTimer;

            let thumbnailInput = $('#profile_pic');
            let profilePreview = $('#profile_preview');


            // Preview thumbnail image
            thumbnailInput.change(function(event) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    profilePreview.attr('src', e.target.result);
                    profilePreview.show();
                }
                reader.readAsDataURL(event.target.files[0]);
            });

            let rules = {
                first_name: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                profile_pic: {
                    accept: "image/*"
                },
                phone: {
                    required: true,
                    pattern: /^(01[3-9]\d{8})$/,
                },
                password:{
                    required: false,
                    minlength:5,
                },
                role_id: {
                    required: true,
                    minlength: 1,
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
