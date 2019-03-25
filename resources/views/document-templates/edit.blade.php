@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-9">
                <form method="POST" action="{{route('document-templates.update', $documentTemplate->id)}}">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" name="name" placeholder="Document name" value="Document name">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Layout</label>
                        <select class="form-control" id="exampleFormControlSelect1" name="layout">
                            @foreach($layouts as $index => $layout)
                                <option value="{{$index}}" @if($layout == $documentTemplate->layout) selected="selected"@endif>{{$layout}}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach($templates as $index => $template)
                        <input type="hidden" name="templates[{{$index}}][id]" value="{{$template->id}}">
                        <input type="hidden" name="templates[{{$index}}][name]" value="{{$template->getName()}}">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Template "<b>{{$template->getName()}}</b>"</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="templates[{{$index}}][content]" rows="3">
                                {{$template->getContent()}}
                            </textarea>
                        </div>

                    @endforeach
                    <button type="submit" class="btn btn-primary mb-2">Save</button>
                    <a class="btn btn-secondary mb-2" target="_blank" href="{{route('document-templates.update', $documentTemplate->id)}}">Render</a>
                </form>
            </div>
            <div class="col-3">
                <h4>Placeholders</h4>
                <ul>
                    @foreach($placeholders as $key => $placeholder)
                        @if(is_array($placeholder))
                            @php
                                $childPlaceholder = $placeholder[0];
                                $childPlaceholderParts = explode('.', $childPlaceholder);
                            @endphp
                            <li> {%  for {{$childPlaceholderParts[0]}} in {{$key}} %}
                                <ul>
                            @foreach($placeholder as $childPlaceholder)
                                <li>
                                    &#123;&#123;{{$childPlaceholder}}&#125;&#125;
                                </li>
                            @endforeach
                                </ul>
                                {% endfor %}
                            </li>
                        @else
                            <li>
                                &#123;&#123;{{$placeholder}}&#125;&#125;
                            </li>
                        @endif
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
@endsection