@extends('layouts.sa')

@section('title')
    Buyer
@endsection

@section('body')

<div class="container-fluid mt-3">
    <form method="POST" action="{{ route('buyer.save') }}">
        @csrf

        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        Buyer
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                {!! $processMessage !!}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="buyer_id" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Buyer Id</label>
                            <div class="col-sm-1 col-md-2 col-lg-2 col-xl-1">
                                <input type="text" name="buyer_id" id="buyer_id" class="form-control form-control-sm" value="{{ $buyer->getBuyerIdForDisplayAttribute() }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <label for="buyer_name" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Buyer Name</label>
                            <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                <input type="text" name="buyer_name" id="buyer_name" class="form-control form-control-sm @error('buyer_name') is-invalid @enderror" value="{{ $buyer->buyer_name ?? '' }}">
                                @error('buyer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <label for="active" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Active</label>
                            <div class="col-12 col-md-2">
                                <select name="active" id="active" class="form-control form-control-sm @error('active') is-invalid @enderror">
                                    <option value="1" {{ $buyer->active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $buyer->active == 0 ? 'selected' : '' }}>No</option>
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
