<head>
    <script src="https://kit.fontawesome.com/3b161c540c.js" crossorigin="anonymous"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        :root {
            --clr-bg-header: #3D8361;
            --clr-btn: #3D8361;
            --clr-dropdown: #1C6758;
            --clr-nav-hover: #1E6F5C;
            --clr-dropdown-hov: #289672;
            --clr-dropdown-link-hov: #29BB89;
            --clr-light: #FAFAFA;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        ul {
            list-style: none;
        }

        a {
            text-decoration: none;
        }

        header {
            position: sticky;
            top: 0px;
            background-color: var(--clr-bg-header);
            width: 100%;
            z-index: 1000;
        }

        section {
            position: relative;
            height: calc(100vh - 3rem);
            width: 100%;
            background: url("https://i.postimg.cc/TPn6kNJ2/bg.jpg") no-repeat top center / cover;
            overflow: hidden;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(30, 130, 95, 0.5);
        }

        .container {
            max-width: 65rem;
            padding: 0 2rem;
            margin: 0 auto;
            display: flex;
            position: relative;
        }

        .logo-container {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .nav-btn {
            flex: 3;
            display: flex;
        }

        .nav-links {
            flex: 2;
        }

        .logo {
            line-height: 3rem;
        }

        /* .btn {
            display: inline-block;
            padding: .5rem 1.3rem;
            font-size: .8rem;
            border: 2px solid var(--clr-light);
            border-radius: 2rem;
            line-height: 1;
            margin: 0 .2rem;
            transition: .3s;
            text-transform: uppercase;
        }

        .btn.solid,
        .btn.transparent:hover {
            background-color: var(--clr-light);
            color: var(--clr-btn);
        }

        .btn.transparent,
        .btn.solid:hover {
            background-color: transparent;
            color: var(--clr-light);
        } */

        .nav-links>ul {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .nav-link {
            position: relative;
        }

        .nav-link>a {
            line-height: 3rem;
            color: var(--clr-light);
            padding: 0 .8rem;
            /* letter-spacing: 1px; */
            /* font-size: .95rem; */
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: .5s;
        }

        .nav-link>a>i {
            margin-left: .2rem;
        }

        .nav-link:hover>a {
            transform: scale(1.1);
        }

        .dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            width: 10rem;
            transform: translateY(10px);
            opacity: 0;
            pointer-events: none;
            transition: .5s;
        }

        .dropdown ul {
            position: relative;
        }

        .dropdown-link>a {
            display: flex;
            background-color: var(--clr-light);
            color: var(--clr-dropdown);
            padding: .5rem 1rem;
            font-size: .9rem;
            align-items: center;
            justify-content: space-between;
            transition: .3s;
        }

        .dropdown-link:hover>a {
            background-color: var(--clr-dropdown);
            color: var(--clr-light);
        }

        .dropdown-link:not(:nth-last-child(2)) {
            border-bottom: 1px solid var(--clr-light);
        }

        .dropdown-link i {
            transform: rotate(-90deg);
        }

        .arrow {
            position: absolute;
            width: 11px;
            height: 11px;
            top: -5.5px;
            left: 32px;
            background-color: var(--clr-light);
            transform: rotate(45deg);
            cursor: pointer;
            transition: .3s;
            z-index: -1;
        }

        .dropdown-link:first-child:hover~.arrow {
            background-color: var(--clr-dropdown);
        }

        .dropdown-link {
            position: relative;
        }

        .dropdown.second {
            top: 0;
            left: 100%;
            padding-left: .8rem;
            cursor: pointer;
            transform: translateX(10px);
        }

        .dropdown.second .arrow {
            top: 10px;
            left: -5.5px;
        }

        .nav-link:hover>.dropdown,
        .dropdown-link:hover>.dropdown {
            transform: translate(0, 0);
            opacity: 1;
            pointer-events: auto;
        }

        .hamburger-menu-container {
            flex: 1;
            display: none;
            align-items: center;
            justify-content: flex-end;
        }

        .hamburger-menu {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .hamburger-menu div {
            width: 1.6rem;
            height: 3px;
            border-radius: 3px;
            background-color: var(--clr-light);
            position: relative;
            z-index: 1001;
            transition: .5s;
        }

        .hamburger-menu div:before,
        .hamburger-menu div:after {
            content: '';
            position: absolute;
            width: inherit;
            height: inherit;
            background-color: var(--clr-light);
            border-radius: 3px;
            transition: .5s;
        }

        .hamburger-menu div:before {
            transform: translateY(-7px);
        }

        .hamburger-menu div:after {
            transform: translateY(7px);
        }

        #check {
            position: absolute;
            top: 50%;
            right: 1.5rem;
            transform: translateY(-50%);
            width: 2.5rem;
            height: 2.5rem;
            z-index: 90000;
            cursor: pointer;
            opacity: 0;
            display: none;
        }

        #check:checked~.hamburger-menu-container .hamburger-menu div {
            background-color: transparent;
        }

        #check:checked~.hamburger-menu-container .hamburger-menu div:before {
            transform: translateY(0) rotate(-45deg);
        }

        #check:checked~.hamburger-menu-container .hamburger-menu div:after {
            transform: translateY(0) rotate(45deg);
        }

        @keyframes animation {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0px);
            }
        }

        @media (max-width: 920px) {
            .hamburger-menu-container {
                display: flex;
            }

            #check {
                display: block;
            }

            .nav-btn {
                position: fixed;
                height: calc(100vh - 3rem);
                top: 3rem;
                left: 0;
                width: 100%;
                background-color: var(--clr-btn);
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                overflow-x: hidden;
                overflow-y: auto;
                transform: translateX(100%);
                transition: .65s;
            }

            #check:checked~.nav-btn {
                transform: translateX(0);
            }

            #check:checked~.nav-btn .nav-link,
            #check:checked~.nav-btn .log-sign {
                animation: animation .5s ease forwards var(--i);
            }

            .nav-links {
                flex: initial;
                width: 100%;
            }

            .nav-links>ul {
                flex-direction: column;
            }

            .nav-link {
                width: 100%;
                opacity: 0;
                transform: translateY(15px);
            }

            .nav-link>a {
                line-height: 1;
                padding: 1.6rem 2rem;
            }

            .nav-link:hover>a {
                transform: scale(1);
                background-color: var(--clr-nav-hover);
            }

            .dropdown,
            .dropdown.second {
                position: initial;
                top: initial;
                left: initial;
                transform: initial;
                opacity: 1;
                pointer-events: auto;
                width: 100%;
                padding: 0;
                background-color: var(--clr-dropdown-hov);
                display: none;
            }

            .nav-link:hover>.dropdown,
            .dropdown-link:hover>.dropdown {
                display: block;
            }

            .nav-link:hover>a>i,
            .dropdown-link:hover>a>i {
                transform: rotate(360deg);
            }

            .dropdown-link>a {
                background-color: transparent;
                color: var(--clr-light);
                padding: 1.2rem 2rem;
                line-height: 1;
            }

            .dropdown.second .dropdown-link>a {
                padding: 1.2rem 2rem 1.2rem 3rem;
            }

            .dropdown.second .dropdown.second .dropdown-link>a {
                padding: 1.2rem 2rem 1.2rem 4rem;
            }

            .dropdown-link:not(:nth-last-child(2)) {
                border-bottom: none;
            }

            .arrow {
                z-index: 1;
                background-color: var(--clr-btn);
                left: 10%;
                transform: scale(1.1) rotate(45deg);
                transition: .5s;
            }

            .nav-link:hover .arrow {
                background-color: var(--clr-nav-hover);
            }

            .dropdown .dropdown .arrow {
                display: none;
            }

            .dropdown-link:hover>a {
                background-color: var(--clr-dropdown-link-hov);
            }

            .dropdown-link:first-child:hover~.arrow {
                background-color: var(--clr-nav-hover);
            }

            .nav-link>a>i {
                font-size: 1.1rem;
                transform: rotate(-90deg);
                transition: .7s;
            }

            .dropdown i {
                font-size: 1rem;
                transition: .7s;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <input type="checkbox" name="check" id="check">
            <div class="logo-container">
                <h3 class="logo">On<span>time</span></h3>
            </div>

            <div class="nav-btn">
                <div class="nav-links">
                    <ul>
                        <li class="nav-link" style="--i: .6s">
                            <a href="tasks.php">Tasks</a>
                        </li>
                        <li class="nav-link" style="--i: .85s">
                            <a href="#">User<i class="fas fa-caret-down"></i></a>
                            <div class="dropdown">
                                <ul>
                                    <li class="dropdown-link">
                                        <a href="#">Profile</a>
                                    </li>
                                    <li class="dropdown-link">
                                        <a href="#">Sign Out</a>
                                    </li>
                                    <div class="arrow"></div>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="hamburger-menu-container">
                <div class="hamburger-menu">
                    <div></div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section>
            <div class="overlay"></div>
        </section>
    </main>
</body>