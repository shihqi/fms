<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('/public/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="active"><a href="{{ url('home') }}"><i class='fa fa-dashboard'></i> <span>Dashboard</span></a></li>
            <li><a href="{{ url('admin/customers') }}"><i class='fa fa-users'></i><span>Customers</span></a></li>
            <li><a href="{{ url('admin/platforms') }}"><i class="fa fa-sitemap"></i><span>Platforms</span></a></li>
            <li><a href="{{ url('admin/feeds') }}"><i class="fa fa-file-text"></i><span>Feeds</span></a></li>
            <li><a href="{{ url('admin/products') }}"><i class="fa fa-list"></i><span>Products</span></a></li>
            <li class="treeview">
                <a href="#"><i class='fa fa-link'></i> <span>Multilevel</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
            @if(Entrust::hasRole('administrator'))
            <li class="treeview"><a href="#"><i class='fa fa-users'></i><span>Users</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('admin/users') }}">All Users</a></li>
                    <li><a href="{{ url('admin/roles') }}">Roles</a></li>
                    <li><a href="#">Permissions</a></li>
                </ul>
            </li>
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
