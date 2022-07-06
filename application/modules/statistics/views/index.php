<div class="mb-4">
    <?= $this->breadcrumbs->show();

    ?>
</div>
<div class="row">

    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>

            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 pointer-link" id="productStat">
        <div class="card border-left-primary shadow h-100 py-2 ">
            <div class="card-body">
                <div class="row no-gutters align-items-center ">
                    <div class="col mr-2">
                        <div class="text-xs text-color font-weight-bold text-uppercase mb-1">Product Statistics </div>
                    </div>
                    <div class="col-auto text-color fnt-size">
                        <i class="fa-brands fa-product-hunt text-black-100"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#productStat").click(function() {
        window.location = "<?= admin_url('statistics/product_list'); ?>";
        return false;
    });
</script>