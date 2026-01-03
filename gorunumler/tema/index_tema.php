<!-- main-sec6 -->
<section class="main-sec6">
    <canvas id="fluid-canvas"></canvas>
    <!-- hero-style15 -->
    <div class="hero-style15">
        <div class="container">
            <div class="banner-content15">
                <img src="/assets/images/logo5.svg" alt="AI Agency & Technology HTML Template">
                <h1 class="title animated-heading">Meet Powerfull AI Agency & Technology HTML Template</h1>
                <a href="#demo" class="ibt-btn ibt-btn-secondary">
                    <span>See Demo</span>
                    <i class="icon-arrow-top"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- End hero-style15 -->

    <!-- service-sec23 -->
    <div class="service-sec23">
        <div class="container2">
            <div class="row">
                <div class="col-xl-5 col-lg-12">
                    <div class="ser-card23-card1 ser-anim">
                        <div class="funfact-content23">
                            <div class="counter-box23">
                                <span class="counter-number percent-counter" data-target="300">0</span>
                                <span class="counter-text">+</span>
                            </div>
                            <h4 class="title">Awesome collection <br>of different Block & Sections</h4>
                            <p>Featuring an extensive collection of blocks and ready-to-use pages, you’ll have all the
                                tools to build a standout, unforgettable website.</p>
                        </div>
                    </div>
                </div>
                <!-- ... diger icerikler ... -->
            </div>
        </div>
    </div>
</section>

<!-- Demo Section -->
<section id="demo" class="demo-sec">
    <div class="container2">
        <div class="title-area3">
            <h4 class="title">Meet incredable <br>& creative demo pages</h4>
        </div>
        <div class="row">
            <?php foreach ($temalar as $t): ?>
                <div class="col-lg-6">
                    <div class="demo-img">
                        <a href="/?tema=<?php echo $t['tema_kodu']; ?>"><img
                                src="/assets/images/event/demo1-<?php echo str_replace('index', '', $t['tema_kodu']); ?>.png"
                                alt="<?php echo $t['ad']; ?>"></a>
                        <div class="demo-hover">
                            <h4 class="demo-title">
                                <?php echo $t['ad']; ?>
                            </h4>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>