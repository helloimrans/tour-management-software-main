@extends('layouts.admin.master')

@section('title')
    View Permission
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card dashboard-custom-card">
                    <div class="card-body">
                        <div class="card-header custom-card-header p-0  border-0">
                            <h4 class="">Permission</h4>

                            <div class="card-tools">
                                    <a href="{{route('permissions.edit', [$permission->id])}}" class="btn btn-primary mr-1">
                                        <i class="fas fa-edit"></i> Edit Permission
                                    </a>
                                    <a href="{{route('permissions.index')}}" class="btn btn-info">
                                        <i class="fas fa-backward"></i> Back
                                    </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Name</p>
                                    <div class="input-box">
                                        {{ $permission->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Display Name</p>
                                    <div class="input-box">
                                        {{ $permission->display_name }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-view-box mb-4">
                                    <p class="label-text text-bold mb-0">Group Name</p>
                                    <div class="input-box">
                                        {{ $permission->group_name }}
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
