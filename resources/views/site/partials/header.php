<header class="header" id="header">
    <nav class="navbar container">
        <a href="/" class="brand">Brand</a>
        <div class="burger" id="burger">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </div>
        <div class="menu" id="menu">
            <ul class="menu-inner">
                <li class="menu-item"><a href="/" class="menu-link">Home</a></li>
                <?php if (Auth::guest()): ?>
                    <li class="menu-item">
                        <a href="<?php echo route('login'); ?>" class="menu-link">
                            <div class="icon-wrap icon-xs bg-primary round text-white"><i class="fa fa-user"></i></div>
                            <div class="text-wrap"><span>Login</span></div>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="menu-item">
                        <a id="navbarDropdown" class="menu-link" href="<?php echo route('admin.blogs.index'); ?>" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <?php echo Auth::user()->name; ?> <span class="caret"></span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?php echo route('logout'); ?>" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <?php echo __('Logout'); ?>
                            </a>
                            <form id="logout-form" action="<?php echo route('logout'); ?>" method="POST"
                                style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<script>
    const navbarMenu = document.getElementById("menu");
    const burgerMenu = document.getElementById("burger");
    const headerMenu = document.getElementById("header");

    // Open Close Navbar Menu on Click Burger
    if (burgerMenu && navbarMenu) {
        burgerMenu.addEventListener("click", () => {
            burgerMenu.classList.toggle("is-active");
            navbarMenu.classList.toggle("is-active");
        });
    }

    // Close Navbar Menu on Click Menu Links
    document.querySelectorAll(".menu-link").forEach((link) => {
        link.addEventListener("click", () => {
            burgerMenu.classList.remove("is-active");
            navbarMenu.classList.remove("is-active");
        });
    });

    // Change Header Background on Scrolling
    window.addEventListener("scroll", () => {
        if (this.scrollY >= 85) {
            headerMenu.classList.add("on-scroll");
        } else {
            headerMenu.classList.remove("on-scroll");
        }
    });

    // Fixed Navbar Menu on Window Resize
    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            if (navbarMenu.classList.contains("is-active")) {
                navbarMenu.classList.remove("is-active");
            }
        }
    });
</script>