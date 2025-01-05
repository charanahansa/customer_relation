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

                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="officer_name" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer Name</label>
                            <div class="col-md-6 col-lg-6 col-xl-6">
                                <input type="text" name="officer_name" id="officer_name" class="form-control form-control-sm @error('officer_name') is-invalid @enderror" value="{{ old('officer_name', $officer->officer_name ?? '') }}">
                                @error('officer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="officer_email" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer Email</label>
                            <div class="col-md-4 col-lg-4 col-xl-4">
                                <input type="text" name="officer_email" id="officer_email" class="form-control form-control-sm @error('officer_email') is-invalid @enderror" value="{{ old('officer_email', $officer->officer_email ?? '') }}">
                                @error('officer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="officer_id" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Officer ID</label>
                            <div class="col-md-1 col-lg-1 col-xl-1">
                                <input type="text" name="officer_id" id="officer_id" class="form-control form-control-sm @error('officer_id') is-invalid @enderror" value="{{ old('officer_id', $officer->officer_id ?? '') }}">
                                @error('officer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="phone" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Phone</label>
                            <div class="col-md-3 col-lg-3 col-xl-2">
                                <input type="text" name="phone" id="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $officer->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="action_station" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Action Station</label>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                                <select name="action_station" id="action_station" class="form-control form-control-sm @error('action_station') is-invalid @enderror">
                                    @foreach($User['action_station'] as $row)
                                        @if($User['attributes']['action_station'] == $row->as_code)
                                            <option value ="{{$row->as_code}}" selected>{{$row->action_station}}</option>
                                        @else
                                            <option value ="{{$row->as_code}}">{{$row->action_station}}</option>
                                        @endif
                                    @endforeach
                                    @if($User['attributes']['action_station'] == "Not")
                                        <option value ="Not" selected>Select the Action Station</option>
                                    @else
                                        <option value ="Not">Select the Action Station</option>
                                    @endif
                                </select>
                                @error('action_station')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="job_role" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Job Role</label>
                            <div class="col-md-3 col-lg-3 col-xl-3">
                                <select name="job_role" id="job_role" class="form-control form-control-sm @error('job_role') is-invalid @enderror">

                                </select>
                                @error('job_role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="team_lead" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Team Lead</label>
                            <div class="col-12 col-md-2">
                                <select name="team_lead" id="team_lead" class="form-control form-control-sm @error('team_lead') is-invalid @enderror">
                                    <option value="0" {{ old('team_lead', $officer->team_lead ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('team_lead', $officer->team_lead ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('team_lead')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="courier" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Courier</label>
                            <div class="col-12 col-md-2">
                                <select name="courier" id="courier" class="form-control form-control-sm @error('courier') is-invalid @enderror">
                                    <option value="0" {{ old('courier', $officer->courier ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('courier', $officer->courier ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('courier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="courier_print" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Courier Print</label>
                            <div class="col-12 col-md-2">
                                <select name="courier_print" id="courier_print" class="form-control form-control-sm @error('courier_print') is-invalid @enderror">
                                    <option value="0" {{ old('courier_print', $officer->courier_print ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('courier_print', $officer->courier_print ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
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
                                    <option value="0" {{ old('quotation_email', $officer->quotation_email ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('quotation_email', $officer->quotation_email ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('quotation_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="address" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Address</label>
                            <div class="col-md-5 col-lg-5 col-xl-5">
                                <textarea  name="address" id="address" class="form-control" rows="2" style="resize:none"></textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="0" {{ old('active', $officer->active ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('active', $officer->active ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('active')
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
