@extends('layouts.admin.master')

@section('title')
    View Role
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card dashboard-custom-card">
                    <div class="card-body">
                        <div class="card-header custom-card-header p-0  border-0">
                            <h4 class="card-title">Role</h4>

                            <div class="card-tools">
                                <a href="{{route('roles.edit', [$role->id])}}" class="create-button bg-custom2 mr-1">
                                    <i class="fas fa-edit"></i> Edit Role </a>
                                <a href="{{route('roles.index')}}" class="create-button">
                                    <i class="fas fa-backward"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Name</p>
                                    <div class="input-box">
                                        {{ $role->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Display Name</p>
                                    <div class="input-box">
                                        {{ $role->display_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Description</p>
                                    <div class="input-box">
                                        {{ $role->description }}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
