
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

    echo"<br>";
            echo $leads;
    echo"<br>";
            echo $leads_active;
        ?>


        </div>
    </div>

    <div class="dashboard">

        <div class="dashboard-box2">aa</div>
        <div class="dashboard-box2">aa</div>
        <div class="dashboard-box2">aa</div>
        <div class="dashboard-box2">aa</div>

      

    </div>
</div>
