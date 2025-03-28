<div class="lead-box">
    <b>Lead:</b> 
    <br />
    <?= $lead->getLastName()?> <?= $lead->getFirstName()?>
</div>

<div class="lead-box">
    <b>Email:</b> 
    <br />
    <i><?= $lead->getEmail()?></i>
</div>

<div class="lead-box">
    <b>Phone:</b> 
    <br />
    <i><?= $lead->getPhone()?></i>
</div>