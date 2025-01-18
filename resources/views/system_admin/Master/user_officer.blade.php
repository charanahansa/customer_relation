@extends('layouts.sa')

@section('title')
    User / Officer
@endsection

@section('body')

<div class="container-fluid mt-4">

    <form action="{{ route('user_officer.save') }}" method="POST">

        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        User / Officer
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @elseif( session('error') )
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-1 col-lg-1 col-xl-1">
                                <input type="hidden" name="method" id="method" class="form-control form-control-sm" value="{{ old('method', $user->method ?? '') }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="user_id" class="col-6 col-md-3 col-lg-2 col-form-label-sm">User ID</label>
                            <div class="col-md-1 col-lg-1 col-xl-1">
                                <input type="text" name="user_id" id="user_id" class="form-control form-control-sm @error('user_id') is-invalid @enderror" value="{{ old('user_id', $user->user_id ?? '') }}" readonly>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="first_name" class="col-6 col-md-3 col-lg-2 col-form-label-sm">First Name</label>
                            <div class="col-12 col-md-2">
                                <input type="text" name="first_name" id="first_name" class="form-control form-control-sm @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name ?? '') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="last_name" class="col-6 col-md-3 col-lg-1 col-form-label-sm">Last Name</label>
                            <div class="col-12 col-md-2">
                                <input type="text" name="last_name" id="last_name" class="form-control form-control-sm @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name ?? '') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="email" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer Email</label>
                            <div class="col-md-4 col-lg-4 col-xl-4">
                                <input type="text" name="email" id="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="officer_id" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer ID</label>
                            <div class="col-md-1 col-lg-1 col-xl-1">
                                <input type="text" name="officer_id" id="officer_id" class="form-control form-control-sm @error('officer_id') is-invalid @enderror" value="{{ old('officer_id', $user->officer_id ?? '') }}">
                                @error('officer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="phone" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Phone</label>
                            <div class="col-md-3 col-lg-3 col-xl-2">
                                <input type="text" name="phone" id="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="action_station" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Action Station</label>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                                <select name="action_station" id="action_station" class="form-control form-control-sm @error('action_station') is-invalid @enderror">
                                    @foreach($action_station as $row)
                                        <option value="{{ $row->as_code }}"
                                            @if(old('action_station', $user->action_station) == $row->as_code)
                                                selected
                                            @endif>
                                            {{ $row->action_station }}
                                        </option>
                                    @endforeach
                                    <option value="Not" @if(old('action_station', $user->action_station) == "Not") selected @endif>
                                        Select the Action Station
                                    </option>
                                </select>
                                @error('action_station')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="officer_role" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer Role</label>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                                <select name="officer_role" id="officer_role" class="form-control form-control-sm @error('officer_role') is-invalid @enderror">
                                    @foreach($officer_role as $row)
                                        <option value="{{ $row->role_code }}"
                                            @if(old('officer_role', $user->officer_role) == $row->role_code)
                                                selected
                                            @endif>
                                            {{ $row->role_name }}
                                        </option>
                                    @endforeach
                                    <option value="Not" @if(old('officer_role', $user->officer_role) == "Not") selected @endif>
                                        Select the Officer Role
                                    </option>
                                </select>
                                @error('officer_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="team_lead" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Team Lead</label>
                            <div class="col-12 col-md-2">
                                <select name="team_lead" id="team_lead" class="form-control form-control-sm @error('team_lead') is-invalid @enderror">
                                    <option value="0" {{ old('team_lead', $user->team_lead ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('team_lead', $user->team_lead ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('team_lead')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="head_of_department" class="col-6 col-md-1 col-lg-1 col-form-label-sm">Head of Depatment</label>
                            <div class="col-12 col-md-2">
                                <select name="head_of_department" id="head_of_department" class="form-control form-control-sm @error('head_of_department') is-invalid @enderror">
                                    <option value="0" {{ old('head_of_department', $user->head_of_department ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('head_of_department', $user->head_of_department ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('head_of_department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="courier" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Courier</label>
                            <div class="col-12 col-md-2">
                                <select name="courier" id="courier" class="form-control form-control-sm @error('courier') is-invalid @enderror">
                                    <option value="0" {{ old('courier', $user->courier ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('courier', $user->courier ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('courier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="courier_print" class="col-6 col-md-3 col-lg-1 col-form-label-sm">Courier Print</label>
                            <div class="col-12 col-md-2">
                                <select name="courier_print" id="courier_print" class="form-control form-control-sm @error('courier_print') is-invalid @enderror">
                                    <option value="0" {{ old('courier_print', $user->courier_print ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('courier_print', $user->courier_print ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('courier_print')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="quotation_email" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Quotation Email</label>
                            <div class="col-12 col-md-2">
                                <select name="quotation_email" id="quotation_email" class="form-control form-control-sm @error('quotation_email') is-invalid @enderror">
                                    <option value="0" {{ old('quotation_email', $user->quotation_email ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('quotation_email', $user->quotation_email ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('quotation_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="address" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Address</label>
                            <div class="col-md-5 col-lg-5 col-xl-5">
                                <textarea  name="address" id="address" class="form-control" rows="2" style="resize:none">{{$user->address}}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="0" {{ old('active', $user->active ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('active', $user->active ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="password" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Password</label>
                            <div class="col-md-3 col-lg-3 col-xl-2">
                                <input type="password" name="password" id="password" class="form-control form-control-sm @error('password') is-invalid @enderror" value="">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="confirm_password" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Confirm Password</label>
                            <div class="col-md-3 col-lg-3 col-xl-2">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-sm @error('confirm_password') is-invalid @enderror" value="">
                                @error('confirm_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 row">
                            <div class="col-md-2">
                                <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Save">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Reset">
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
@endsection
