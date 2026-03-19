<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">KiraStore</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Catalogue -->
    <div class="sidebar-heading">Catalogue</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Catégories</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('produits.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Produits</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Ventes -->
    <div class="sidebar-heading">Ventes</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('ventes.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Ventes</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Clients</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Stock -->
    <div class="sidebar-heading">Stock</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('mvtstock.index') }}">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span>Mouvements</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Finance -->
    <div class="sidebar-heading">Finance</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('depenses.index') }}">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Dépenses</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('resumes.index') }}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Résumé journalier</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Administration -->
    <div class="sidebar-heading">Administration</div>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-user-cog"></i>
            <span>Utilisateurs</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('roles.index') }}">
            <i class="fas fa-fw fa-shield-alt"></i>
            <span>Rôles</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <!-- Compte -->
    <div class="sidebar-heading">Compte</div>

    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-left">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </form>
    </li>

</ul>
<!-- End of Sidebar -->
