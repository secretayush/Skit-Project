<!-- Sidebar Menu -->
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
    <li class="nav-item">
      <a href="/admin" class="nav-link">
        <i class="nav-icon fas fa-th"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>
    <?php if (unserialize($_SESSION['user'])->title == 'admin'): ?>
      <li class="nav-item">
        <a href="/admin/createModerator" class="nav-link">
          <i class="nav-icon fas fa-plus-circle"></i>
          <p>
            Create Moderator
          </p>
        </a>
      </li>
    <?php endif ?>
    <li class="nav-item">
      <a href="/admin/companies" class="nav-link">
        <i class="nav-icon fas fa-list"></i>
        <p>
          Company Data
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="/admin/students" class="nav-link">
        <i class="nav-icon fas fa-list"></i>
        <p>
          Student Data
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="/admin/jobs" class="nav-link">
        <i class="nav-icon fas fa-tasks"></i>
        <p>
          Jobs Posted
        </p>
      </a>
    </li>
  </ul>
</nav>
<!-- /.sidebar-menu -->
