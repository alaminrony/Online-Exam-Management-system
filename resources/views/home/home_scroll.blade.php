<!--scrollbar start-->
@if (!$scrollmessageList->isEmpty())
<section id="scroll_section" class="scroll_body">
    <div class="container">
        <div class="row">
            <marquee onmouseover="this.stop();" onmouseout="this.start();">
                <div class="marquee marquee2">

                    <?php
                    $str = '';
                    ?>
                    @foreach($scrollmessageList as $message)
                    <?php $str .= '<i class="fa fa-envelope-o"></i> ' . $message->message . ' | '; ?>
                    @endforeach
                    <?php
                    echo trim($str, " | ");
                    ?>

                </div>
            </marquee>
        </div>
    </div>
</section>
@endif
<!--end scrollbar section -->