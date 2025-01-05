@extends('layouts.sa')

@section('title')
    Relevant Detail
@endsection

@section('body')

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('relevant_detail_creation') }}">
        @csrf

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        Relevant Detail
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                {!! $processMessage !!}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="rno" class="col-6 col-md-3 col-lg-2 col-form-label-sm">RNO</label>
                            <div class="col-sm-1 col-md-2 col-lg-2 col-xl-1">
                                <input type="text" name="rno" id="rno" class="form-control form-control-sm" value="{{ $relevantDetail->getRelevantDetailIdForDisplayAttribute()}}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="relevant_detail" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Relevant Detail</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="text" name="relevant_detail" id="relevant_detail" class="form-control form-control-sm @error('relevant_detail') is-invalid @enderror" value="{{ $relevantDetail->relevant_detail ?? '' }}">
                                @error('relevant_detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="1" {{ $relevantDetail->active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $relevantDetail->active == 0 ? 'selected' : '' }}>No</option>
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
