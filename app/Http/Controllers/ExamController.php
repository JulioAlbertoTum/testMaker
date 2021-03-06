<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Group;
use App\Exam;
use App\Question;
use Session;
use Redirect;
use Validator;
use DateTime;
use DateInterval;
class ExamController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($group)
	{
		$name_course = "";
		$institute = "";
		if($group){
			// $group = intval(Session::get('group'));
			// dd($group);
			$name_course =  Group::join('courses','groups.course_id','=','courses.id')
								->where('groups.id','=',$group)->first()->name;
		    $institution =  Group::join('courses','groups.course_id','=','courses.id')
		    					->join('careers','courses.career_id','=','careers.id')
		    					->where('groups.id','=',$group)->first()->name;
		    // Session::forget('group');
		}
		$range = $this->getRangeTime("00:00:00");
		// dd($range);
		return view('exams.create',compact('group','name_course','institution','range'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, $group)
	{
		// $data = date_create($request->get("time_start"));
		// dd(date_format($data, 'H:i:s'));
		
		
		// dd($date_start, $date_end, $date_start->format('Y-m-d H:i:s'), $date_end->format('Y-m-d H:i:s'),$date_start->diff($date_end)->format("%H:%I:%S"));
		if($request->get('types') == 'presential'){
			$rules = array(
			'course' => 'required|string',
			'institution' => 'required|string',
			'title' => 'string',
			'types' => 'required|in:online,presential',
			'description' => 'string',
			);
		

		}else if($request->get('types') == 'online'){
			$rules = array(
			'course' => 'required|string',
			'institution' => 'required|string',
			'title' => 'string',
			'types' => 'required|in:online,presential',
			'description' => 'string',
			'date_exam' =>'required|date_format:Y-m-d|after:tomorrow',
			'time_start' => 'required|date_format:"H:i"|before:duration',
			'duration' => 'required|date_format:"H:i"|after:time_start',
			'total' => 'required|min:1|max:100',
			'intents' => 'required|min:1|max:10',
			);

		}
		
		$this->validate($request, $rules);
		
		
		$exam = new Exam;
		$exam->group_id = $group;	
		$exam->name_course = $request['course'];
		$exam->institution = $request['institution'];
		$exam->title = $request['title'];
		$exam->types = $request['types'];
		$exam->description = trim($request['description']);

		if($request->get('types') == 'online'){
			$date_exam = $request->get('date_exam');
			$start = $request->get('time_start');
			$end = $request->get('duration');
			$date_start = new DateTime($date_exam." ".$start);
			$date_end = new DateTime($date_exam." ".$end);
			$exam->time_start = $date_start->format('Y-m-d H:i:s');
			$exam->duration = $date_end->format('Y-m-d H:i:s');
			$exam->intents = $request->get('intents');
		}else{
			$exam->time_start = "";
			$exam->duration ="";
		}
			
		$exam->total = $request['total'];
		$exam->save();
		Session::flash('flash_message',"El examen se ha creado de forma exitosa");
		
		return redirect('teacher/group/'.$group.'/exams');

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($group, $id)
	{
		$exam = Exam::find($id);
		$state = "show";
		return view('exams.show',compact('exam','group','state'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($group,$id)
	{
		$exam = Exam::find($id);
		// $range = $this->getRangeTime($exam->duration);
		// $group = Group::find($group_id);
		// dd($exam);
		return view('exams.edit',compact('group','exam'));
	}

	public function getRangeTime($duration){
		$range = array();
		$inter = new DateInterval('PT30M');
		$fecha = new DateTime('2016-05-01 00:00:00');
		for($i=0; $i<10; $i++){
			$row = array();
			$fecha = $fecha->add($inter); 
			$row['time'] = $fecha->format('H:i:s');

			if($duration == $fecha->format('H:i:s')){
				$row['selected'] = 1; 
			}else{
				$row['selected'] = 0;
			}
			$range[] = $row;
		}

		return $range;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $group, $id)
	{
		$exam = Exam::find($id);
		if($request->get('types') == 'presential'){
			$rules = array(
			'course' => 'required|string',
			'institution' => 'required|string',
			'title' => 'string',
			'types' => 'required|in:online,presential',
			'description' => 'string',
			);	

		}else if($request->get('types') == 'online'){
			$rules = array(
			'course' => 'required|string',
			'institution' => 'required|string',
			'title' => 'string',
			'types' => 'required|in:online,presential',
			'description' => 'string',
			'date_exam' =>'required|date_format:Y-m-d|after:tomorrow',
			'time_start' => 'required|date_format:"H:i"|before:duration',
			'duration' => 'required|date_format:"H:i"|after:time_start',
			'total' => 'required|min:1|max:100',
			'intents' => 'required|min:1|max:10',
			);
		}
		
		$this->validate($request, $rules);

		$exam->group_id = $group;	
		$exam->name_course = $request['course'];
		$exam->institution = $request['institution'];
		$exam->title = $request['title'];
		$exam->types = $request['types'];
		$exam->description = trim($request['description']);

		if($request->get('types') == 'online'){
			$date_exam = $request->get('date_exam');
			$start = $request->get('time_start');
			$end = $request->get('duration');
			$date_start = new DateTime($date_exam." ".$start);
			$date_end = new DateTime($date_exam." ".$end);
			$exam->time_start = $date_start->format('Y-m-d H:i:s');
			$exam->duration = $date_end->format('Y-m-d H:i:s');
			$exam->intents = $request->get('intents');
		}else{
			$exam->time_start = "";
			$exam->duration ="";
		}
			
		$exam->total = $request['total'];
		$exam->save();

		Session::flash('flash_message',"Los datos se han actualizado exitosamente");
		
		return redirect('teacher/group/'.$group.'/exams');

	}

	public function delete($group_id, $id){
		$group = Group::find($group_id);
		$exam = Exam::find($id);

		return view('exams.eliminate', compact('group','exam'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($group, $id)
	{
		$exam = Exam::find($id);
		$exam->questions()->detach();
		$exam->delete();
		Session::flash('flash_message',"Se ha eliminado el examen");
		
		return redirect('teacher/group/'.$group.'/exams');

	}


	public function asign($group,$id){

		
		$exam = Exam::find($id);
		if($exam->types == 'online'){
			$all = Question::where('group_id','=',intval($group))->get();
			$questions = $all->filter(function($question){
				return !$question->isDevelop();
			}); 
		
		}else{
			$questions = Question::where('group_id','=',intval($group))->get();	
		}
		$questionsExam = Question::join('exam_question','questions.id','=','exam_question.question_id')
								->where('exam_question.exam_id','=',$id)->orderBy('exam_question.order','asc')->get();
		// dd($questionsExam);
		if(!$exam->isValid())
			Session::flash('warnings','El puntaje de las preguntas del examen deben sumar 100');

		return view('exams.asign',compact('group','id','questions','exam','questionsExam'));
	}

	public function populate(Request $request, $group, $id){

		$questions_id = $request->id_q;
		$remove_ids = $request->id_rm;
 		$exam = Exam::find($id);
 		// dd($exam->isValid());

 		// delete questions selected
 		if($remove_ids != null){
			foreach ($remove_ids as $question) {
				if($exam->questions->contains($question)){
					$exam->questions()->detach($question);
				}
			}
		}
 		
 		// dd($questions_id);
		if($questions_id != null){
			$questions = $this->getIdAndPercent($questions_id);			
				foreach($questions as $question){
					if(!$exam->questions->contains( intval( $question[0] )) ){
						// dd($question);
						$exam->questions()->attach($question[0], array('percent'=> $question[1], 'order' => $question[2]));
					}else{
						// $q = Question::find($question[0]);

						$q = $exam->questions->where('id',intval( $question[0] ))->first();
					    // dd($q);
						// dd($q->pivot->percent);
						$q->pivot->percent = $question[1];
						$q->pivot->order = $question[2];
						$q->pivot->save();
					}
					
				}

				if($exam->isValid()){
					Session::flash('flash_message',"Los datos se han guardado correctamente");
					// return Redirect::back();
				}else{
					// $notifications = array();
					// $notifications[] = ;
					Session::flash('warnings','El puntaje de las preguntas del examen deben sumar 100');
					// return Redirect::back();
				}			
		}
		return Redirect::back();
		
	}

	// public function validatePercentScore($arr){
	// 	$percents = array_column($arr, 1); 
	// 	$sum = array_sum($percents);
	// 	// dd($sum);
	// 	$rules = array('sum_percent' => 'required|integer|min:0|max:100');

	// 	$validator = Validator::make( ['sum_percent' => $sum], $rules);
	// 	$notifications =[];

	// 	if($validator->fails()){
	// 		$messages = $validator->errors();
	// 		foreach($messages->all() as $message){
	// 			$notifications[] = $message;
	// 		}
	// 	}else{

	// 		if($sum < 100){
 // 				$notifications[] = "La suma de porcentajes de calificacion no es igual a 100";
	// 		}
			
	// 	}

	// 	return $notifications;		
	// }


	public function getIdAndPercent($arr){
		$questions = array();
		$i = 1;
		foreach ($arr as $q ){
			$values = explode('_',$q);
			$values[] = $i;
			$questions[] = $values ;
			$i++;
		}
		return $questions;
	}

	public function terminate($group_id, $id){
		$group = Group::find($group_id);
		$exam = Exam::find($id);
		return view('exams.terminate',compact('group','exam'));
	}

	public function terminateEdition($group,$id){
		$exam = Exam::find($id);
		$notifications = [];
		if($exam->stateEdition() && $exam->isValid()){
			$exam->state = 'terminate';
			$exam->save();
			Session::flash('flash_message',"Los datos se han guardado correctamente");
			return redirect('teacher/group/'.$group.'/exams');;
		}else{
			$notifications[] = "El examen no esta en estado de edicion o el puntaje del mismo no es 100";
			return Redirect::back()->withErrors($notifications);
		}
		
	}




}
