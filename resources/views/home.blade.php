
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$user->user_type}} {{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if($user->user_type === "Student")
                            <h3>These are the ongoing classes available on the system</h3>
                            @foreach($classes as $key=>$class)
                                <a href="{{route('classroom', ['id' => $class->id])}}">{{$key + 1}}. {{$class->name}}</a>
                                <br />
                            @endforeach
                        @else
                            <h4>Welcome {{$user->name}}. Fill the form below to create a class</h4>
                            <form method="POST" action="{{ route('create_class') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-12 col-form-label">{{ __('Class Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="{{ old('name') }}" required autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Create Class') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
