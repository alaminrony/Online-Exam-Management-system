<?php echo $__env->make('layouts.default.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<body id="addFullMenuClass" class="page-header-fixed page-content-white">
    <div class="page-wrapper">
        <?php echo $__env->make('layouts.default.topNavbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="clearfix"> </div>
        <div class="page-container">
            <?php echo $__env->make('layouts.default.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="page-content-wrapper">
                <div class="page-content">
                    <?php echo $__env->yieldContent('data_count'); ?>
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        <?php echo $__env->make('layouts.default.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="quick-nav-overlay"></div>
     <script type="text/javascript">
        $(function () {
            $(document).on("click", "#fullMenu", function () {
                var fullMenu = $("#fullMenu").attr("data-fullMenu");
                if (fullMenu == '1') {
                    localStorage.setItem('fullMenu', fullMenu);
                    $("#fullMenu").attr("data-fullMenu", "2");
                    $("#addFullMenuClass").addClass("page-sidebar-closed");
                    $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");
                } else {
                    localStorage.removeItem('fullMenu');
                    $("#fullMenu").attr("data-fullMenu", "1");
                    $("#addFullMenuClass").removeClass("page-sidebar-closed");
                    $("#addsidebarFullMenu").removeClass("page-sidebar-menu-closed");
                }
            });

            if (localStorage.getItem('fullMenu') == '1') {
                $("#fullMenu").attr("data-fullMenu", "2");
                $("#addFullMenuClass").addClass("page-sidebar-closed");
                $("#addsidebarFullMenu").addClass("page-sidebar-menu-closed");
            } else {
                $("#fullMenu").attr("data-fullMenu", "1");
                $("#addFullMenuClass").removeClass("page-sidebar-closed");
                $("#addsidebarFullMenu").removeClass("page-sidebar-menu-closed");
            }
        });
    </script>
    <?php echo $__env->make('layouts.default.footerScript', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\oem\resources\views/layouts/default/master.blade.php ENDPATH**/ ?>