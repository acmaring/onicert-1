@extends('layouts.app')

@section('content')

@if (Route::has('login'))
    @auth
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center pb-5 pt-3">{{ $competenciaName }}</h1>
                </div>
            </div>
            <div class="row">
            @if (count($pregunta) > 0)
                @foreach ($pregunta as $valor)
                    <div class="col-md-6 mt-3 mb-3">
                        <div class="d-flex">
                            <a class="btn btn-secondary btn-block dropdown-toggle" data-toggle="collapse" href="#collapse{{ $valor->pre_id }}" role="button" aria-expanded="false" aria-controls="collapseExample">{{ $valor->pre_content }}</a>
                            <form action="/admin/edit" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="editar" value="{{ $valor->pre_id }}">
                                <input class="btn btn-outline-info btn-opt-q" type="submit" value="Modificar">
                            </form>
                            <form action="/admin/erase" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="borrar" value="{{ $valor->pre_id }}">
                                <input class="btn btn-outline-danger btn-opt-q" type="submit" value="Borrar">
                            </form>
                        </div>
                        <div class="collapse show" id="collapse{{ $valor->pre_id }}">
                            <div class="card card-body">
                                <ol class="options_q">
                                    @foreach ($respuesta as $res)
                                        @if ($res->res_pre_id == $valor->pre_id)
                                            @if ($res->res_correct == 1)
                                                <li type="a" class="correct">{{ $res->res_content }}</li>
                                            @else
                                                <li type="a">{{ $res->res_content }}</li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <h5>No hay preguntas en el momento.</h5>
                    <a class="btn btn-primary" href="{{ route('admin') }}">Regresar</a>
                </div>
            @endif
            </div>
        </div>
    @endauth
@endif

@endsection