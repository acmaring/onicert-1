@extends('layouts.app')

@section('content')

@if (Route::has('login'))
    @auth
        @foreach ($pregunta as $pre)

            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">Modificar pregunta</div>
                            <div class="card-body">
                                <form action="/admin/save" method="post">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <label for="pregunta" class="col-md-2">Pregunta:</label>
                                        {{-- <input type="text" name="pregunta" placeholder="{{ $pre->pre_content }}"> --}}
                                        <input type="hidden" name="id" value="{{ $pre->pre_id }}">
                                        <div class="col-md-10">
                                            <textarea name="pregunta" class="form-control" required="">{{ $pre->pre_content }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="esquema" class="col-md-2">Esquema:</label>
                                        <div class="col-md-10">
                                            <select id="esquema" class="form-control" name="esquema" data-url="{{  url('/admin/validate') }}" required="">
                                                <option selected disabled>Seleccionar</option>
                                                @foreach ($esquema as $esq)
                                                    @if ($pre->esq_id == $esq->esq_id)
                                                        <option value="{{ $esq->esq_id }}" selected="">{{ $esq->esq_name }}</option>
                                                    @else
                                                        <option value="{{ $esq->esq_id }}">{{ $esq->esq_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="competencia" class="col-md-3">Competencia:</label>
                                        <div class="col-md-9">
                                            <select id="competencia" class="form-control" name="competencia" required="">
                                                @foreach ($competencia as $com)
                                                    @if ($pre->pre_com_id == $com->com_id)
                                                        <option value="{{ $com->com_id }}" selected="">{{ $com->com_name }}</option>
                                                    @else
                                                        <option value="{{ $com->com_id }}">{{ $com->com_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-2">Tipo:</label>
                                        <div class="col-md-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" value=0 required="" {{ $pre->pre_restrict === 0 ? "checked" : "" }}>
                                                <label class="form-check-label" for="restrict">Ninguno</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" value=1 required="" {{ $pre->pre_restrict === 1 ? "checked" : "" }}>
                                                <label class="form-check-label" for="restrict">Examen 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restrict" value=2 required="" {{ $pre->pre_restrict === 2 ? "checked" : "" }}>
                                                <label class="form-check-label" for="restrict">Examen 2</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-md-2">Respuestas:</label>
                                        <div class="col-md-10">
                                            @if ($respuesta[0]->res_correct == 1)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaA">a</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaA" value={{ $respuesta[0]->res_content }}>
                                                        <input type="hidden" name="res_idA" value="{{ $respuesta[0]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="a" checked>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaB">b</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaB" value={{ $respuesta[1]->res_content }}>
                                                        <input type="hidden" name="res_idB" value="{{ $respuesta[1]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="b">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaC" class="input-group-text">c</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaC" value={{ $respuesta[2]->res_content }}>
                                                        <input type="hidden" name="res_idC" value="{{ $respuesta[2]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="c">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaD" class="input-group-text">d</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaD" value={{ $respuesta[3]->res_content }}>
                                                        <input type="hidden" name="res_idD" value="{{ $respuesta[3]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="d">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($respuesta[1]->res_correct == 1)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaA">a</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaA" value={{ $respuesta[0]->res_content }}>
                                                        <input type="hidden" name="res_idA" value="{{ $respuesta[0]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="a">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaB">b</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaB" value={{ $respuesta[1]->res_content }}>
                                                        <input type="hidden" name="res_idB" value="{{ $respuesta[1]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="b" checked="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaC" class="input-group-text">c</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaC" value={{ $respuesta[2]->res_content }}>
                                                        <input type="hidden" name="res_idC" value="{{ $respuesta[2]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="c">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaD" class="input-group-text">d</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaD" value={{ $respuesta[3]->res_content }}>
                                                        <input type="hidden" name="res_idD" value="{{ $respuesta[3]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="d">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($respuesta[2]->res_correct == 1)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaA">a</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaA" value={{ $respuesta[0]->res_content }}>
                                                        <input type="hidden" name="res_idA" value="{{ $respuesta[0]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="a">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaB">b</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaB" value={{ $respuesta[1]->res_content }}>
                                                        <input type="hidden" name="res_idB" value="{{ $respuesta[1]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="b">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaC" class="input-group-text">c</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaC" value={{ $respuesta[2]->res_content }}>
                                                        <input type="hidden" name="res_idC" value="{{ $respuesta[2]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="c" checked="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaD" class="input-group-text">d</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaD" value={{ $respuesta[3]->res_content }}>
                                                        <input type="hidden" name="res_idD" value="{{ $respuesta[3]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="d">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($respuesta[3]->res_correct == 1)
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaA">a</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaA" value={{ $respuesta[0]->res_content }}>
                                                        <input type="hidden" name="res_idA" value="{{ $respuesta[0]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="a">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text" for="respuestaB">b</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaB" value={{ $respuesta[1]->res_content }}>
                                                        <input type="hidden" name="res_idB" value="{{ $respuesta[1]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="b">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaC" class="input-group-text">c</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaC" value={{ $respuesta[2]->res_content }}>
                                                        <input type="hidden" name="res_idC" value="{{ $respuesta[2]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="c">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <label for="respuestaD" class="input-group-text">d</label>
                                                        </div>
                                                        <input class="form-control" type="text" name="respuestaD" value={{ $respuesta[3]->res_content }}>
                                                        <input type="hidden" name="res_idD" value="{{ $respuesta[3]->res_id }}">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <input type="radio" required="" name="correcta" value="d" checked="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($errors->any())
                                        @foreach ($errors->get('pregunta') as $error)
                                            {{ $error }}
                                        @endforeach
                                    @endif

                                    <input type="hidden" name="competenciaH" value="{{$pre->com_id}}">
                                    <input type="hidden" name="esquemaH" value="{{$pre->esq_id}}">

                                    <input type="submit" class="btn btn-primary" value="Guardar cambios">
                                    <a class="btn btn-primary" href="{{ url('admin/show') }}?esquema={{$pre->esq_id}}&competencia={{$pre->com_id}}">Cancelar</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/form.js') }}"></script>
    @endauth
@endif
@endsection