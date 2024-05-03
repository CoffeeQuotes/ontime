<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <?= $page_title ?? 'Dashboard' ?>
                </h2>
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    <?= $page_desc ?? 'Welcome Ontime' ?>
                </div>
            </div>
            <?php if (isset($page_target_modal) && $page_target_modal != ''): ?>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <button type="button" class="btn btn-success btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="<?= $page_target_modal ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-hierarchy-3">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M8 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M12 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M20 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M4 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M16 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M5 17l2 -3" />
                                <path d="M9 10l2 -3" />
                                <path d="M13 7l2 3" />
                                <path d="M17 14l2 3" />
                                <path d="M15 14l-2 3" />
                                <path d="M9 14l2 3" />
                            </svg>
                            Create a team
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>