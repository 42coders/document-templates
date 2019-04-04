@extends('layouts.app')

@section('content')
    <document-template-form
            :initial-data="{{ collect($data) }}"
            :base-url="'{{route('document-templates.index')}}'"
    ></document-template-form>
@endsection