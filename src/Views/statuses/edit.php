<div class="page">
    <div class="page-header">
        <div class="page-header-container">
            <div class="page-wrapper">
                <i class="fa fa-bars" aria-hidden="true" style="font-size:22px"></i>
                <h1>Edit status</h1>
            </div>
        </div>
    </div>
    <div class="page-subheader">
        <a href="/statuses">
            <div class="button">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
                <span>Back</span>
            </div>
        </a>
    </div>
    <div class="page-body">
        <form action="/statuses/update" method="post" class="form" id="status-form">
            <label>Name of status:</label>
            <input type="text" name="name" class="input-form" data-mandatory="true" required value="<?php echo $status->getName(); ?>">
            <input type="hidden" name="id" value="<?php echo $status->getId(); ?>">
            <input type="submit" class="input-submit" value="Save">
            <p id="error-message" style="color: red; display: none;"></p>
        </form>
    </div>
</div>