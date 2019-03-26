@extends('layouts.app')

@section('content')
    <document-template-form :initial-data="{{ collect($data) }}"></document-template-form>
    {{--<div class="container">--}}
        {{--<div class="row justify-content-center">--}}
            {{--<div class="col-9">--}}
                {{--<form method="POST" action="{{route('document-templates.store')}}">--}}
                    {{--@csrf--}}
                    {{--{{ method_field('POST') }}--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="exampleFormControlInput1">Name</label>--}}
                        {{--<input type="text" class="form-control" id="exampleFormControlInput1" name="name" placeholder="Document name" value="Document name">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="exampleFormControlSelect1">Layout</label>--}}
                        {{--<select class="form-control" id="exampleFormControlSelect1" name="layout">--}}
                            {{--@foreach($layouts as $index => $layout)--}}
                                {{--<option value="{{$index}}">{{$layout}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label for="exampleFormControlSelect1">Class</label>--}}
                        {{--<select class="form-control" id="exampleFormControlSelect1" name="document_class">--}}
                            {{--@foreach($classes as $index => $class)--}}
                                {{--<option value="{{$index}}">{{$class}}</option>--}}
                            {{--@endforeach--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<button type="submit" class="btn btn-primary mb-2">Save</button>--}}
                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endsection