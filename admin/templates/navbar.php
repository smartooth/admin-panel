        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">LD Admin <i class="icon-wrench"></i></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav visible-phone">
                            <li class="nav-header">Changelog</li>
                            <li class="<?= @$nav[0] ?>"><a href="/admin"><i class="icon-home"></i> Home</a></li>
                            <li class="<?= @$nav[1] ?>"><a href="/admin/view"><i class="icon-eye-open"></i> View</a></li>
                            <li class="<?= @$nav[2] ?>"><a href="/admin/edit"><i class="icon-cogs"></i> Edit</a></li>
                            <li class="<?= @$nav[3] ?>"><a href="/admin/add"><i class="icon-plus"></i> Add</a></li>
                            <li class="nav-header">Profile</li>
                            <li class="<?= @$nav[4] ?>"><a href="/admin/chpwd"><i class="icon-link"></i> Change Password</a></li>
                            <li class="<?= @$nav[5] ?>"><a href="/admin/profile"><i class="icon-pencil"></i> Edit Profile</a></li>
                            <li class="<?= @$nav[6] ?>"><a href="/admin/logout"><i class="icon-signout"></i> Log out</a></li>
                            <li class="nav-header">Edit Users</li>
                            <li class="<?= @$nav[7] ?>"><a href="/admin/users"><i class="icon-eye-open"></i> View</a></li>
                            <li class="<?= @$nav[8] ?>"><a href="/admin/adduser"><i class="icon-plus"></i> Add</a></li>
                        </ul>
                        <span class="nav visible-tablet">
                            <span class="nav-header">Quick Links</span> <a href="/admin"><i class="icon-home"></i> Home</a> | <a href="/admin/logout"><i class="icon-signout"></i> Log Out</a></span>
                        </span>
                        <p class="navbar-text pull-right">
                            Logged in as <a href="/admin/profile" class="navbar-link" style="text-transform: capitalize"><?= $user["name"] ?> <i class="icon-user"></i></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

