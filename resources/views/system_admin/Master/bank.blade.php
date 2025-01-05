@extends('layouts.sa')
@section('title')
    Bank
@endsection

@section('body')

    <div class="container-fluid mt-3">
        <form method="POST" action="{{ route('bank_creation') }}">
            @csrf

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Bank
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12">
                                    {!! $processMessage !!}
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="bank" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Bank Code</label>
                                <div class="col-sm-1 col-md-3 col-lg-3 col-xl-2">
                                    <input type="text" name="bank" id="bank" class="form-control form-control-sm @error('bank') is-invalid @enderror" value="{{ old('bank', $bank->bank ?? '') }}">
                                    @error('bank')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="bankname" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Bank Name</label>
                                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                    <input type="text" name="bankname" id="bankname" class="form-control form-control-sm @error('bankname') is-invalid @enderror" value="{{ old('bankname', $bank->bankname ?? '') }}">
                                    @error('bankname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="tnms_maintain" class="col-6 col-md-3 col-lg-2 col-form-label-sm">TNMS Maintain</label>
                                <div class="col-12 col-md-2">
                                    <select name="tnms_maintain" id="tnms_maintain" class="form-control form-control-sm @error('tnms_maintain') is-invalid @enderror">
                                        <option value="0" {{ old('tnms_maintain', $bank->tnms_maintain ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('tnms_maintain', $bank->tnms_maintain ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('tnms_maintain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="repair_maintain" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Repair Maintain</label>
                                <div class="col-12 col-md-2">
                                    <select name="repair_maintain" id="repair_maintain" class="form-control form-control-sm @error('repair_maintain') is-invalid @enderror">
                                        <option value="0" {{ old('repair_maintain', $bank->repair_maintain ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('repair_maintain', $bank->repair_maintain ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('repair_maintain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="full_maintain" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Full Maintain</label>
                                <div class="col-12 col-md-2">
                                    <select name="full_maintain" id="full_maintain" class="form-control form-control-sm @error('full_maintain') is-invalid @enderror">
                                        <option value="0" {{ old('full_maintain', $bank->full_maintain ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('full_maintain', $bank->full_maintain ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('full_maintain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="qasp" class="col-6 col-md-3 col-lg-2 col-form-label-sm">QASP</label>
                                <div class="col-12 col-md-2">
                                    <select name="qasp" id="qasp" class="form-control form-control-sm @error('qasp') is-invalid @enderror">
                                        <option value="0" {{ old('qasp', $bank->qasp ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                        <option value="1" {{ old('qasp', $bank->qasp ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                    </select>
                                    @error('qasp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="printname" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Print Name</label>
                                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                    <input type="text" name="printname" id="printname" class="form-control form-control-sm @error('printname') is-invalid @enderror" value="{{ old('printname', $bank->printname ?? '') }}">
                                    @error('printname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="sc_code" class="col-6 col-md-3 col-lg-2 col-form-label-sm">SC Code</label>
                                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                    <input type="text" name="sc_code" id="sc_code" class="form-control form-control-sm @error('sc_code') is-invalid @enderror" value="{{ old('sc_code', $bank->sc_code ?? '') }}">
                                    @error('sc_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="address" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Address</label>
                                <div class="col-12 col-md-8 col-lg-8 col-xl-8">
                                    <input type="text" name="address" id="address" class="form-control form-control-sm @error('address') is-invalid @enderror" value="{{ old('address', $bank->address ?? '') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="lotno" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Lot No</label>
                                <div class="col-12 col-md-2">
                                    <input type="number" name="lotno" id="lotno" class="form-control form-control-sm @error('lotno') is-invalid @enderror" value="{{ old('lotno', $bank->lotno ?? '') }}">
                                    @error('lotno')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="qt_no" class="col-6 col-md-3 col-lg-2 col-form-label-sm">QT No</label>
                                <div class="col-12 col-md-2">
                                    <input type="number" name="qt_no" id="qt_no" class="form-control form-control-sm @error('qt_no') is-invalid @enderror" value="{{ old('qt_no', $bank->qt_no ?? '') }}">
                                    @error('qt_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="bd_eml" class="col-6 col-md-3 col-lg-2 col-form-label-sm">BD EML</label>
                                <div class="col-12 col-md-2">
                                    <input type="number" name="bd_eml" id="bd_eml" class="form-control form-control-sm @error('bd_eml') is-invalid @enderror" value="{{ old('bd_eml', $bank->bd_eml ?? '') }}">
                                    @error('bd_eml')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <label for="bd_report" class="col-6 col-md-3 col-lg-2 col-form-label-sm">BD Report</label>
                                <div class="col-12 col-md-2">
                                    <input type="number" name="bd_report" id="bd_report" class="form-control form-control-sm @error('bd_report') is-invalid @enderror" value="{{ old('bd_report', $bank->bd_report ?? '') }}">
                                    @error('bd_report')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-5">
                                <label for="warrant_month" class="col-6 col-md-3 col-lg-2 col-form-label-sm">Warranty Month</label>
                                <div class="col-12 col-md-2">
                                    <input type="number" name="warrant_month" id="warrant_month" class="form-control form-control-sm @error('warrant_month') is-invalid @enderror" value="{{ old('warrant_month', $bank->warrant_month ?? 0) }}">
                                    @error('warrant_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                                    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-sm w-100" value="Save">
                                </div>
                                <div class="col-6 col-md-3 col-lg-2 col-xl-2">
                                    <input type="submit" name="submit" id="reset" class="btn btn-secondary btn-sm w-100" value="Reset">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </form>
    </div>

@endsection
