@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <form>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name" placeholder="Document name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Layout</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="layout">
                            @foreach($layouts as $index => $layout)
                                <option value="{{$index}}">{{$layout}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Class</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="class">
                            @foreach($classes as $index => $class)
                                <option value="{{$index}}">{{$class}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Example textarea</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection