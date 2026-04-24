<div class="row">

    <!-- Profile Info and Notifications -->
    <div class="col-md-6 col-sm-8 clearfix">

        <ul class="user-info pull-left pull-none-xsm">

            <!-- Profile Info -->
            <li class="profile-info dropdown">
                <!-- add class "pull-right" if you want to place this from right -->

                <a href="#" class="hidden-print">
                    <?=$_SESSION ['usuario'] ?>
                </a>

            </li>

        </ul>

    </div>


    <!-- Raw Links -->
    <div class="col-md-6 col-sm-4 clearfix hidden-xs">

        <ul class="list-inline links-list pull-right">

            <li>
                <a href="<?=base_url("cerrar") ?>">
                    Salir <i class="entypo-logout right"></i>
                </a>
            </li>
        </ul>

    </div>

</div>