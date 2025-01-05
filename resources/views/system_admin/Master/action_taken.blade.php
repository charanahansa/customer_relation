@extends('layouts.sa')

@section('title')
    Action Taken
@endsection

@section('body')

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('action_taken_creation') }}">
        @csrf

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        Action Taken
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                {!! $processMessage !!}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="ano" class="col-6 col-md-3 col-lg-2 col-form-label-sm">ANO</label>
                            <div class="col-sm-1 col-md-2 col-lg-2 col-xl-1">
                                <input type="text" name="ano" id="ano" class="form-control form-control-sm" value="{{ $actionTaken->getActionTakenIdForDisplayAttribute() }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="action_taken" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Action Taken</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="text" name="action_taken" id="action_taken" class="form-control form-control-sm @error('action_taken') is-invalid @enderror" value="{{ $actionTaken->action_taken ?? '' }}">
                                @error('action_taken')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="1" {{ $actionTaken->active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $actionTaken->active == 0 ? 'selected' : '' }}>No</option>
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
