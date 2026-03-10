<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
        <img class="img-90 rounded-circle" src="{{ asset('assets/images/dashboard/1.png') }}" alt="User Image" />
        <div class="badge-bottom">
            <span class="badge badge-primary">{{ strtoupper(Auth::user()->role) }}</span>
        </div>
        <a href="{{ url('user-profile') }}">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end">
                            <span>Back</span>
                            <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
                        </div>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav {{ routeActive('dashboard') }}"
                            href="{{ route('dashboard') }}">
                            <i data-feather="home"></i><span>Dashboard</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('users') }}" href="javascript:void(0)">
                            <i data-feather="users"></i><span>Anggota</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('users') }};">
                            <li><a href="{{ route('users.index') }}" class="{{ routeActive('users.index') }}">Data
                                    Anggota</a></li>
                            <li><a href="{{ route('users.create') }}" class="{{ routeActive('users.create') }}">Buat
                                    Anggota</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('informations') }}" href="javascript:void(0)">
                            <i data-feather="edit"></i><span>Informasi</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('informations') }};">
                            <li><a href="{{ route('informations.index') }}"
                                    class="{{ routeActive('informations.index') }}">Data Informasi</a></li>
                            <li><a href="{{ route('informations.create') }}"
                                    class="{{ routeActive('informations.create') }}">Buat Informasi</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('learnings') }}" href="javascript:void(0)">
                            <i data-feather="layers"></i><span>Materi Belajar</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('learnings') }};">
                            <li><a href="{{ route('learnings.index') }}"
                                    class="{{ routeActive('learnings.index') }}">Data Materi</a></li>
                            <li><a href="{{ route('learnings.create') }}"
                                    class="{{ routeActive('learnings.create') }}">Buat Materi</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('financials') }}" href="javascript:void(0)">
                            <i data-feather="dollar-sign"></i><span>Laporan Keuangan</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('financials') }};">
                            <li><a href="{{ route('financials.index') }}"
                                    class="{{ routeActive('financials.index') }}">Data Laporan</a></li>
                            <li><a href="{{ route('financials.create') }}"
                                    class="{{ routeActive('financials.create') }}">Buat Laporan</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('organizations') }}" href="javascript:void(0)">
                            <i data-feather="globe"></i><span>Struktur Organisasi</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('organizations') }};">
                            <li><a href="{{ route('organizations.index') }}"
                                    class="{{ routeActive('organizations.index') }}">Data Struktur</a></li>
                            <li><a href="{{ route('organizations.create') }}"
                                    class="{{ routeActive('organizations.create') }}">Buat Struktur</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('socials') }}" href="javascript:void(0)">
                            <i data-feather="share-2"></i><span>Program Sosial</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('socials') }};">
                            <li><a href="{{ route('socials.index') }}" class="{{ routeActive('socials.index') }}">Data
                                    Program</a></li>
                            <li><a href="{{ route('socials.create') }}"
                                    class="{{ routeActive('socials.create') }}">Buat Program</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('unions') }}" href="javascript:void(0)">
                            <i data-feather="send"></i><span>Serikat SP PION</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('unions') }};">
                            <li><a href="{{ route('unions.index') }}" class="{{ routeActive('unions.index') }}">Data
                                    Serikat</a></li>
                            <li><a href="{{ route('unions.create') }}" class="{{ routeActive('unions.create') }}">Buat
                                    Serikat</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('votes') }}" href="javascript:void(0)">
                            <i data-feather="check-square"></i><span>Pemilu</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('votes') }};">
                            <li><a href="{{ route('votes.index') }}" class="{{ routeActive('votes.index') }}">Data
                                    Pemilu</a></li>
                            <li><a href="{{ route('votes.create') }}" class="{{ routeActive('votes.create') }}">Buat
                                    Pemilu</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('tickets') }}" href="javascript:void(0)">
                            <i data-feather="message-circle"></i><span>Pesan</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('tickets') }};">
                            <li><a href="{{ route('tickets.index') }}"
                                    class="{{ routeActive('tickets.index') }}">Data Pesan</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title {{ prefixActive('members') }}" href="javascript:void(0)">
                            <i data-feather="user-plus"></i><span>Member</span>
                        </a>
                        <ul class="nav-submenu menu-content" style="display: {{ prefixBlock('members') }};">
                            <li><a href="{{ route('members.index') }}"
                                    class="{{ routeActive('members.index') }}">Data Member</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav {{ routeActive('vision.edit') }}"
                            href="{{ route('vision.edit') }}">
                            <i data-feather="target"></i><span>Visi Misi</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav {{ routeActive('profile.index') }}"
                            href="{{ route('profile.index') }}">
                            <i data-feather="user"></i><span>Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
