
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
            <br />
            Leads data are placeholder 
            <br />
            and generate with Php Faker
        </div>
    </div>

    <div class="dashboard">
        <div class="dashboard-box2">
            <div class="dashboard-icon-container dashboard-icon1">
                <i class="fa-regular fa-address-card"></i>
            </div>
            <div>
                <p>Total leads:</p>
                <?php echo $total_leads; ?>
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
                <h3>Last leads</h3>

                <?php if (isset($leads) && count($leads) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                            <th style="width:30%">Surname name</th>
                            <th style="width:15%">Status</th>
                            <th style="width:15%">Source</th>
                            <th style="width:15%">Assigned user</th>
                            <th style="width:15%">Data</th>
                            <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leads as $lead): ?>
                                <tr>
                                    <td><?= $lead->getLastName() ?> <?= $lead->getFirstName() ?></td>
                                    <td><?= $lead->getStatus()->getName() ?></td>
                                    <td><?= $lead->getSource()->getName() ?></td>
                                    <td><?= $lead->getAssignedUser()->getName() ?></td>
                                    <td><?= $lead->getCreatedAt()->format('d/m/Y H:i')  ?></td>
                                    <td>
                                        <div class="buttons-container">
                                            <a href="/leads/detail/<?php echo $lead->getId(); ?>">
                                                <div class="flex-center base-button detail-button ">
                                                    <i class="fa-regular fa-address-card"></i>
                                                    Detail
                                                </div>
                                            </a>
                                        
                                            <a href="/leads/edit/<?php echo $lead->getId(); ?>">
                                                <div class="flex-center base-button edit-button ">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </div>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
             
                    <?php else: ?>
                        <br />
                        <b>Leads not found</b>
                    <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-box3">
            <div class="dashboard-box-int">
                <div class="line"></div>
                <h3>Recent activity</h3>

                <?php if (isset($histories) && count($histories) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th style="width:20%">Date</th>
                                <th style="width:80%">Event</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($histories as $history): ?>
                                <tr>
                                    <td><?= $history->getCreatedAt()->format('d/m/Y H:i') ?></td>
                                    <td><?= $history->getEvent() ?></td>
                                    <td>
                                        <div class="buttons-container">
                                            <a href="/leads/history/edit/<?php echo $history->getId(); ?>">
                                                <div class="flex-center base-button edit-button ">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </div>
                                            </a>
                                            <div class="flex-center base-button delete-button"  onclick="confirmDelete(<?php echo $history->getId(); ?>)">
                                                <i class="fa-solid fa-trash"></i>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
             
                    <?php else: ?>
                        <br />
                        <b>Leads not found</b>
                    <?php endif; ?>
             
            </div>
        </div>
    </div>
</div>
