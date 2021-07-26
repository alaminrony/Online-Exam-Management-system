<?php
$currentControllerName = Request::segment(1);
$currentControllerName = Request::route()->getName();
$currentControllerFunction = Route::currentRouteAction();
$currentCont = preg_match('/([a-z]*)@/i', request()->route()->getActionName(), $currentControllerFunction);
$currentControllerName = str_replace('controller', '', strtolower($currentControllerFunction[1]));
$routeName = strtolower(Route::getFacadeRoot()->current()->uri());
?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul id="addsidebarFullMenu" class="page-sidebar-menu page-header-fixed" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" >
            <!--li class="sidebar-toggler-wrapper hide">
            <div class="sidebar-toggler">
                <span></span>
            </div>
        </li-->

            <!-- start dashboard menu -->
            <li <?php $current = ( in_array($currentControllerName, array('dashboard'))) ? 'start active open' : ''; ?>class="nav-item <?php echo e($current); ?> nav-item ">
                <a href="<?php echo e(url('/dashboard')); ?>" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title"> <?php echo app('translator')->get('label.DASHBOARD'); ?></span>
                </a>
            </li>

            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php echo ($currentControllerName == 'gallery') ? 'start active open' : ''; ?>">
                <a href="<?php echo e(URL::to('gallery')); ?>"  class="nav-link nav-toggle">
                    <i class="fa fa-file-image-o"></i>
                    <span class="title"><?php echo app('translator')->get('label.PHOTO_GALLERY'); ?></span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php
            echo (in_array($currentControllerName, array('rank', 'designation', 'branch'
                , 'department', 'division', 'region', 'cluster', 'subject'))) ? 'start active open' : '';
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-puzzle"></i>
                    <span class="title"><?php echo app('translator')->get('label.GENERAL_SETUP'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    
                    <li class="nav-item <?php echo ($currentControllerName == 'region') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('region')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.REGION_MGT'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'cluster') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('cluster')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CLUSTER_MGT'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'branch') ? 'start active open' : ''; ?> ">
                        <a href="<?php echo e(URL::to('branch')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BRANCH_MANAGEMENT'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'department') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('department')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.DEPARTMENT_MGT'); ?></span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo ($currentControllerName == 'rank') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('rank')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.RANK_MANAGEMENT'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'designation') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('designation')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.APPOINTMENT_MANAGEMENT'); ?></span>
                        </a>
                    </li>
                    

                    <li class="nav-item <?php echo ($currentControllerName == 'subject') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('subject')); ?>"  class="nav-link nav-toggle">
<!--                            <i class="fa fa-book"></i>-->
                            <span class="title"><?php echo app('translator')->get('label.SUBJECT_MANAGEMENT'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>
            <?php endif; ?>
            
            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('usergroup', 'user'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title"> <?php echo app('translator')->get('label.USER_MANAGEMENT'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(Auth::user()->group_id,[1])): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'usergroup') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('userGroup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.USER_GROUP'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1])): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'user') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('user')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.USER_MANAGEMENT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            
            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('configuration', 'signatory', 'scrollmessage', 'passwordsetup'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title"><?php echo app('translator')->get('label.SETTING'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
<!--                    <li class="nav-item <?php echo ($currentControllerName == 'signatory') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('signatory')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SIGNATORY'); ?></span>
                        </a>
                    </li>-->
                    <li class="nav-item <?php echo ($currentControllerName == 'configuration') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('configuration')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CONFIGURATION'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'scrollmessage') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('scrollmessage')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.SCROLL_MESSAGE'); ?></span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo ($currentControllerName == 'passwordsetup') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('passwordSetup')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.PASSWORD_SETUP'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            <!--<li class="nav-item <?php echo ($currentControllerName == 'student') ? 'start active open' : ''; ?>">
                <a href="<?php echo e(URL::to('student/student_profile/'.Auth::user()->id)); ?>"  class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title"><?php echo app('translator')->get('label.MY_PROFILE'); ?></span>
                    <span class="selected"></span>
                </a>
            </li>-->

            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('marksdistribution', 'subjecttods', 'subjecttoci')) || in_array($routeName, array('part/course', 'part/phase'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-loop"></i>
                    <span class="title"><?php echo app('translator')->get('label.RELATIONSHIP_SETUP'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item <?php echo ($currentControllerName == 'subjecttods') ? 'start active open' : ''; ?>"">
                        <a href="<?php echo e(URL::to('subjecttods')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.ASSIGN_SUBJECT_TO_EXAMINER'); ?></span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if(in_array(Auth::user()->group_id,[1])): ?>
            <li class="nav-item <?php
            echo (in_array($currentControllerName, array('tae', 'question', 'epe'
                , 'mocktest', 'epeattendee', 'taeassignmarks', 'specialpermission', 'epecimarking', 'epeirregularreschedule'
                , 'taeirregularreschedule', 'unlockrequestci', 'unlockrequestds', 'examtostudent'))) ? 'start active open' : '';
            ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-file-text-o"></i>
                    <span class="title"><?php echo app('translator')->get('label.EXAM_MANAGEMENT'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(Auth::user()->group_id,[1]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'question') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('question')); ?>"  class="nav-link nav-toggle">
                            <i class="icon-tag"></i>
                            <span class="title"><?php echo app('translator')->get('label.QUESTION_BANK'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo ($currentControllerName == 'mocktest') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('mock_test')); ?>"  class="nav-link nav-toggle">
                            <i class="fa fa-filter"></i>
                            <span class="title"><?php echo app('translator')->get('label.EPE_MOCK_TEST'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'epe') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('epe')); ?>"  class="nav-link nav-toggle">
                            <i class="icon-note"></i>
                            <span class="title"><?php echo app('translator')->get('label.EXAM'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'examtostudent') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('examtostudent')); ?>"  class="nav-link nav-toggle">
                            <i class="icon-equalizer"></i>
                            <span class="title"><?php echo app('translator')->get('label.ASSIGN_EXAM_TO_EMPLOYEE'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'epeattendee') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('epeattendee')); ?>"  class="nav-link nav-toggle">
                            <i class="fa fa-child"></i>
                            <span class="title"><?php echo app('translator')->get('label.EPE_ATTENDEE'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <?php if(in_array(Auth::user()->group_id,[1])): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'unlockrequestci') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('unlockrequest')); ?>"  class="nav-link nav-toggle">
                            <i class="fa fa-unlock"></i>
                            <span class="title"><?php echo app('translator')->get('label.EPE_UNLOCK_REQUEST'); ?></span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <?php if(in_array(Auth::user()->group_id,[2]) && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo ($currentControllerName == 'epedsmarking') ? 'start active open' : ''; ?>">
                <a href="<?php echo e(URL::to('epedsmarking')); ?>"  class="nav-link nav-toggle">
                    <i class="fa fa-book"></i>
                    <span class="title"><?php echo app('translator')->get('label.EPE_MARKING_SUB'); ?></span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->group_id == '3' && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo ($routeName == 'isspstudentactivity/myepe') ? 'start active open' : ''; ?>">
                <a href="<?php echo e(URL::to('isspstudentactivity/myepe')); ?>"  class="nav-link nav-toggle">
                    <i class="icon-note"></i>
                    <span class="title"><?php echo app('translator')->get('label.MY_EPE'); ?></span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(Auth::user()->group_id == '3' && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo ($routeName == 'isspstudentactivity/mymocktest') ? 'start active open' : ''; ?>">
                <a href="<?php echo e(URL::to('isspstudentactivity/mymocktest')); ?>"  class="nav-link nav-toggle">
                    <i class="fa fa-filter"></i>
                    <span class="title"><?php echo app('translator')->get('label.EPE_MOCK_TEST'); ?></span>
                    <span class="selected"></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if(in_array(Auth::user()->group_id,[1,2,3,4]) && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('gradingsystem', 'examresultreport', 'mocktestreport', 'employeewiseresult'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-check"></i>
                    <span class="title"><?php echo app('translator')->get('label.RESULT'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(Auth::user()->group_id,[1,2,3,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'gradingsystem') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('gradingSystem')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.GRADING_SYSTEM'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,3,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'examresultreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('examResultReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.EXAM_RESULT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,3,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'mocktestreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('mockTestReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.MOCK_TEST_RESULT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,3,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'employeewiseresult') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('employeeWiseResult')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.EMPLOYEE_WISE_RESULT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('regionstatusreport', 'branchresult', 'stafftrendanalysis', 'clusterresultreport', 'departmentstatusreport', 'participationstatus'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title"> <?php echo app('translator')->get('label.REPORT'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'regionstatusreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('regionStatusReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.REGION_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'clusterresultreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('clusterResult')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.CLUSTER_RESULT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'branchresult') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('branchResult')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.BRANCH_RESULT'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'departmentstatusreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('departmentStatusReport')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.DEPARTMENT_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'stafftrendanalysis') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('staffTrendAnalysis')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.STAFF_TREND_ANALYSIS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'participationstatus') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('participationStatus')); ?>" class="nav-link">
                            <span class="title"><?php echo app('translator')->get('label.PARTICIPATION_STATUS'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                </ul>
            </li>
            <?php endif; ?>
            <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
            <li class="nav-item <?php echo (in_array($currentControllerName, array('loginreport', 'userlogreport', 'questionlogreport', 'branchlogreport', 'changepasswordlogreport'))) ? 'start active open' : ''; ?>">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="fa fa-sticky-note"></i>
                    <span class="title"> <?php echo app('translator')->get('label.HISTORY_REPORT'); ?></span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <?php if(in_array(Auth::user()->group_id,[1,2,4]) && Auth::user()->first_login =='1'): ?>
                    <li class="nav-item <?php echo ($currentControllerName == 'loginreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('loginReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.LOGIN_REPORT'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'userlogreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('userLogReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.USER'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'changepasswordlogreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('changePasswordLog')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.CHANGE_PASSWORD'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'questionlogreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('questionLogReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.QUESTION'); ?></span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo ($currentControllerName == 'branchlogreport') ? 'start active open' : ''; ?>">
                        <a href="<?php echo e(URL::to('branchLogReport')); ?>" class="nav-link ">
                            <span class="title"><?php echo app('translator')->get('label.BRANCH'); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div><?php /**PATH C:\xampp\htdocs\oem\resources\views/layouts/default/sidebar.blade.php ENDPATH**/ ?>