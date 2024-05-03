<?php include 'partials/head.php' ?>
<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTeamModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
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
<div class="modal modal-blur fade" id="createTeamModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Test Content
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="Close"></button>
            </div>
            <form id="createTeamForm" action="add-team.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-3 align-items-end">
                        <div class="col-auto">
                            <label for="file-upload" class="avatar avatar-upload rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                <span class="avatar-upload-text">Add</span>
                            </label>
                            <input id="file-upload" name="logo" type="file" style="display: none;" />
                        </div>

                        <div class="col">
                            <label class="form-label">
                                Team name
                            </label>
                            <input type="text" name="team_name" id="team_name" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pick your team color</label>
                        <div class="row g-2">
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="dark" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-dark"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput form-colorinput-light">
                                    <input name="color" type="radio" value="white" class="form-colorinput-input"
                                        checked />
                                    <span class="form-colorinput-color bg-white"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="blue" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-blue"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="azure" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-azure"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="indigo" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-indigo"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="purple" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-purple"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="pink" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-pink"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="red" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-red"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="orange" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-orange"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="yellow" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-yellow"></span>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-colorinput">
                                    <input name="color" type="radio" value="lime" class="form-colorinput-input" />
                                    <span class="form-colorinput-color bg-lime"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Additional info</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="team" class="btn btn-primary">Add Team</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'partials/footer.php' ?>
<script>
    document.getElementById('file-upload').addEventListener('change', function () {
        var fileInput = document.getElementById('file-upload');
        var fileName = document.querySelector('.avatar-upload-text');
        fileName.textContent = fileInput.files[0].name;
    });
</script>