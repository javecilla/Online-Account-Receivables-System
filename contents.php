<?php require_once __DIR__ . '/app/includes/layouts/begin.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/sidebar.php' ?>
<?php require_once __DIR__ . '/app/includes/layouts/header.php' ?>
<?php // require_once __DIR__ . '/app/includes/layouts/breadcrumb.php' 
?>

<!-- Main Content -->
<main class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="border-bottom mb-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h4 class="module-title">Content Management: </h4>
                                        <div class="tableContainerContent">
                                            <!-- <button class="btn action-btn" id="aboutUsBtn">
                                                <i class="fas fa-info-circle me-2"></i> About Us
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="formContainerContent">
                                <div id="tabContainerContent" style="overflow-x: hidden!important;">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs content-tabs mb-3" id="contentContentTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="content-contactus-tab" data-bs-toggle="tab" data-bs-target="#content-contactus" type="button" role="tab" aria-controls="content-contactus" aria-selected="false">
                                                <i class="fas fa-phone me-2"></i> Contact Us
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="content-aboutus-tab" data-bs-toggle="tab" data-bs-target="#content-aboutus" type="button" role="tab" aria-controls="content-aboutus" aria-selected="false">
                                                <i class="fas fa-info-circle me-2"></i> About Us
                                            </button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="contentTabsContent">
                                        <div class="tab-pane fade show active" id="content-contactus" role="tabpanel" aria-labelledby="content-contactus-tab">
                                            <div class="row g-4">
                                                <!-- Map Column Preview -->
                                                <div class="col-lg-6 mb-4 mb-lg-0">
                                                    <div class="card h-100 shadow-sm">
                                                        <div class="card-body p-0">
                                                        <div id="contact-map" style="height: 600px; width: 100%;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- fForm -->
                                                <div class="col-lg-6">
                                                    <form action="#" class="row">
                                                        <div class="row mb-3">
                                                            <label for="latitude" class="col-sm-3 col-form-label">Latitude:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="latitude" readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="longtitude" class="col-sm-3 col-form-label">Longtitude:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="longtitude" readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="popup" class="col-sm-3 col-form-label">Popup Details:</label>
                                                            <div class="col-sm-9">
                                                                <textarea class="form-control" id="popup" rows="15"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"></label>
                                                            <div class="col-sm-9">
                                                                <button type="button" class="btn action-btn w-100" id="updateContactMapBtn">Save Changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="content-aboutus" role="tabpanel" aria-labelledby="content-aboutus-tab">
                                            <div class="row g-4">
                                                <!-- content preview -->
                                                <div class="col-md-6">
                                                    <h1 class="fw-bold mb-4">Empowering Members. Building Futures.</h1>
                                                    <p class="lead mb-4">
                                                        Our cooperative system is more than just a tool — it’s a gateway to financial empowerment and inclusive growth. 
                                                        Whether you're a long-time member or new to the cooperative world, our platform is designed to support your journey 
                                                        toward financial freedom with ease, transparency, and security.
                                                    </p>

                                                    <ul class="list-unstyled">
                                                        <li class="mb-3">
                                                        <i class="fas fa-user-shield me-2"></i>
                                                        Seamless and secure member onboarding process
                                                        </li>
                                                        <li class="mb-3">
                                                        <i class="fas fa-bolt me-2"></i>
                                                        Fast loan processing with real-time status updates
                                                        </li>
                                                        <li class="mb-3">
                                                        <i class="fas fa-chart-line me-2"></i>
                                                        Transparent financial tracking and instant reports
                                                        </li>
                                                        <li class="mb-3">
                                                        <i class="fas fa-hand-holding-usd me-2"></i>
                                                        Member-focused benefits and growing community support
                                                        </li>
                                                    </ul>
                                                </div>

                                                <!-- Form -->
                                                <div class="col-md-6">
                                                    <form action="#" class="row">
                                                        <div class="row mb-3">
                                                            <label for="titleAboutUs" class="col-sm-3 col-form-label">Title:</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control" id="titleAboutUs" />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="descriptionAboutUs" class="col-sm-3 col-form-label">Description:</label>
                                                            <div class="col-sm-9">
                                                                <textarea class="form-control" id="descriptionAboutUs" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="featuredAboutUs" class="col-sm-3 col-form-label">Featured:</label>
                                                            <div class="col-sm-9">
                                                                <textarea class="form-control" id="featuredAboutUs" rows="10"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label class="col-sm-3 col-form-label"></label>
                                                            <div class="col-sm-9">
                                                                <button type="button" class="btn action-btn w-100" id="updateAboutUsBtn">Save Changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php //require_once __DIR__ . '/app/includes/layouts/footer.php' 
?>
<?php require_once __DIR__ . '/app/includes/layouts/end.php' ?>