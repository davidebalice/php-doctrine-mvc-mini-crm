<ul class="tabs">
    <a href="/leads/detail/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'detail') ? 'active' : '' ?>" id="tab-detail" onclick="showTab('detail')">Detail</li>
    </a>
    <a href="/leads/history/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'history') ? 'active' : '' ?>" id="tab-history" onclick="showTab('history')">History</li>
    </a>
    <a href="/leads/notes/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'notes') ? 'active' : '' ?>" id="tab-note" onclick="showTab('note')">Notes</li>
    </a>
    <a href="/leads/calls/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'calls') ? 'active' : '' ?>" id="tab-call" onclick="showTab('call')">Calls</li>
    </a>
    <a href="/leads/tasks/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'tasks') ? 'active' : '' ?>" id="tab-task" onclick="showTab('task')">Tasks</li>
    </a>
    <a href="/leads/quotations/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'quotations') ? 'active' : '' ?>" id="tab-quotation" onclick="showTab('quotation')">Quotations</li>
    </a>
    <a href="/leads/documents/<?= $lead->getId();?>">
        <li class="tab <?= str_contains($currentTab, 'documents') ? 'active' : '' ?>" id="tab-document" onclick="showTab('document')">Documents</li>
    </a>
</ul>