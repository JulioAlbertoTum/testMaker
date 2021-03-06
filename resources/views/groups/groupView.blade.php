@extends('layouts.master')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			@include('common.messages')
			@include('common.error')
			<div class="panel panel-default">

				<div class="panel-heading" >
					<div class="row">
						<div class="col-md-2">
							Inscribir Estudiante
						</div>
						<div class="col-md-4">
							<div class="row">
								<a href="{{ url('admin/group/excel') }}" class="btn btn-success" data-toggle="tooltip" title="Crear a partir de excel">
									<i class="fa fa-table" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="panel-body">		
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
							<form class="" action="{{ url('admin/group/'.$group->id.'/registerstudent') }}" method="POST">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row">
                	<div class="col-md-12">
                		<div class="form-group">
											<label for="student">Cod Sis Estudiante</label>
                  		<select multiple class="form-control" name="student">
                    		@foreach($students_all as $student)
                      			<option value="{{$student->id}}">{{$student->cod_sis}}</option>
                    		@endforeach
                  		</select>
                		</div>
                	</div>
                </div>

                <div class="row">
                  <div class="col-md-2 col-md-offset-5">
                  	<div class="form-group">
                    	<button class="btn btn-primary" type="submit" name="button">Inscribir</button>
                  	</div>
                  </div>
                </div>

              </form>
            </div>
          </div>
				</div>

			</div>

			<div class="panel panel-default">

				<div class="panel-heading" >
					
					<div class="row">
						<div class="col-md-11">
							Lista de Estudiantes del grupo <strong>{{$group->nro}}</strong> de la materia de <strong>{{ $group->course->name }}.</strong>
						</div>
						<div class="col-md-1">
							<div class="row">
								<button class="btn btn-success" formaction="{{ url('admin/user/create/email') }}" form="form-email" data-toggle="tooltip" title="Enviar Correo.">
									<i class="fa fa-envelope-o" aria-hidden="true"></i>
								</button>
								<button class="btn btn-success" data-toggle="tooltip" title="Seleccionar Todo" id="select-all">
									<i class="fa fa-globe" aria-hidden="true"></i>
								</button>				
							</div>
						</div>
					</div>
				
				</div>

				<div class="panel-body">		
         	<table class="table table-bordered">
         		<thead>
							<th class="text-center col-md-1">Select</th>
							<th class="text-center col-md-2">Codigo Sis</th>
              <th class="text-center col-md-1">Foto</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Email</th>
              <th class="text-center col-md-1">Tipo</th>
            </thead>
            <tbody>
            	<form action="" method="post" id="form-email">
            		<input type="hidden" name="_token" value="{{ csrf_token() }}">
            		@foreach($students as $student)

            			@if($student->user_id == 0)
										<tr>
											<td class="text-center">
												
											</td>
											<td>
                    		{{$student->cod_sis}}
                  		</td>
                  		<td colspan="4" class="text-center danger">Estudiante sin Cuenta de Usuario</td>
										</tr>
            			@else


									<tr>
										<td class="text-center">
											<input type="checkbox"  name="id[]" value="{{ $student->user_id }}">
										</td>
										<td>
                    	{{$student->cod_sis}}
                  	</td>
										<td class="text-center">
                      <img src="{{URL::asset( $student->user->userPhoto() )}}" alt="default" class="img-circle" width="30px" height="30px">
                  	</td>
                  	<td>
                    	{{$student->user->name}}
                  	</td>
                  	<td>
                    	{{$student->user->email}}
                  	</td>
                  	<td>
                    	@if($student->user->isStudent())
                      	Estudiante
                    	@endif
                  	</td>	
									</tr>
									@endif
            		@endforeach
            	</form>
            </tbody>
         	</table>

				</div>

			</div>

		</div>
	</div> 
</div>
@endsection

@section('scripts')
	<script>
		$('#select-all').click(function(){
			$users = $("input[type='checkbox']");
			$users.each(function($index,element){
				$(this).prop('checked',true);
			});
			// console.log($users);
			// alert($users.length);
		});
	</script>
@endsection
