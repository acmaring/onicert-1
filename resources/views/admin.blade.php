@extends('layouts.app')

@section('content')
    @if (Route::has('login'))
        @auth
            {{-- Formulario para agregar preguntas y respuestas --}}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Crear pregunta</div>
                            <div class="card-body">
                                <form action="/admin/create" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="pregunta" class="col-md-2 col-form-label">Pregunta: </label>
                                        <div class="col-md-10">
                                            <textarea name="pregunta" class="form-control" required=""></textarea>
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <label for="esquema" class="col-md-2 col-form-label">Esquema :</label>
                                        <div class="col-md-10">
                                            <select class="form-control" id="esquemaCreate" name="esquema" data-url="{{  url('/admin/validate') }}" required="">
                                                <option selected disabled>Selecionar</option>
                                                @foreach ($esquema as $element)
                                                    <option value="{{ $element->esq_id }}">{{ $element->esq_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="competenciaCreate" class="col-md-3 col-form-label">Competencia: </label>
                                        <div class="col-md-9">
                                            <select id="competenciaCreate" class="form-control" name="competencia" required="">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-2">Tipo:</label>
                                        <div class="col-md-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" id="restrict0" value=0 checked="" required>
                                                <label class="form-check-label" for="restrict0">Ninguno</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" id="restrict1" value=1>
                                                <label class="form-check-label" for="restrict1">Examen 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" id="restrict2" value=2>
                                                <label class="form-check-label" for="restrict2">Examen 2</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" id="restrict3" value=3>
                                                <label class="form-check-label" for="restrict3">Examen 3</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-3">Respuestas: </label>
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <label class="input-group-text" for="respuestaA">a</label>
                                                    </span>
                                                    <textarea class="form-control" name="respuestaA" required=""></textarea>
                                                    <span class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" name="correcta" value="a" required="">
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="respuestaB">b</label>
                                                    </div>
                                                    <textarea class="form-control" name="respuestaB" required=""></textarea>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" name="correcta" value="b" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="respuestaC">c</label>
                                                    </div>
                                                    <textarea class="form-control" name="respuestaC" required=""></textarea>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" name="correcta" value="c" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="respuestaD">d</label>
                                                    </div>
                                                    <textarea class="form-control" name="respuestaD" required=""></textarea>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="radio" name="correcta" value="d" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                    @if ($errors->any())
                                        @foreach ($errors->get('pregunta') as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif
                                
                                    <input type="submit" value="Guardar" class="btn btn-primary">
                                    <!-- <input type="button" name="cancelar" value="Limpiar campos"> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Mostrar pregunta por competencia</div>
                            <div class="card-body">
                                <form action="/admin/show" method="get">
                                    <div class="form-group">
                                        <label for="esquema">Esquema :</label>
                                        <select class="form-control" id="esquema" name="esquema" data-url="{{  url('/admin/validate') }}">
                                            <option selected disabled>Selecionar</option>
                                            @foreach ($esquema as $element)
                                                <option value="{{ $element->esq_id }}">{{ $element->esq_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Competencia: </label>
                                        <select class="form-control" id="competencia" name="competencia">
                                        </select>
                                    </div>
                                    <input class="btn btn-primary" type="submit" value="Mostrar">
                                    <!-- <input class="btn btn-link" type="button" name="cancelar" value="Cancelar" onclick="location.href='/'"> -->
                                </form>
                                {{-- <a href="{{ route('showComp', array('pre_id' => 'allie.hackett')) }}">Mostrar preguntas</a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- JQery --}}
            <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
            <script type="text/javascript" src="{{ asset('js/form.js') }}"></script>
        @endauth
    @endif
@endsection
