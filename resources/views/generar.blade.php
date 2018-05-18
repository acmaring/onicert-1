@extends('layouts.app')

@section('content')
	@if (Route::has('login'))
		@auth
			<div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                        	<div class="card-header">
                        		<p>Exámen generado</p>
                        		<div class="btn-descargar">
                        			<a class="btn btn-primary" href="{{ url('/generate/word?pre_doc='.$pregunta_doc) }}">Descargar</a>
								   	<a class="btn btn-primary" href="{{ url('/generate/answer?res_doc='.$respuesta_doc) }}">Descargar HR</a>
                        		</div>
                        	</div>
                        	<div class="card-body">
                        		@foreach ($esquema as $esq)
									<h3 class="prev_esquema">{{ $esq->esq_name }} - {{ $esq->esq_code }}</h3>
								@endforeach
								@php
									$count=0;
								@endphp
								@foreach ($competencia as $com)
									<h4 class="prev_competencia">{{ $com->com_name }}</h4>
									<ol>
									@foreach ($pregunta as $pre)
										@if ($pre->pre_com_id == $com->com_id)
											@php
												$opciones = ['a. ','b. ','c. ','d. '];
												$countOpciones = 0;
											@endphp
										    <li type="1">{{ $pre->pre_content }}:
										    	<ol>
												    @foreach ($respuesta as $res)
												    	@if ($res->res_pre_id == $pre->pre_id)
												    		<li type="a">{{ $res->res_content }}</li>
												    	@endif
												    @endforeach
											    </ol>
											</li>
										@endif
									@endforeach
									</ol>
								@endforeach
                        	</div>
                        </div>
                    </div>
                </div>
            </div>
		@endauth
	@endif
	{{-- {{ $restriccion }} --}}
@endsection