@extends('layouts.app')

@section('content')
    <document-template-form
            :initial-data="{{ collect($data) }}"
            :base-url="'{{route(config('document_templates.base_url') . '.index')}}'"
    ></document-template-form>
@endsection