@extends('includes.main')

@section('title')
    Home
@endsection

@section('content')
    @if (session('statusSuccess'))
        <div class="alert alert-success">
            {{ session('statusSuccess') }}
        </div>
    @elseif (session('statusError'))
    <div class="alert alert-success">
        {{ session('statusError') }}
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
@endsection
