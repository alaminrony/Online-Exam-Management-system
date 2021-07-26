<?php $__env->startSection('data_count'); ?>
<div class="col-md-12">
    <?php echo $__env->make('layouts.flash', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="portlet box green">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i><?php echo app('translator')->get('label.USER_LIST'); ?>
            </div>
            <div class="actions">
                <a class="btn btn-default btn-sm create-new" href="<?php echo e(URL::to('user/create'.Helper::queryPageStr($qpArr))); ?>"> <?php echo app('translator')->get('label.CREATE_NEW_USER'); ?>
                    <i class="fa fa-plus create-new"></i>
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <?php echo e(Form::open(array('role' => 'form', 'url' => 'user/filter', 'class' => ''))); ?>

            <?php echo e(Form::hidden('page', Helper::queryPageStr($qpArr))); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label col-md-4" for="userGroup"><?php echo app('translator')->get('label.USER_GROUP'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('fil_group_id',  $groupList, Request::get('fil_group_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'userGroup']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="rankId"><?php echo app('translator')->get('label.SELECT_RANK'); ?></label>
                        <div class="col-md-6">
                            <?php echo Form::select('fil_rank_id',  $rankList, Request::get('fil_rank_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'rankId']); ?>

                        </div>
                    </div>
                </div>                        
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="searchText"><?php echo app('translator')->get('label.SEARCH_TEXT'); ?></label>
                        <div class="col-md-8">
                            <?php echo e(Form::text('search_text',Request::get('search'), array('id'=> 'searchText', 'class' => 'form-control', 'placeholder' => __('label.SERACH_TEXT')))); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="appointmentId"><?php echo app('translator')->get('label.APPROINTMENT'); ?></label>
                        <div class="col-md-8">
                            <?php echo Form::select('fil_designation_id',  $appointmentList, Request::get('fil_designation_id'), ['class' => 'form-control js-source-states','autocomplete'=>'off','id'=>'appointmentId']); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-md green btn-outline filter-submit margin-bottom-20">
                        <i class="fa fa-search"></i> <?php echo app('translator')->get('label.FILTER'); ?>
                    </button>
                </div>
            </div>
            <?php echo e(Form::close()); ?>


            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="text-center info">
                            <th class="vcenter"><?php echo app('translator')->get('label.SL_NO'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.USER_GROUP'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.DEPARTMENT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.RANK'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.APPOINTMENT'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.BRANCH'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.REGION'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.CLUSTER'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.NAME'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.USERNAME'); ?></th>
                            <th class='text-center vcenter'><?php echo app('translator')->get('label.PHOTO'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.ACCOUNT_CONFIRMED'); ?></th>
                            <th class="text-center vcenter"><?php echo app('translator')->get('label.STATUS'); ?></th>
                            <th class='text-center vcenter'><?php echo app('translator')->get('label.ACTION'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!$targetArr->isEmpty()): ?>
                        <?php
                        $page = Request::get('page');
                        $page = empty($page) ? 1 : $page;
                        $sl = ($page - 1) * Session::get('paginatorCount');
                        ?>
                        <?php $__currentLoopData = $targetArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="vcenter"><?php echo e(++$sl); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->UserGroup->name); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->department->name); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->rank->title??''); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->designation->title??''); ?></td>
                            <td class="text-center vcenter"><?php echo e(!empty($target->branch_name) ? $target->branch_name: ''); ?></td>
                            <td class="text-center vcenter"><?php echo e(!empty($target->region_name) ? $target->region_name: ''); ?></td>
                            <td class="text-center vcenter"><?php echo e(!empty($target->cluster_name) ? $target->cluster_name: ''); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->first_name .' '. $target->last_name); ?></td>
                            <td class="text-center vcenter"><?php echo e($target->username); ?></td>
                            <td class="text-center vcenter">
                                <?php if(isset($target->photo)): ?>
                                <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/uploads/user/<?php echo e($target->photo); ?>" alt="<?php echo e($target->first_name.' '.$target->last_name); ?>">
                                <?php else: ?>
                                <img width="40" height="40" src="<?php echo e(URL::to('/')); ?>/public/img/unknown.png" alt="<?php echo e($target->first_name.' '.$target->last_name); ?>">
                                <?php endif; ?>
                            </td>
                            <td class="text-center vcenter">
                                <?php if($target->first_login == '1'): ?>
                                <span class="label label-success"><?php echo app('translator')->get('label.YES'); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo app('translator')->get('label.NO'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center vcenter">
                                <?php if($target->status == 'active'): ?>
                                <span class="label label-success"><?php echo e($target->status); ?></span>
                                <?php else: ?>
                                <span class="label label-warning"><?php echo e($target->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="action-center vcenter">
                                <div class="text-center user-action">
                                    <?php echo e(Form::open(array('url' => 'user/' . $target->id.'/'.Helper::queryPageStr($qpArr), 'id' => 'delete'))); ?>

                                    <?php echo e(Form::hidden('_method', 'DELETE')); ?>


                                    <?php
                                    $dd = Request::query();

                                    if (!empty($dd)) {
                                        $param = '';
                                        $sn = 1;

                                        foreach ($dd as $key => $item) {
                                            if ($sn === 1) {
                                                $param .= $key . '=' . $item;
                                            } else {
                                                $param .= '&' . $key . '=' . $item;
                                            }
                                            $sn++;
                                        }//foreach
                                    }
                                    ?>
                                    <?php if((Auth::user()->group_id == 1) || (Auth::user()->group_id != $target->group_id)): ?>
                                    <a class='btn btn-info btn-xs tooltips' href="<?php echo e(URL::to('user/activeUser/' . $target->id )); ?><?php if(isset($param)): ?><?php echo e('/'.$param); ?> <?php endif; ?>" data-rel="tooltip" title="<?php if($target->status == 'active'): ?> Inactivate <?php else: ?> Activate <?php endif; ?>" data-container="body" data-trigger="hover" data-placement="top">
                                        <?php if($target->status == 'active'): ?>
                                        <i class='fa fa-remove'></i>
                                        <?php else: ?>
                                        <i class='fa fa-check-circle'></i>
                                        <?php endif; ?>
                                    </a>
                                    <?php endif; ?>
                                    <a class='btn btn-primary btn-xs tooltips' href="<?php echo e(URL::to('user/' . $target->id . '/edit'.Helper::queryPageStr($qpArr))); ?>" title="<?php echo app('translator')->get('label.EDIT_USER'); ?>" data-container="body" data-trigger="hover" data-placement="top">
                                        <i class='fa fa-edit'></i>
                                    </a>
                                    <a class="tooltips" href="<?php echo e(URL::to('changePassword/' . $target->id)); ?><?php if(isset($param)): ?><?php echo e('/'.$param); ?> <?php endif; ?>" data-original-title="<?php echo app('translator')->get('label.CHANGE_PASSWORD'); ?>">
                                        <span class="btn btn-success btn-xs"> 
                                            <i class="fa fa-key"></i>
                                        </span>
                                    </a>
                                    <a class="tooltips details-btn tooltips" data-toggle="modal"  data-id="<?php echo e($target->id); ?>" data-target="#details" data-id="<?php echo e($target->id); ?>" href="#view-modal" id="detailsBtn-<?php echo e($target->id); ?>" title="<?php echo app('translator')->get('label.USER_DETAILS'); ?>" data-placement="top">
                                        <span class="btn btn-success btn-xs"> 
                                            &nbsp;<i class='fa fa-info'></i>&nbsp;
                                        </span>
                                    </a>
                                    <?php if((Auth::user()->group_id == 1) || (Auth::user()->group_id != $target->group_id)): ?>
                                    <button class="btn btn-danger btn-xs tooltips delete" type="submit" title="<?php echo app('translator')->get('label.DELETE'); ?>" data-placement="top" data-rel="tooltip" data-original-title="<?php echo app('translator')->get('label.DELETE'); ?>">
                                        <i class='fa fa-trash'></i>
                                    </button>
                                    <?php endif; ?>
                                    <?php echo e(Form::close()); ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="12" class="vcenter"><?php echo app('translator')->get('label.NO_USER_FOUND'); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php echo $__env->make('layouts.paginator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>	
    </div>
</div>
<!--User modal -->
<div class="modal fade" id="details" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div id="showDetails"></div> 
    </div>
</div>
<!--End user modal -->
<script type="text/javascript">
    $(function () {
        $(function () {
            $(document).on('click', '.details-btn', function (e) {
                e.preventDefault();
                var userId = $(this).attr('data-id');
                var options = {
                    closeButton: true,
                    debug: false,
                    positionClass: "toast-bottom-right",
                    onclick: null
                };
                $.ajax({
                    url: "<?php echo URL::to('user/details'); ?>",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        user_id: userId
                    },
                    beforeSend: function () {
                        App.blockUI({boxed: true});
                    },
                    success: function (res) {
                        $('#showDetails').html(res.html);
                        $('.tooltips').tooltip();
                        App.unblockUI();
                    },
                    error: function (jqXhr, ajaxOptions, thrownError) {
                        toastr.error('<?php echo app('translator')->get("label.SOMETHING_WENT_WRONG"); ?>', 'Error', options);
                    }
                });
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\oem\resources\views/user/index.blade.php ENDPATH**/ ?>