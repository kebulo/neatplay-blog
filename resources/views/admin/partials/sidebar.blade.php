<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="nav-item">
                <a href="{{ route('admin.blogs.index') }}" class="nav-link align-middle px-0 text-white">
                    <i class="fa-solid fa-book-open"></i> <span class="ms-1 d-none d-sm-inline">Articles</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="nav-link px-0 align-middle text-white">
                    <i class="fa-solid fa-list"></i> <span class="ms-1 d-none d-sm-inline">Categories</span>
                </a>
            </li>
        </ul>
    </div>
</div>