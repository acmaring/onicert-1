<?php

namespace App\Http\Controllers;

use App\Respuesta;
use App\Pregunta;
use App\Competencia;
use App\Esquema;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function home(){
    	//Pagina de inicio
        $esquema = Esquema::all();
        
    	return view('welcome',[
            'esquema' => $esquema
        ]);
    }

    public function admin(){
    	
    	//Pagina Admin
    	$esquema = Esquema::all();
    	$competencia = Competencia::all();

    	/*foreach ($esquemas as $esquema) {
    		
    	}*/
    	#dd($esquema);

    	return view('admin',[
    		'esquema' => $esquema,
    		'competencia' => $competencia
    	]);
    }

    public function generarExamen(Request $request){

        $pregunta = [];
        $respuesta = [];
        $pre_id = [];
        $respuesta_total = [];
        $pregunta_total = [];
        $cantCompTotal = 0;
        $cantComp = [];

        $esquema = Esquema::where('esq_id',$request->input('esq'))->get();
        #dd($esquema);
        
        $competencia = Competencia::where('com_esq_id',$request->input('esq'))->get();

        $rand_restrict = rand(1, 2);

        foreach ($competencia as $com) {
            $cantCompTotal += Pregunta::where('pre_com_id', $com->com_id)->count('pre_id');
            array_push($cantComp, Pregunta::where('pre_com_id', $com->com_id)->count('pre_id'));
        }
        
        $int = 0;
        foreach ($cantComp as $cant) {
            $cantComp[$int++] = $cant * 100 / $cantCompTotal;
        }

        $int = 0;
        foreach ($cantComp as $cant) {
            $cantComp[$int++] = round($cant * $esquema[0]->esq_cant / 100);
        }

        // dd($competencia[1]->com_id);

        $int = 0;
        foreach ($competencia as $com) {
            #$pregunta = Pregunta::where('pre_com_id', $com->com_id)->inRandomOrder()->take($com->com_cant)->get();
            array_push($pregunta, Pregunta::where('pre_com_id', $com->com_id)->whereIn('pre_restrict',[0,$rand_restrict])->inRandomOrder()->take($cantComp[$int++])->get());
        }
        #dd($pregunta);
        // dd($pregunta[0]->get(0)->pre_id);
        // dd($pregunta[0][0]->pre_id);

        foreach ($pregunta as $pre) {
            foreach ($pre as $key) {
                array_push($pre_id, $key->pre_id);
            }
        }
        #dd($pre_id);
        foreach ($pre_id as $pre) {
            array_push($respuesta, Respuesta::where('res_pre_id', $pre)->get());
        }
        // dd($respuesta);
        
        foreach ($respuesta as $resCollection) {
            foreach ($resCollection as $res) {
                array_push($respuesta_total, $res);
            }
            
        }

        foreach ($pregunta as $preCollection) {
            foreach ($preCollection as $pre) {
                array_push($pregunta_total, $pre);
            }
            
        }

        #dd($pregunta_total);

        // $pregunta = Pregunta::where('pre_com_id', $competencia->com_id)->get();

        // $respuesta = Respuesta::where('res_pre_id', $pregunta->pre_id)->get();

        $count = 0;

        $wordDoc = new \PhpOffice\PhpWord\PhpWord();

        $seccion = $wordDoc->addSection();

        $header = $seccion->addHeader();
        // $header->firstPage();

        $fancyTableStyle = array('borderSize' => 6, 'borderColor' => '000000');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan = array('gridSpan' => 1, 'valign' => 'center');
        $cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $cellVCentered = array('valign' => 'center');

        $spanTableStyleName = 'Colspan Rowspan';
        $wordDoc->addTableStyle($spanTableStyleName, $fancyTableStyle);

        $table = $header->addTable($spanTableStyleName);
        $table->addRow();
        $cell1 = $table->addCell(2000, $cellRowSpan);
        $image = $cell1->addTextRun();
        $image->addImage(storage_path('onicert.png'),  array('width' => 148, 'height' => 40, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

        $cell2 = $table->addCell(4000, $cellRowSpan);
        $textrun2 = $cell2->addTextRun($cellHCentered);
        $textrun2->addText('Examen de conocimiento esquema '.$esquema[0]->esq_name);

        
        $table->addCell(2000, $cellVCentered)->addText('Versión: '.$esquema[0]->esq_version, null, $cellHCentered);

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(2000, $cellVCentered)->addText('Vigencia: '.$esquema[0]->esq_vigencia, null, $cellHCentered);

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(4000, $cellRowSpan)->addText('Certificación de competencias profesionales en RETIE', null, $cellHCentered);
        // $table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
        // $table->addCell(null, $cellRowContinue);
        $table->addCell(2000, $cellVCentered)->addText('Código: '.$esquema[0]->esq_code, null, $cellHCentered);

        // $paginas = $header->addPreserveText('Page {PAGE} of {NUMPAGES}');

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        // $table->addCell(2000, $cellHCentered)->addText('prueba');
        // $table->addCell(2000, $cellHCentered)->addText('paginas');
        $table->addCell(2000, $cellVCentered, $cellHCentered)->addPreserveText('Pagina {PAGE} de {NUMPAGES}');

        //Footer
        $footer = $seccion->addFooter();
        $footer->addImage(storage_path('footer.png'), array('width' => 50, 'height' => 40, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT));
        //dd($paginas);

        //seccion de preguntas y respuestas
        $seccion->addTextBreak();

        $fontStyleName = 'rStyle';
        $wordDoc->addFontStyle($fontStyleName, array('bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true));

        $paragraphStyleName = 'pStyle';
        $wordDoc->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));

        $wordDoc->addTitleStyle(5, array('bold' => true), array('spaceAfter' => 240, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

        //Nueva seccion
        //$seccion = $wordDoc->addSection();

        //Texto sin formato
        //Preguntas y respuestas
        $seccion->addTitle('PREGUNTAS DE SELECCIÓN MÚLTIPLE CON ÚNICA RESPUESTA', 5);
        $seccion->addTextBreak();
        foreach ($competencia as $com) {
            $seccion->addTitle($com->com_name, 5);
            foreach ($pregunta_total as $pre){
                $opciones = ['a. ','b. ','c. ','d. '];
                $countOpciones = 0;
                if ($com->com_id == $pre->pre_com_id) {
                    $seccion->addText(++$count.'. '.$pre->pre_content);
                    $seccion->addTextBreak();
                    foreach ($respuesta_total as $res) {
                        if ($res->res_pre_id == $pre->pre_id) {
                            $seccion->addText($opciones[$countOpciones++].$res->res_content, null, array('indentation' => array('left' => 1000, 'right' => 100)));
                        }
                    }
                }
                $seccion->addTextBreak();
            }
        }

        //Para generar documento a partir de plantilla

        $count = 0;

        $respuestaDoc = new \PhpOffice\PhpWord\PhpWord();

        //Para el encabezado

        $seccion = $respuestaDoc->addSection();

        $header = $seccion->addHeader();
        // $header->firstPage();

        $fancyTableStyle = array('borderSize' => 6, 'borderColor' => '000000');
        $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
        $cellRowContinue = array('vMerge' => 'continue');
        $cellColSpan = array('gridSpan' => 1, 'valign' => 'center');
        $cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
        $cellVCentered = array('valign' => 'center');

        $spanTableStyleName = 'Colspan Rowspan';
        $respuestaDoc->addTableStyle($spanTableStyleName, $fancyTableStyle);

        $table = $header->addTable($spanTableStyleName);
        $table->addRow();
        $cell1 = $table->addCell(2000, $cellRowSpan);
        $image = $cell1->addTextRun();
        $image->addImage(storage_path('onicert.png'),  array('width' => 148, 'height' => 40, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

        $cell2 = $table->addCell(4000, $cellRowSpan);
        $textrun2 = $cell2->addTextRun($cellHCentered);
        $textrun2->addText('Respuestas examen de conocimiento esquema '.$esquema[0]->esq_name);

        
        $table->addCell(2000, $cellVCentered)->addText('Versión: '.$esquema[0]->esq_version, null, $cellHCentered);

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        $table->addCell(2000, $cellVCentered)->addText('Vigencia: '.$esquema[0]->esq_vigencia, null, $cellHCentered);

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(4000, $cellRowSpan)->addText('Certificación de competencias profesionales en RETIE', null, $cellHCentered);
        // $table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
        // $table->addCell(null, $cellRowContinue);
        $table->addCell(2000, $cellVCentered)->addText('Código: '.$esquema[0]->esq_code, null, $cellHCentered);

        // $paginas = $header->addPreserveText('Page {PAGE} of {NUMPAGES}');

        $table->addRow();
        $table->addCell(null, $cellRowContinue);
        $table->addCell(null, $cellRowContinue);
        // $table->addCell(2000, $cellHCentered)->addText('prueba');
        // $table->addCell(2000, $cellHCentered)->addText('paginas');
        $table->addCell(2000, $cellVCentered, $cellHCentered)->addPreserveText('Pagina {PAGE} de {NUMPAGES}');

        //Footer
        $footer = $seccion->addFooter();
        $footer->addImage(storage_path('footer.png'), array('width' => 50, 'height' => 40, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT));

        //dd($paginas);

        $seccion->addTextBreak();

        //Para tabla de respuestas
        $fontStyleName = 'rStyle';
        $respuestaDoc->addFontStyle($fontStyleName, array('bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true));

        $paragraphStyleName = 'pStyle';
        $respuestaDoc->addParagraphStyle($paragraphStyleName, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));

        $respuestaDoc->addTitleStyle(5, array('bold' => true), array('spaceAfter' => 240, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

        //para tabla de nombre
        $table = $seccion->addTable(array('borderSize' => 6, 'borderColor' => '000000'));

        $table->addRow();
        $table->addCell(3000, array('gridSpan' => '2', 'valign' => 'center'))->addText('NOMBRES Y APELLIDOS');
        $table->addCell(7000, array('gridSpan' => '4', 'valign' => 'center'));

        $table->addRow();
        $table->addCell(1000)->addText('CEDULA');
        $table->addCell(1500);
        $table->addCell(1500)->addText('FECHA');
        $table->addCell(2500);
        $table->addCell(2000)->addText('CODIGO EXAMEN');
        $table->addCell(1500);

        
        $seccion->addTextBreak();
        
        //Imagen
        $seccion->addImage(storage_path('respuesta.png'), array('width' => 410, 'height' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

        //Tabla de respuestas
        $table = $seccion->addTable(array('borderSize' => 6, 'borderColor' => '000000'));

        $table->addRow(300);
        $table->addCell(350, array('vMerge' => 'restart', 'valign' => 'center','borderColor' => 'FFFFFF'));
        $table->addCell(350, array('gridSpan' => 4, 'valign' => 'center'));
        $table->addCell(350, array('vMerge' => 'restart', 'valign' => 'center', 'borderColor' => 'FFFFFF'));
        $table->addCell(350, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '999999', 'borderColor' => 'FFFFFF', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addText('Supervisor', array('size' => 8));
        $table->addCell(350, array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => '999999', 'borderColor' => 'FFFFFF', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR))->addText('revisión', array('size' => 8));

        $table->addRow(150);
        $table->addCell(null, array('vMerge' => 'continue'));
        $table->addCell()->addText('A');
        $table->addCell()->addText('B');
        $table->addCell()->addText('C');
        $table->addCell()->addText('D');
        $table->addCell(null, array('vMerge' => 'continue'));
        $table->addCell(null, array('vMerge' => 'continue'));
        $table->addCell(null, array('vMerge' => 'continue'));
        
        foreach ($esquema as $esq) {
            // $seccion->addTitle('Esquema: '.$esq->esq_name, 5);
        }
        foreach ($competencia as $com) {
            // $seccion->addTitle('Competencia: '.$com->com_name, 5);
            foreach ($pregunta_total as $pre){
                $opciones = ['a.png','b.png','c.png','d.png'];
                $countOpciones = 0;
                if ($com->com_id == $pre->pre_com_id) {
                    $table->addRow(300);
                    $table->addCell(350)->addText(++$count);
                    foreach ($respuesta_total as $res) {
                        if ($res->res_pre_id == $pre->pre_id) {
                            if ($res->res_correct == 1) {
                                $table->addCell(350, array('bgColor' => '000000'));
                                $countOpciones++;
                            }else{
                                $table->addCell(350)->addImage(storage_path($opciones[$countOpciones++]),  array('width' => 10, 'height' => 10, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                            }
                        }
                    }
                    $table->addCell(350);
                    $table->addCell(350, array('bgColor' => '999999'));
                    $table->addCell(350, array('bgColor' => '999999'));
                }
            }
        }
    
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($wordDoc, 'Word2007');
        $objWriterRespuesta = \PhpOffice\PhpWord\IOFactory::createWriter($respuestaDoc, 'Word2007');

        try {
            $objWriter->save(storage_path($esquema[0]->esq_code.'-'.str_slug($esquema[0]->esq_name).'.docx'));
            $objWriterRespuesta->save(storage_path('HR-'.$esquema[0]->esq_code.'-'.str_slug($esquema[0]->esq_name).'.docx'));
            $pregunta_doc = $esquema[0]->esq_code.'-'.str_slug($esquema[0]->esq_name).'.docx';
            $respuesta_doc = 'HR-'.$esquema[0]->esq_code.'-'.str_slug($esquema[0]->esq_name).'.docx';
        }catch (Exception $e){

        }

        return view('generar',[ 
            'esquema' => $esquema,
            'competencia' => $competencia,
            'pregunta' => $pregunta_total,
            'respuesta' => $respuesta_total,
            'restriccion' => $rand_restrict,
            'pregunta_doc' => $pregunta_doc,
            'respuesta_doc' => $respuesta_doc
        ]);
    }

    public function esqComp(Request $request){

        $competencia = Competencia::where('com_esq_id',$request->input('esquema'))->get();

        return view('validar', compact('competencia'));
    }

    public function generarWordPregunta(Request $request){
        return  response()->download(storage_path($request->input('pre_doc')));
    }

    public function generarWordRespuesta(Request $request){
        return  response()->download(storage_path($request->input('res_doc')));
    }

}
