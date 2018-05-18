<?php

namespace App\Http\Controllers;

use App\Competencia;
use App\Esquema;
use App\Respuesta;
use App\Http\Requests\CreateQuestionRequest;
use App\Pregunta;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mostrarPregunta(Request $competencia){

        $competenciaName = Competencia::where('com_id', $competencia->input('competencia'))->get();
        $competenciaName = $competenciaName[0]->com_name;
        $competencia = $competencia->input('competencia');
        // $esquema = $competencia->input('esquema');

        //Muestra todas las preguntas
        $pregunta = Pregunta::where('pre_com_id',$competencia)->get();
        #$respuesta = Respuesta::where('res_pre_id')
        $respuesta = Respuesta::all();
        //dd($respuesta);
        return view('mostrar', [
            'pregunta' => $pregunta,
            'respuesta' => $respuesta,
            'competenciaName' => $competenciaName
        ]);
    }

    /*public function mostrarPregunta(Request $request){

        //Muestra todas las preguntas
        $pregunta = Pregunta::where('pre_com_id',$request->input('competencia'))->get();  
        dd($pregunta);
        return view('mostrar', [
            'pregunta' => $pregunta
        ]);
    }*/

    public function crearPregunta(CreateQuestionRequest $request){
    	
        $mensaje = [];

        //Guarda pregunta en base de datos
    	$pregunta = Pregunta::create([

    		'pre_content'	=> $request->input('pregunta'),
    		#'pre_id' => $request->input('id'),
    		'pre_restrict' => $request->input('restrict'),
    		'pre_com_id' => $request->input('competencia'),
    	]);

        if ($pregunta) {
            
            array_push($mensaje, 'Pregunta creada correctamente');

            $comCant = Competencia::select('com_cant')->where('com_id', $request->input('competencia'))->get();
            
            $competencia = Competencia::where('com_id',$request->input('competencia'))->update([
                'com_cant' => $comCant[0]->com_cant + 1
            ]);

            //Para convierte correcta a entero
            if ($request->input('correcta') == 'a') {
                $correctaA = 1;
            }else{
                $correctaA = 0;
            }

            if ($request->input('correcta') == 'b') {
                $correctaB = 1;
            } else {
                $correctaB = 0;
            }

            if ($request->input('correcta') == 'c') {
                $correctaC = 1;
            }else{
                $correctaC = 0;
            }

            if ($request->input('correcta') == 'd') {
                $correctaD = 1;
            }else{
                $correctaD = 0;
            }
            
            //Obtiene el ultimo id de pregunta añadido para agregarle como llave foranea a respuesta
            $res_pre_id = Pregunta::max('pre_id');

            //Guarda las respuestas
            $respuestaA = Respuesta::create([

                'res_content'   => $request->input('respuestaA'),
                'res_correct' => $correctaA,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaB = Respuesta::create([

                'res_content'   => $request->input('respuestaB'),
                'res_correct' => $correctaB,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaC = Respuesta::create([

                'res_content'   => $request->input('respuestaC'),
                'res_correct' => $correctaC,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaD = Respuesta::create([

                'res_content'   => $request->input('respuestaD'),
                'res_correct' => $correctaD,
                'res_pre_id' => $res_pre_id
            ]);

            // if ($respuestaA) {
            //     if ($correctaA) {
            //         array_push($mensaje, 'Respuesta A (correcta) creada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta A creada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se creo la respuesta A');
            // }
            // if ($respuestaB) {
            //     if ($correctaB) {
            //         array_push($mensaje, 'Respuesta B (correcta) creada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta B creada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se creo la respuesta B');
            // }
            // if ($respuestaC) {
            //     if ($correctaC) {
            //         array_push($mensaje, 'Respuesta C (correcta) creada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta C creada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se creo la respuesta C');
            // }
            // if ($respuestaD) {
            //     if ($correctaD) {
            //         array_push($mensaje, 'Respuesta D (correcta) creada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta D creada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se creo la respuesta D');
            // }
        }else{
            array_push($mensaje, 'No se crearon las preguntas ni las respuestas');
        }

        return back()->with('mensaje', $mensaje);
    }

    public function modificarPregunta(Request $request){

        // $prueba = DB::table('preguntas')
        //     ->join('competencias', 'competencias.com_id', 'preguntas.pre_com_id')
        //     ->join('esquemas', 'esquemas.esq_id', 'competencias.com_esq_id')
        //     ->where('pre_id',$request->input('editar'))
        //     ->get();
            // ->join('esquemas', 'esquemas.esq_id', '')

        $pregunta = Pregunta::join('competencias', 'competencias.com_id', 'preguntas.pre_com_id')
            ->join('esquemas', 'esquemas.esq_id', 'competencias.com_esq_id')
            ->where('pre_id',$request->input('editar'))->get();

        $respuesta = Respuesta::where('res_pre_id',$request->input('editar'))->get();

        $esquema = Esquema::all();
        $competencia = Competencia::all();

        #$editar = $request->input('editar');

        #dd($pregunta);

        return view('modificar',[
            'pregunta' => $pregunta,
            'respuesta' => $respuesta,
            'esquema' => $esquema,
            'competencia' => $competencia
        ]);
    }

    public function guardarModificacion(Request $request){

        $mensaje = [];

        //Guarda pregunta en base de datos
        $pregunta = Pregunta::where('pre_id',$request->input('id'))->update([

            'pre_content'   => $request->input('pregunta'),
            #'pre_id' => $request->input('id'),
            'pre_restrict' => $request->input('restrict'),
            'pre_com_id' => $request->input('competencia'),
        ]);

        if ($pregunta) {
            
            array_push($mensaje, 'Pregunta modificada correctamente');

            //Para convierte correcta a entero
            if ($request->input('correcta') == 'a') {
                $correctaA = 1;
            }else{
                $correctaA = 0;
            }

            if ($request->input('correcta') == 'b') {
                $correctaB = 1;
            } else {
                $correctaB = 0;
            }

            if ($request->input('correcta') == 'c') {
                $correctaC = 1;
            }else{
                $correctaC = 0;
            }

            if ($request->input('correcta') == 'd') {
                $correctaD = 1;
            }else{
                $correctaD = 0;
            }
            
            //Obtiene el ultimo id de pregunta añadido para agregarle como llave foranea a respuesta
            $res_pre_id = $request->input('id');

            //Guarda las respuestas
            $respuestaA = Respuesta::where('res_id',$request->input('res_idA'))->update([

                'res_content'   => $request->input('respuestaA'),
                'res_correct' => $correctaA,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaB = Respuesta::where('res_id',$request->input('res_idB'))->update([

                'res_content'   => $request->input('respuestaB'),
                'res_correct' => $correctaB,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaC = Respuesta::where('res_id',$request->input('res_idC'))->update([

                'res_content'   => $request->input('respuestaC'),
                'res_correct' => $correctaC,
                'res_pre_id' => $res_pre_id
            ]);

            $respuestaD = Respuesta::where('res_id',$request->input('res_idD'))->update([

                'res_content'   => $request->input('respuestaD'),
                'res_correct' => $correctaD,
                'res_pre_id' => $res_pre_id
            ]);

            // if ($respuestaA) {
            //     if ($correctaA) {
            //         array_push($mensaje, 'Respuesta A (correcta) modificada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta A modificada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se modifico la respuesta A');
            // }
            // if ($respuestaB) {
            //     if ($correctaB) {
            //         array_push($mensaje, 'Respuesta B (correcta) modificada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta B modificada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se modifico la respuesta B');
            // }
            // if ($respuestaC) {
            //     if ($correctaC) {
            //         array_push($mensaje, 'Respuesta C (correcta) modificada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta C modificada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se modifico la respuesta C');
            // }
            // if ($respuestaD) {
            //     if ($correctaD) {
            //         array_push($mensaje, 'Respuesta D (correcta) modificada correctamente');
            //     }else{
            //         array_push($mensaje, 'Respuesta D modificada correctamente');
            //     }
            // }else{
            //     array_push($mensaje, 'No se modifico la respuesta D');
            // }
        }else{
            array_push($mensaje, 'No se modificaron la pregunta ni las respuestas');
        }

        $competenciaH = $request->input('competenciaH');
        $esquemaH = $request->input('esquemaH');

        return redirect('/admin/show?esquema='.$esquemaH.'&competencia='.$competenciaH)->with('mensaje', $mensaje);
    }

    public function borrarPregunta(Request $request){

        $mensaje = [];

        $borrarRespuestas = Respuesta::where('res_pre_id',$request->input('borrar'))->delete();
        
        //dd($request->all());

        if ($borrarRespuestas) {
            $borrar = Pregunta::where('pre_id',$request->input('borrar'))->delete();
            
            array_push($mensaje, 'Respuestas eliminadas correctamente');

            if ($borrar) {
                array_push($mensaje, 'Pregunta eliminada correctamente');
            }else{
                array_push($mensaje, 'No se elimino la pregunta');
            }
        }else{
            array_push($mensaje, 'No se elimino la pregunta, ni las respuestas');
        }

        return back()->with('mensaje', $mensaje);
    }
}