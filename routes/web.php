<?php

Route::get('/', function() {
    return \Redirect::to('home');
});

Route::get('home', 'HomeController@index');
Route::get('about_us', 'HomeController@aboutUs');
Route::get('history', 'HomeController@history');
Route::get('photo_gallery', 'HomeController@gallery');
Route::get('ejournal', 'HomeController@ejournal');


Auth::routes();


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    Route::post('dashboard/getTodaysExamDetails', 'Admin\DashboardController@getTodaysExamDetails');


    Route::post('setRecordPerPage', 'UserController@setRecordPerPage');
    Route::get('changePassword/{id}/{param?}', 'UserController@changePassword')->name('user.cp');
    Route::post('changePassword', 'UserController@updatePassword')->name('user.updatePass');
    Route::get('forceChangePassword', 'UserController@forceChangePassword')->name('forceChangePassword');
    Route::post('updateForcePassword/{id}', 'UserController@updateForcePassword')->name('updateForcePassword');

    //Grading System
    Route::get('gradingSystem/', 'GradingSystemController@index');

    //Exam result report
    Route::get('examResultReport', 'ExamResultReportController@index');
    Route::post('examResultReport/generate', 'ExamResultReportController@generate');
    Route::post('examResultReport/getEmployee', 'ExamResultReportController@getEmployee');

    //Mock Test Result
    Route::get('mockTestReport', 'MockTestReportController@index');
    Route::post('mockTestReport/generate', 'MockTestReportController@generate');
    Route::post('mockTestReport/getEmployee', 'MockTestReportController@getEmployee');

    //Employee wise result report
    Route::get('employeeWiseResult', 'EmployeeWiseResultController@index');
    Route::post('employeeWiseResult/generate', 'EmployeeWiseResultController@generate');
    Route::post('employeeWiseResult/getExam', 'EmployeeWiseResultController@getExam');
    Route::post('employeeWiseResult/getEmployee', 'EmployeeWiseResultController@getEmployee');
    Route::post('employeeWiseResult/getSubject','EmployeeWiseResultController@getSubject');
});

Route::group(['middleware' => ['auth', 'groupExaminer']], function () {
    Route::get('epedsmarking/questionanswersheet', 'EpeDsMarkingController@questionanswersheet');
    Route::get('epedsmarking', 'EpeDsMarkingController@index');
    Route::get('epedsmarking/show_part_list', 'EpeDsMarkingController@relatePartList');
    Route::post('epedsmarking/show_subject', 'EpeDsMarkingController@showSubject');
    Route::post('epedsmarking/show_submitted_epe', 'EpeDsMarkingController@showSubmittedEpe');
    Route::post('epedsmarking/unlockRequest', 'EpeDsMarkingController@getUnlockRequest');
    Route::post('epedsmarking/unlockRequestSave', 'EpeDsMarkingController@UnlockRequestSave');

    Route::get('subjectiveMarking/{id}', 'EpeDsMarkingController@subjectiveMarking');
    Route::post('saveSubjectiveMarking/{id}', 'EpeDsMarkingController@saveSubjectiveMarking');
    Route::post('submitSubjectiveMarking', 'EpeDsMarkingController@submitSubjectiveMarking');
});

Route::group(['middleware' => ['auth', 'groupEmployee']], function () {

    Route::get('disclaimer', 'EpeExamController@disclaimer');
    Route::post('disclaimer', 'EpeExamController@setDisclaimer');

    //ISSP Student Activity
    Route::get('isspstudentactivity/mymocklist', 'IsspStudentActivityController@myMockList')->name('epe.myMockList');
    Route::get('isspstudentactivity/mockplay', 'IsspStudentActivityController@mockPlay')->name('epe.mockPlay');
    Route::get('isspstudentactivity/myepe', 'IsspStudentActivityController@myEpe')->name('epe.myEpe');
    Route::get('isspstudentactivity/mymocktest', 'IsspStudentActivityController@myMockTest')->name('epe.myMockTest');
    Route::get('isspstudentactivity/epeexam', 'IsspStudentActivityController@myEpeExam')->name('epe.myEpeExam');

    Route::get('mockExam', 'MockExamController@exam');
    Route::post('mockExam', 'MockExamController@submitExam');
    Route::get('mockExam/examresult', 'MockExamController@submitExamResult');
    //EPE Exam 
    Route::get('epeExam', 'EpeExamController@exam');
    Route::post('objectiveTempSave', 'EpeExamController@objectiveTempSave');
    Route::get('examSubjective', 'EpeExamController@examSubjective');
    Route::post('epeExam', 'EpeExamController@submitExam');
    Route::post('examSubjective', 'EpeExamController@submitSubjective');
    Route::get('subjectiveComplete', 'EpeExamController@subjectiveComplete');
    Route::post('saveSingleSubjectiveQuestion', 'EpeExamController@saveSingleSubjective');
    Route::post('saveLockSingleSubjectiveQuestion', 'EpeExamController@saveLockSingleSubjective');
    Route::post('epeExam/deleteQuestionSetAnswers', 'EpeExamController@deleteSubjectiveQuestionSetAnswers');
    Route::post('epeExam/deleteSubjectiveIndividualAnswer', 'EpeExamController@deleteSubjectiveIndividualAnswer');
    Route::get('epeExam/viewFile', 'EpeExamController@viewFile');
});

Route::group(['middleware' => ['auth', 'groupABD']], function () {
    //Region status report
    Route::get('regionStatusReport', 'RegionStatusReportController@index');
    Route::post('regionStatusReport/generate', 'RegionStatusReportController@generate');

    //Cluster result report
    Route::get('clusterResult', 'ClusterResultReportController@index');
    Route::post('clusterResult/generate', 'ClusterResultReportController@generate');

    //Branch result report
    Route::get('branchResult', 'BranchResultController@index');
    Route::post ('branchResult/generate', 'BranchResultController@generate');

    //Department status result report
    Route::get('departmentStatusReport', 'DepartmentStatusReportController@index');
    Route::post('departmentStatusReport/generate', 'DepartmentStatusReportController@generate');

    //Stafftrend Analysis result report
    Route::get('staffTrendAnalysis', 'StaffTrendAnalysisController@index');
    Route::post('staffTrendAnalysis/generate', 'StaffTrendAnalysisController@generate');

    //Participation status result report
    Route::get('participationStatus', 'ParticipationStatusController@index');
    Route::post('participationStatus/generate', 'ParticipationStatusController@generate');
    Route::post('participationStatus/getEmployeeDetails', 'ParticipationStatusController@getEmployeeDetails');

    //Log History login logout report
    Route::get('loginReport', 'LoginReportController@index');
    Route::post('loginReport/generate', 'LoginReportController@generate');

    Route::get('userLogReport', 'UserLogReportController@index');
    Route::post('userLogReport/generate', 'UserLogReportController@generate');

    Route::get('changePasswordLog', 'ChangePasswordLogReportController@index');
    Route::post('changePasswordLog/generate', 'ChangePasswordLogReportController@generate');

    Route::get('questionLogReport', 'QuestionLogReportController@index');
    Route::post('questionLogReport/generate', 'QuestionLogReportController@generate');

    Route::get('branchLogReport', 'BranchLogReportController@index');
    Route::post('branchLogReport/generate', 'BranchLogReportController@generate');
});


Route::group(['middleware' => ['auth', 'groupAdmin']], function () {

    //user group
    Route::get('userGroup', 'UserGroupController@index')->name('userGroup.index');
    Route::get('userGroup/{id}/edit', 'UserGroupController@edit')->name('userGroup.edit');
    Route::patch('userGroup/{id}', 'UserGroupController@update')->name('userGroup.update');

    //user
    Route::post('user/filter/', 'UserController@filter');
    Route::get('user', 'UserController@index')->name('user.index');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::patch('user/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/{id}', 'UserController@destroy')->name('user.destroy');
    Route::get('user/activeUser/{id}/{param?}', 'UserController@active')->name('user.active');
    Route::post('user/details', 'UserController@details')->name('user.details');
    Route::post('user/getData', 'UserController@getData')->name('user.getData');
    Route::post('user/getBranchData', 'UserController@getBranchData')->name('user.getBranchData');

    //Photo Gallery
    Route::get('gallery', 'GalleryController@index')->name('gallery.index');
    Route::get('gallery/create', 'GalleryController@create')->name('gallery.create');
    Route::post('gallery', 'GalleryController@store')->name('gallery.store');
    Route::get('gallery/{id}/edit', 'GalleryController@edit')->name('gallery.edit');
    Route::patch('gallery/{id}', 'GalleryController@update')->name('gallery.update');
    Route::delete('gallery/{id}', 'GalleryController@destroy')->name('gallery.destroy');

    //department
    Route::post('department/filter/', 'DepartmentController@filter');
    Route::get('department', 'DepartmentController@index')->name('department.index');
    Route::get('department/create', 'DepartmentController@create')->name('department.create');
    Route::post('department', 'DepartmentController@store')->name('department.store');
    Route::get('department/{id}/edit', 'DepartmentController@edit')->name('department.edit');
    Route::patch('department/{id}', 'DepartmentController@update')->name('department.update');
    Route::delete('department/{id}', 'DepartmentController@destroy')->name('department.destroy');

    //Rank
    Route::get('rank', 'RankController@index')->name('rank.index');
    Route::get('rank/create', 'RankController@create')->name('rank.create');
    Route::post('rank', 'RankController@store')->name('rank.store');
    Route::get('rank/{id}/edit', 'RankController@edit')->name('rank.edit');
    Route::patch('rank/{id}', 'RankController@update')->name('rank.update');
    Route::delete('rank/{id}', 'RankController@destroy')->name('rank.destroy');

    //Designation
    Route::get('designation', 'DesignationController@index')->name('designation.index');
    Route::get('designation/create', 'DesignationController@create')->name('designation.create');
    Route::post('designation', 'DesignationController@store')->name('designation.store');
    Route::get('designation/{id}/edit', 'DesignationController@edit')->name('designation.edit');
    Route::patch('designation/{id}', 'DesignationController@update')->name('designation.update');
    Route::delete('designation/{id}', 'DesignationController@destroy')->name('designation.destroy');

    //region
    Route::post('region/filter/', 'RegionController@filter');
    Route::get('region', 'RegionController@index')->name('region.index');
    Route::get('region/create', 'RegionController@create')->name('region.create');
    Route::post('region', 'RegionController@store')->name('region.store');
    Route::get('region/{id}/edit', 'RegionController@edit')->name('region.edit');
    Route::patch('region/{id}', 'RegionController@update')->name('region.update');
    Route::delete('region/{id}', 'RegionController@destroy')->name('region.destroy');

    //cluster
    Route::post('cluster/filter/', 'ClusterController@filter');
    Route::get('cluster', 'ClusterController@index')->name('cluster.index');
    Route::get('cluster/create', 'ClusterController@create')->name('cluster.create');
    Route::post('cluster', 'ClusterController@store')->name('cluster.store');
    Route::get('cluster/{id}/edit', 'ClusterController@edit')->name('cluster.edit');
    Route::patch('cluster/{id}', 'ClusterController@update')->name('cluster.update');
    Route::delete('cluster/{id}', 'ClusterController@destroy')->name('cluster.destroy');

    //Branch
    Route::post('branch/filter/', 'BranchController@filter');
    Route::get('branch', 'BranchController@index')->name('branch.index');
    Route::get('branch/create', 'BranchController@create')->name('branch.create');
    Route::post('branch', 'BranchController@store')->name('branch.store');
    Route::get('branch/{id}/edit', 'BranchController@edit')->name('branch.edit');
    Route::patch('branch/{id}', 'BranchController@update')->name('branch.update');
    Route::post('branch/getCluster', 'BranchController@getCluster');
    Route::delete('branch/{id}', 'BranchController@destroy')->name('branch.destroy');

    //Subject
    Route::post('subject/filter/', 'SubjectController@filter');
    Route::get('subject', 'SubjectController@index')->name('subject.index');
    Route::get('subject/create', 'SubjectController@create')->name('subject.create');
    Route::post('subject', 'SubjectController@store')->name('subject.store');
    Route::get('subject/{id}/edit', 'SubjectController@edit')->name('subject.edit');
    Route::patch('subject/{id}', 'SubjectController@update')->name('subject.update');
    Route::delete('subject/{id}', 'SubjectController@destroy')->name('subject.destroy');

    //Configuration
    Route::get('configuration', 'ConfigurationController@index')->name('configuration.index');
    Route::get('configuration/{id}/edit', 'ConfigurationController@edit')->name('configuration.edit');
    Route::patch('configuration/{id}', 'ConfigurationController@update')->name('configuration.update');

    //Scrollmessage
    Route::get('scrollmessage', 'ScrollMessageController@index')->name('scrollmessage.index');
    Route::get('scrollmessage/create', 'ScrollMessageController@create')->name('scrollmessage.create');
    Route::post('scrollmessage', 'ScrollMessageController@store')->name('scrollmessage.store');
    Route::get('scrollmessage/{id}/edit', 'ScrollMessageController@edit')->name('scrollmessage.edit');
    Route::patch('scrollmessage/{id}', 'ScrollMessageController@update')->name('scrollmessage.update');
    Route::delete('scrollmessage/{id}', 'ScrollMessageController@destroy')->name('scrollmessage.destroy');

    //Password Setup
    Route::get('passwordSetup', 'PasswordSetupController@index')->name('passwordSetup.index');
    Route::get('passwordSetup/{id}/edit', 'PasswordSetupController@edit')->name('passwordSetup.edit');
    Route::patch('passwordSetup/{id}', 'PasswordSetupController@update')->name('passwordSetup.update');

    //Subject to DS
    Route::get('subjecttods', 'SubjectToDsController@index');
    Route::get('subjecttods/relateSubject', 'SubjectToDsController@relateSubject');
    Route::post('subjecttods/relatedData', 'SubjectToDsController@relatedData');


    //Question
    Route::post('question/filter/', 'QuestionController@filter');
    Route::get('question', 'QuestionController@index')->name('question.index');
    Route::get('question/create', 'QuestionController@create')->name('question.create');
    Route::post('question', 'QuestionController@store')->name('question.store');
    Route::get('question/{id}/edit', 'QuestionController@edit')->name('question.edit');
    Route::patch('question/{id}', 'QuestionController@update')->name('question.update');
    Route::delete('question/{id}', 'QuestionController@destroy')->name('question.destroy');

    //Mock Test
    Route::get('mock_test', 'MockTestController@index');
    Route::get('mock_test/create', 'MockTestController@create');
    Route::get('mock_test/create', 'MockTestController@create');
    Route::get('mock_test/{id}/edit', 'MockTestController@edit')->name('mock_test.edit');
    Route::patch('mock_test/{id}', 'MockTestController@update')->name('mock_test.update');
    Route::delete('mock_test/{id}', 'MockTestController@destroy')->name('mock_test.destroy');
    Route::get('mock_test/show_part_list', 'MockTestController@relatePartList');
    Route::post('mock_test/show_subject', 'MockTestController@showSubject');
    Route::post('mock_test/manage', 'MockTestController@storeMockTest');
    Route::post('mock_test/filter/', 'MockTestController@filter');
    Route::post('mock_test/show_mock_test_info/', 'MockTestController@showMockTestInfo');
    Route::get('mock_test/questionset/{id}', 'MockTestController@questionSet');
    Route::post('mock_test/updated_question_set', 'MockTestController@updatedQuestionSet');
    Route::get('mock_test/question_details', 'MockTestController@questionDetails');




    //EPE/Exam 
    Route::get('epe', 'EpeController@index')->name('epe.index');
    Route::get('epe/create', 'EpeController@create')->name('epe.create');
    Route::post('epe', 'EpeController@store')->name('epe.store');
    Route::get('epe/{id}/edit', 'EpeController@edit')->name('epe.edit');
    Route::patch('epe/{id}', 'EpeController@update')->name('epe.update');
    Route::delete('epe/{id}', 'EpeController@destroy')->name('epe.destroy');
    Route::get('epe/show_part_list', 'EpeController@relatePartList')->name('epe.relatePartList');
    Route::post('epe/show_subject', 'EpeController@showSubject')->name('epe.showSubject');
    Route::post('epe/previewEpe', 'EpeController@previewEpe')->name('epe.previewEpe');
    Route::post('epe/manage', 'EpeController@storeEpe')->name('epe.storeEpe');
    Route::post('epe/filter/', 'EpeController@filter');
    Route::post('epe/showEpeInfo/', 'EpeController@showEpeInfo')->name('epe.showEpeInfo');
    Route::get('epe/questionset/{id}', 'EpeController@questionSet')->name('epe.questionSet');
    Route::post('epe/updatedQuestionSet', 'EpeController@updatedQuestionSet')->name('epe.updatedQuestionSet');
    Route::get('epe/questionDetails', 'EpeController@questionDetails')->name('epe.questionDetails');
    Route::get('epe/subjective_question_details', 'EpeController@subjectiveQuestionDetails')->name('epe.subjectiveQuestionDetails');
    Route::get('epe/update_publish/', 'EpeController@updatePublish')->name('epe.updatePublish');
    Route::post('epe/updated_publish/', 'EpeController@updatedPublish')->name('epe.updatedPublish');
    Route::get('epe/subquestionset/{id}', 'EpeController@subQuestionSet')->name('epe.subQuestionSet');
    Route::post('epe/storesubqusset/', 'EpeController@storeSubQusSet')->name('epe.storeSubQusSet');
    Route::get('epe/subquestion', 'EpeController@subQuestion')->name('epe.subQuestion');
    Route::get('epe/showsubqus/', 'EpeController@showSubQus')->name('epe.showSubQus');
    Route::post('epe/storesubqus/', 'EpeController@storeSubQus')->name('epe.storeSubQus');
    Route::get('epe/question_details', 'EpeController@questionDetails')->name('epe.questionDetails');
    Route::get('ajaxresponse/epeInfo', 'AjaxResponseController@getEpeInfo')->name('epe.getEpeInfo');
    Route::get('ajaxresponse/mock-info', 'AjaxResponseController@getMockInfo');
    Route::post('ajaxresponse/student-details', 'AjaxResponseController@getStudentDetails');

    //Assign exam to student
    Route::get('examtostudent', 'ExamToStudentController@index')->name('epe.index');
    Route::post('examtostudent/getStudent', 'ExamToStudentController@getStudent')->name('epe.getStudent');
    Route::post('examtostudent/saveStudent', 'ExamToStudentController@saveStudent')->name('epe.saveStudent');
    Route::post('examtostudent/getAssignedStudent', 'ExamToStudentController@getAssignedStudent')->name('epe.getAssignedStudent');

    //Exam attende
    Route::get('epeattendee', 'EpeAttendeeController@index');
    Route::get('epeattendee/show_part_list', 'EpeAttendeeController@relatePartList');
    Route::post('epeattendee/show_epe', 'EpeAttendeeController@showEpe');
    Route::post('epeattendee/show_attendee_epe', 'EpeAttendeeController@showAttendeeEpe');
    Route::post('epeattendee/delete', 'EpeAttendeeController@delete');
    Route::post('epeattendee/forceSubmit', 'EpeAttendeeController@forceSubmit');
    Route::post('epeattendee/saveForceSubmit', 'EpeAttendeeController@saveForceSubmit');

    //Unlock Request
    Route::get('unlockrequest', 'UnlockRequestCiController@index');
    Route::post('unlockrequest/unlock', 'UnlockRequestCiController@unlock');
    Route::post('unlockrequest/deny', 'UnlockRequestCiController@deny');


    Route::get('previousquestion', 'PreviousQuestionDownloadController@index');
    Route::post('previousquestion/show_previous_question_lists', 'PreviousQuestionDownloadController@showPreviousQuestion');
    Route::get('perviousquestion/question_details', 'PreviousQuestionDownloadController@questionDetails');
    Route::get('perviousquestion/subjective_question_details', 'PreviousQuestionDownloadController@subjectiveQuestionDetails');

    // report
    Route::get('mocktestresult', 'MockTestResultController@index');
    Route::get('mocktestresult/studentlist', 'MockTestResultController@studentList');
    Route::post('mocktestresult/showresult', 'MockTestResultController@showResult');
    Route::get('mocktestresult/questionanswersheet', 'MockTestResultController@questionAnswerSheet');

    Route::get('examResult', 'ExamResultController@index');
    Route::post('examResult/filter', 'ExamResultController@filter');
    Route::post('examResult/show_epe', 'ExamResultController@showEpe');
    Route::post('examResult/show_student', 'ExamResultController@showStudent');

    Route::get('studentwiseresult', 'StudentWiseResultController@index');
    Route::get('studentwiseresult/show_student_list', 'StudentWiseResultController@showStudents');
    Route::post('studentwiseresult/show_result', 'StudentWiseResultController@showResult');




    Route::get('myresult', 'StudentResultController@showResult');
    // My Profile
    Route::get('student/student_profile/{id}', 'StudentController@studentProfile');
});

