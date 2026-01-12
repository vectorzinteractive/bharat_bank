<div class="sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            @include('auth.logo')
        </div>

        <div class="sidebar-content" id="simple-bar">
            <div class="card side-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div class="avtar-img flex-shrink-0">
                            <img src="{{ asset('/backend/images/user.jpg') }}" alt="">
                        </div>

                        <div class="user-profile flex-grow-1">
                             <h6>{{ $user->name }}</h6>
                            <small>Administrator</small>
                        </div>

                        <a class="dropdown-icon" data-bs-toggle="collapse" href="#collapseExample" role="button"
                            aria-expanded="false" aria-controls="collapseExample">
                           <i class="las la-angle-down"></i>
                        </a>
                    </div>

                    <div class="collapse" id="collapseExample">
                        <div class="side-user-links d-flex flex-column pt-3">
                            <a href="{{url('cms-admin/profile')}}">
                                <i class="las la-cog"></i>
                                <span>Settings</span>
                            </a>
                            <a href="#" class="logout-form">
                            <i class="las la-power-off"></i>
                                <form method="POST" action="{{ url('cms-admin/logout')}}" class="vi-mb-0"> @csrf
                                    <button type="submit" name="logout" class="vi-logout-btn vi-p-0"><span>Logout</span></button>
                                </form>
                                {{-- <span>Logout</span> --}}
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <nav id="sidebar" class="sidebar-wrapper">
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>Navigation</span>
                        </li>
                        <li class="dropdown-item">
                            <a href="" class="sidebar-link menu-toggle">
                                <div class="sb-drpdn-head">
                                    <div class="head-icon">
                                        <i data-duoicon="box-2"></i>
                                    </div>
                                    <span>Auctions</span>
                                </div>
                                <div class="carret-arrow">
                                    <i class="las la-angle-right"></i>
                                </div>
                            </a>

                            <ul class="sidebar-submenu sub-menu">
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/auctions") }}" class="sidebar-link">Auctions</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/auction-states") }}" class="sidebar-link">Auction States</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/blog-categories") }}" class="sidebar-link">Auction Cities</a>
                                </li>
                            </ul>
                        </li>


                        @php
                            use Nwidart\Modules\Facades\Module;
                        @endphp

                        @if(Module::has('Blog') && Module::find('Blog')->isEnabled())
                        <li class="dropdown-item">
                            <a href="" class="sidebar-link menu-toggle">
                                <div class="sb-drpdn-head">
                                    <div class="head-icon">
                                        <i data-duoicon="box-2"></i>
                                    </div>
                                    <span>Blogs</span>
                                </div>
                                <div class="carret-arrow">
                                    <i class="las la-angle-right"></i>
                                </div>
                            </a>

                            <ul class="sidebar-submenu sub-menu">
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/blogs") }}" class="sidebar-link">Articles</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/blog-categories") }}" class="sidebar-link">Categories</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Module::has('Careers') && Module::find('Careers')->isEnabled())
                        <li class="dropdown-item">
                            <a href="" class="sidebar-link menu-toggle">
                                <div class="sb-drpdn-head">
                                    <div class="head-icon">
                                        <i data-duoicon="box-2"></i>
                                    </div>
                                    <span>Careers</span>
                                </div>
                                <div class="carret-arrow">
                                    <i class="las la-angle-right"></i>
                                </div>
                            </a>

                            <ul class="sidebar-submenu sub-menu">
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/careers/applications") }}" class="sidebar-link">Application's</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/careers") }}" class="sidebar-link">Job's</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/departments") }}" class="sidebar-link">Departments</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url("cms-admin/locations") }}" class="sidebar-link">Locations</a>
                                </li>

                            </ul>
                        </li>
                        @endif

                        @if(Module::has('GenericProject') && Module::find('GenericProject')->isEnabled())
                        <li class="dropdown-item">
                            <a href="" class="sidebar-link menu-toggle">
                                <div class="sb-drpdn-head">
                                    <div class="head-icon">
                                        <i data-duoicon="box-2"></i>
                                    </div>
                                    <span>Projects</span>
                                </div>
                                <div class="carret-arrow">
                                    <i class="las la-angle-right"></i>
                                </div>
                            </a>

                            <ul class="sidebar-submenu sub-menu">
                                <li class="dropdown-item">
                                    <a href="{{ url('cms-admin/projects') }}" class="sidebar-link">Project</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url('cms-admin/projects-categories') }}" class="sidebar-link">Categories</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url('cms-admin/projects-subcategories') }}" class="sidebar-link">SubCategories</a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Module::has('Team') && Module::find('Team')->isEnabled())
                        <li class="dropdown-item">
                            <a href="" class="sidebar-link menu-toggle">
                                <div class="sb-drpdn-head">
                                    <div class="head-icon">
                                        <i data-duoicon="box-2"></i>
                                    </div>
                                    <span>Team</span>
                                </div>
                                <div class="carret-arrow">
                                    <i class="las la-angle-right"></i>
                                </div>
                            </a>

                            <ul class="sidebar-submenu sub-menu">
                                <li class="dropdown-item">
                                    <a href="{{ url('cms-admin/teams') }}" class="sidebar-link">Members</a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ url('cms-admin/designation') }}" class="sidebar-link">Designations</a>
                                </li>
                            </ul>
                        </li>
                        @endif






                    </ul>
                </div>
            </nav>
        </div>
    </aside>
</div>

