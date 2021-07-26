@include('layouts.default.header')
<body id="addFullMenuClass" class="page-header-fixed page-content-white">
    <div class="page-wrapper">
        @include('layouts.default.topNavbar')
        <div class="clearfix"> </div>
        <div class="page-container">
            @include('layouts.default.sidebar')
            <div class="page-content-wrapper">
                <div class="page-content">
                    @yield('data_count')
                    <div class="clearfix"></div>
                </div>
            </div>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        @include('layouts.default.footer')
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
    @include('layouts.default.footerScript')
</body>
</html>