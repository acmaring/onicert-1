@extends('layouts.app')

@section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Generar Examen</div>

                        <div class="card-body">
                            <form action="/generate" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="esq">Seleccionar Esquema: </label>
                                    <select name="esq" class="form-control">
                                        @foreach ($esquema as $esq)
                                            <option value="{{ $esq->esq_id }}">{{ $esq->esq_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Generar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection