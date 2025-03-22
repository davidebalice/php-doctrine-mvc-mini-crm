<ul class="tabs">
    <a href="/leads/detail/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'detail') ? 'active' : '' ?>" id="tab-detail" onclick="showTab('detail')">Detail</li>
    </a>
    <a href="/leads/history/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'history') ? 'active' : '' ?>" id="tab-history" onclick="showTab('history')">History</li>
    </a>
    <a href="/leads/notes/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'notes') ? 'active' : '' ?>" id="tab-note" onclick="showTab('note')">Notes</li>
    </a>
    <a href="/leads/calls/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'calls') ? 'active' : '' ?>" id="tab-call" onclick="showTab('call')">Calls</li>
    </a>
    <a href="/leads/tasks/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'tasks') ? 'active' : '' ?>" id="tab-task" onclick="showTab('task')">Tasks</li>
    </a>
    <a href="/leads/quotations/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentPage, 'quotations') ? 'active' : '' ?>" id="tab-quotation" onclick="showTab('quotation')">Quotations</li>
    </a>
</ul>