@extends('layouts.sa')

@section('title')
    System Admin Dashboard
@endsection

@section('body')

    <form method="POST" name="form_name" action="{{route('individual_email_process')}}">

        @csrf

        <div class="col-sm-12">

            <br>
            <p id="demo"></p>

            <div class="mb-2 row">

                <div class="col-sm-3">
                    <input type="submit" name="submit" id="submit" style="width: 100%;" class="btn btn-primary btn-sm" value="Process">
                </div>

            </div>
        </div>

    </form>

@endsection
<script>

    // setInterval(myTimer, (1000 * 60 * 3));
    // function myTimer() {
    //     document.getElementById('submit').click();
    // }

</script>
