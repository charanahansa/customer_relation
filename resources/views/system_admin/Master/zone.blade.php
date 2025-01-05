@extends('layouts.sa')
@section('title')
    Zones
@endsection

@section('body')

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('zone_creation') }}">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Zones
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                {!! $processMessage !!}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="zone_id" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Zone ID</label>
                            <div class="col-sm-1 col-md-2 col-lg-2 col-xl-1">
                                <input type="text" name="zone_id" id="zone_id" class="form-control form-control-sm @error('zone_id') is-invalid @enderror"  value="{{$zone->getZoneIdForDisplayAttribute() }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="zone_name" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Zone Name</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="text" name="zone_name" id="zone_name" class="form-control form-control-sm @error('zone_name') is-invalid @enderror" value="{{ old('zone_name', $zone->zone_name) }}">
                                @error('zone_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="resolution_time" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Resolution Time</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="number" name="resolution_time" id="resolution_time" class="form-control form-control-sm @error('resolution_time') is-invalid @enderror" value="{{ old('resolution_time', $zone->resolution_time) }}">
                                @error('resolution_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm">
                                    <option value="1" {{ $zone->active ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$zone->active ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                                <input type="submit" name="submit" class="btn btn-primary btn-sm w-100" value="Save">
                            </div>
                            <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                                <input type="submit" name="submit" class="btn btn-secondary btn-sm w-100" value="Reset">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection
