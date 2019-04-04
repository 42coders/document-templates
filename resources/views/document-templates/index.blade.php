@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Document class')</th>
                        <th>@lang('Layout')</th>
                        <th>@lang('Created at')</th>
                        <th>@lang('Modified at')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($documentTemplates as $documentTempate)
                            <tr>
                                <td><a href="{{route('document-templates.edit', $documentTempate->id)}}">{{$documentTempate->name}}</a></td>
                                <td>{{$documentTempate->document_class}}</td>
                                <td>{{$documentTempate->layout}}</td>
                                <td>{{$documentTempate->created_at}}</td>
                                <td>{{$documentTempate->updated_at}}</td>
                                <td>
                                    <a class="btn btn btn-primary" target="_blank" href="{{route('document-templates.show', $documentTempate->id)}}">Render</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection