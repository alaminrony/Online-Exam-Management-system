<div class="portlet light bordered">
    <div class="portlet-body ">
        <div class="row">
            <div class="col-md-12 col-md-offset-3 text-center">
                <div class="col-md-6 header-question">
                   
                    <h4>
                    {{__('label.SUBJECT').': '.$mockTestInfo->subject_title}}
                    </h4>
                    <h4>
                    {{__('label.EXAM').': '.$mockTestInfo->epe_title}}
                    </h4>
                </div>    
            </div>
        </div>
		 <?php $i = 1; ?>
        <div class="mt-element-step">
            @if(!$questionArr->isEmpty())
				@foreach($questionArr as $question)
            <div class="row step-no-background-thin">
                <div class="mt-step-desc">
					@if($question->type_id  != '6')
                    <div class="font-grey-cascade"><h4>{{$i.'.  '.$question->question}}</h4></div>
					@endif
                </div>
				@if($question->type_id  == '1')
					<?php
						$objRightAns = 'font-green-jungle';
						$objFalseAns = 'font-red';
					
					?>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">a</div>
					<?php 
						$answer1 = '';
						if($question->correct_answer == 1){
							$answer1 = $objRightAns;
						}else if($question->submitted_answer == 1 && $question->correct == 0){
							$answer1 = $objFalseAns;
						}
					?>
                    <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer1; ?>">{{$question->opt_1}}</span></div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">b</div>
					<?php 
						$answer2 = '';
						if($question->correct_answer == 2){
							$answer2 = $objRightAns;
						}else if($question->submitted_answer == 2 && $question->correct == 0){
							$answer2 = $objFalseAns;
						}
					?>
                    <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer2; ?>">{{$question->opt_2}}</span></div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">c</div>
					<?php 
						$answer3 = '';
						if($question->correct_answer == 3){
							$answer3 = $objRightAns;
						}else if($question->submitted_answer == 3 && $question->correct == 0){
							$answer3 = $objFalseAns;
						}
					?>
                    <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer3; ?>">{{$question->opt_3}}</span></div>
                </div>
                <div class="col-md-6 mt-step-col">
                    <div class="mt-step-number font-grey-cascade">d</div>
					<?php 
						$answer4 = '';
						if($question->correct_answer == 4){
							$answer4 = $objRightAns;
						}else if($question->submitted_answer == 4 && $question->correct == 0){
							$answer4 = $objFalseAns;
						}
					?>
                    <div class="mt-step-content font-grey-cascade"><span class="<?php echo $answer4; ?>">{{$question->opt_4}}</span></div>
                </div>
				@endif
				
				@if($question->type_id  == '3')
				<?php
					$class = '';
					if($question->correct == 1){
						$class = 'font-green-jungle';
					}else{
						$class = 'font-red font-lg';
					}
				?>
					<div class="mt-step-desc">
                    <div class="font-grey-cascade"><strong>Answer : </strong><span class="<?php echo $class; ?>"><span title="{{$question->submitted_answer}}" class="tooltips">{{$question->correct_answer}}</span></span></div>
					</div>
				@endif	
				
				@if($question->type_id  == '5')
				<?php
					$trueFalseclass = '';
					if($question->correct == 1){
						$trueFalseclass = 'font-green-jungle';
					}else{
						$trueFalseclass = 'font-red font-lg';
					}
				?>
					<div class="mt-step-desc">
                    <div class="font-grey-cascade"><strong>Answer : </strong><span class="<?php echo $trueFalseclass; ?>">{{(empty($question->correct_answer)) ?'False':'True'}}</span></div>
					</div>
				@endif
				
            </div>
        
            <?php $i++; ?>
            @endforeach
					@if(!empty($matchAnswer))
					 <table class="table table-striped table-bordered table-hover margin-top-20">
						<thead>
							<tr>
								<th class="text-center"><strong>{{__('label.COLUMN_A')}}</strong></th>
								<th class="text-center"><strong>{{__('label.COLUMN_B')}}</strong></th>
							   
							</tr>
						</thead>
						<tbody>
							@foreach($matchAnswer as $match)
									<?php
										$machingclass = '';
										$title = '';
										if($match['correct'] == 1){
											$machingclass = 'font-green-jungle';
										}else{
											$machingclass = 'font-red font-ls';
											$title = (!empty($matchAnswer[$match['submitted_answer']]['match_answer'])) ? $matchAnswer[$match['submitted_answer']]['match_answer'] : '';
										}
									?>
									<tr>
										<td>{{$match['question']}}</td>
										<td><span title="<?php echo $title; ?>" class="<?php echo $machingclass; ?> tooltips">{{$match['match_answer']}}</span></td>
									</tr>
							@endforeach
						</tbody>
                    </table>
				@endif
            @endif

          
        </div>
    </div>
</div>















<style>
    .mt-element-step .step-no-background-thin .mt-step-content {
        padding-left: 60px;
        margin-top: 5px;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #e5e5e5;
    }
    .mt-element-step .step-no-background-thin .mt-step-number {
        font-size: 20px;
        border-radius: 50%!important;
        float: left;
        margin: auto;
        padding: 0px 8px;
        border: 1px solid #000000;
    }
    .font-grey-cascade {
        color: #000000!important;
    }
   
</style>