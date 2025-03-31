
<div class="dashboard-container">
    <div class="dashboard">
        <div class="dashboard-box1">
            <img src="/images/phpLogo.png" alt="Logo" class="phpLogo">
            <div>
                <div class="line"></div>
                <h3>Mini Crm - Minimal PHP Framework custom</h3>
                Mini Crm developed with <b>PHP</b> using <b>Doctrine</b> as ORM 
                <br />
                for data management and <b>FastRoute</b> for API management.
                <br />
                The goal is to create a completely customized solution 
                <br />
                without relying on existing frameworks.
                <br />
                The project is developed following the principles 
                <br />
                of <b>OOP</b> and <b>MVC</b> architecture.
            </div>
        </div>

        <div class="dashboard-box1">
            <img src="/images/alert.png" alt="alert" class="alert">
            <br />
            <b class="demo-mode">DEMO MODE</b>
            <br />
            This app is in Demo Mode
            <br />
            Crud operations are not allowed
            <?php
            if(isset($user)){
                echo $user->getEmail();
            }else{
                echo"user non loggato";
            }
        ?>
        </div>
    </div>

    <div class="dashboard">
        <div class="dashboard-box2">
            <div class="dashboard-icon-container dashboard-icon1">
                <i class="fa-regular fa-address-card"></i>
            </div>
            <div>
                <p>Total leads:</p>
                <?php echo $leads; ?>
            </div>
        </div>

        <div class="dashboard-box2">
            <div class="dashboard-icon-container dashboard-icon2">
                <i class="fa-regular fa-address-card"></i>
            </div>
            <div>
                <p>Active leads:</p>
                <?php echo $leads_active; ?>
            </div>
        </div>

        <div class="dashboard-box2">
            <div class="dashboard-icon-container dashboard-icon3">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <p>Pending tasks:</p>
                <?php echo $tasks_pending; ?>
            </div>
        </div>

        <div class="dashboard-box2">
            <div class="dashboard-icon-container dashboard-icon4">
                <i class="fa-regular fa-file-lines"></i>
            </div>
            <div>
                <p>Documents:</p>
                <?php echo $documents; ?>
            </div>
        </div>
    </div>

    <div class="dashboard">
        <div class="dashboard-box3">
            <div class="dashboard-box-int">
                <div class="line"></div>
                <h3>aaaaaaaaaa</h3>
             
            </div>
        </div>

        <div class="dashboard-box3">
            <div class="dashboard-box-int">
                <div class="line"></div>
                <h3>aaaaaaaaaa</h3>
             
            </div>
        </div>
    </div>
</div>
