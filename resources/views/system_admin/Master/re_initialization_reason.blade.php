@extends('layouts.sa')

@section('title')
    Reinitialization Reason
@endsection

@section('body')

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('reinitialization_reason_creation') }}">
        @csrf

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        Reinitialization Reason
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                {!! $processMessage !!}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="ono" class="col-6 col-md-3 col-lg-2 col-form-label-sm">ONO</label>
                            <div class="col-sm-1 col-md-2 col-lg-2 col-xl-1">
                                <input type="text" name="ono" id="ono" class="form-control form-control-sm" value="{{ $reinitializationReason->getReasonIdForDisplayAttribute() }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="reason" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Reason</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="text" name="reason" id="reason" class="form-control form-control-sm @error('reason') is-invalid @enderror" value="{{ $reinitializationReason->reason ?? '' }}">
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="1" {{ $reinitializationReason->active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $reinitializationReason->active == 0 ? 'selected' : '' }}>No</option>
                                </select>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
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
