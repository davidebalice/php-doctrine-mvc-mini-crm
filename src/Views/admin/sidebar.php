<div class="sidebar">
    <div class="sidebar-container">
        <div class="sidebar-header">
            <div class="sidebar-img">
                <img src="/images/mariorossi.jpg">
            </div>
            <div class="sidebar-name">
                <?= $user->getName();?>
                <br />
                <span class="sidebar-email">
                    <?= $user->getEmail();?>
                </span>
            </div>
        </div>

        <ul>
            <a href="/dashboard">
                <li>
                    <i class="fa-solid fa-bars"></i>
                    Dashboard
                </li>
            </a>
            <a href="/leads">
                <li>
                    <i class="fa-regular fa-address-card"></i>
                    Leads
                </li>
            </a>
            <!--a href="/quotations">
                <li>
                <i class="fa-regular fa-file-lines"></i>
                    <?= __('Quotations') ?>
                </li>
            </a-->
            <a href="/sources">
                <li>
                    <i class="fa-solid fa-list"></i>
                    Sources
                </li>
            </a>
            <a href="/statuses">
                <li>
                    <i class="fa-solid fa-list"></i>
                    Statuses
                </li>
            </a>

            <!--a href="/profile">
                <li>
                    <i class="fa-regular fa-circle-user"></i>
                    Users
                </li>
            </a-->


            <a href="#">
                <li>
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </li>
            </a>

            <a href="/logout">
                <li>
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </li>
            </a>

        </ul>
    </div>
</div>