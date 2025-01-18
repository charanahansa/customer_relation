@extends('layouts.sa')
@section('title')
      Master List
@endsection

@section('body')

    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        Master List
                    </div>

                    <div class="card-body">

                        <form method="GET" action="{{ route('master_list') }}" class="mb-5">
                            @csrf
                            <div class="row">

                                <div class="col-12 col-md-3 mb-2">
                                    <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="bank" {{ $type === 'bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="model" {{ $type === 'model' ? 'selected' : '' }}>Model</option>
                                        <option value="fault" {{ $type === 'fault' ? 'selected' : '' }}>Fault</option>
                                        <option value="relevant_detail" {{ $type === 'relevant_detail' ? 'selected' : '' }}>Relevant Detail</option>
                                        <option value="action_taken" {{ $type === 'action_taken' ? 'selected' : '' }}>Action Taken</option>
                                        <option value="re_initialization_reason" {{ $type === 're_initialization_reason' ? 'selected' : '' }}>Re Initialization Reason</option>
                                        <option value="zone" {{ $type === 'zone' ? 'selected' : '' }}>Zones</option>
                                        <option value="buyer" {{ $type === 'user' ? 'selected' : '' }}>Buyer</option>
                                        <option value="user" {{ $type === 'user' ? 'selected' : '' }}>Users</option>
                                    </select>
                                </div>
                                <!-- Search box -->
                                <div class="col-12 col-md-7 mb-2">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search" value="{{ $search }}">
                                </div>

                                <div class="col-12 col-md-2 mb-2">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Search</button>
                                </div>
                            </div>
                        </form>

                        <!-- Responsive Table -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover table-sm table-bordered">
                                <thead>
                                    <tr style="font-family: Consolas; font-size: 15px; background: yellowgreen;">
                                        @if($type === 'zone')
                                            <th>Zone ID</th>
                                            <th>Zone Name</th>
                                            <th>Resolution Time (Hours)</th>
                                            <th>Active</th>
                                        @elseif($type === 'bank')
                                            <th>Bank ID</th>
                                            <th>Bank Code</th>
                                            <th>Bank Name</th>
                                            <th>Active</th>
                                        @elseif($type === 'model')
                                            <th>Model ID</th>
                                            <th>Model Name</th>
                                            <th>Active</th>
                                        @elseif($type === 'fault')
                                            <th>Fault ID</th>
                                            <th>Fault Name</th>
                                            <th>Active</th>
                                        @elseif($type === 'relevant_detail')
                                            <th>RD ID</th>
                                            <th>Relevant Detail</th>
                                            <th>Active</th>
                                        @elseif($type === 'action_taken')
                                            <th>AT ID</th>
                                            <th>Action Taken</th>
                                            <th>Active</th>
                                        @elseif($type === 're_initialization_reason')
                                            <th>RI ID</th>
                                            <th>Reason</th>
                                            <th>Active</th>
                                        @elseif($type === 'buyer')
                                            <th>Buyer ID</th>
                                            <th>Buyer Name</th>
                                            <th>Active</th>
                                        @elseif($type === 'user')
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Active</th>
                                        @endif
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                        <tr style="font-family: Consolas; font-size: 15px;">
                                            @if($type === 'zone')
                                                <td>{{ $item->zone_id }}</td>
                                                <td>{{ $item->zone_name }}</td>
                                                <td>{{ $item->resolution_time }}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('zone_updation', $item->zone_id) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'bank')
                                                <td>{{ $item->bank_id }}</td>
                                                <td>{{ $item->bank }}</td>
                                                <td>{{ $item->bankname }}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('bank_updation', $item->bank_id) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'model')
                                                <td>{{ $item->model_id }}</td>
                                                <td>{{ $item->model }}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('model_updation', $item->model_id) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'fault')
                                                <td>{{ $item->eno }}</td>
                                                <td>{{ $item->error}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('fault_updation', $item->eno) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'relevant_detail')
                                                <td>{{ $item->rno }}</td>
                                                <td>{{ $item->relevant_detail}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('relevant_detail_updation', $item->rno) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'action_taken')
                                                <td>{{ $item->ano }}</td>
                                                <td>{{ $item->action_taken}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('action_taken_updation', $item->ano) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 're_initialization_reason')
                                                <td>{{ $item->ono }}</td>
                                                <td>{{ $item->reason}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('reinitialization_reason_updation', $item->ono) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'buyer')
                                                <td>{{ $item->buyer_id }}</td>
                                                <td>{{ $item->buyer_name}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('buyer_updation', $item->buyer_id) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @elseif($type === 'user')
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->name}}</td>
                                                <td>{{ $item->active ? 'Yes' : 'No' }}</td>
                                                <td>
                                                    <a href="{{ route('user_updation', $item->id) }}" class="btn btn-primary btn-sm w-100">Edit</a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
