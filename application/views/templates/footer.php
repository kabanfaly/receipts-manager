<?php if (isset($_SESSION['user'])) : ?>
    </div>
    <!-- /.content clearfix-->
    </div>
<?php endif; ?>
<!-- ./row -->
</div>
<!-- /.container-fluid-->
</div>
<!-- /#page-wrapper -->
</div>
<?php if (isset($_SESSION['user']) && isset($_SESSION['parameters'])) : ?>
    <footer>
        <div id="footer" class="panel-footer">
            <div class="row">
                <div class="col-lg-12">
                    <h5><?php echo $_SESSION['parameters']['COMPANY']; ?></h5>
                    <small>
                        <p>
                            <i class="fa fa-fw fa-home"></i>
    <?php echo $_SESSION['parameters']['ADDRESS']; ?> 
                            <br>
                            <i class="fa fa-fw fa-envelope"></i>
    <?php echo $_SESSION['parameters']['EMAIL']; ?> 
                            <br>
                            <i class="fa fa-fw fa-phone"></i>
    <?php echo $_SESSION['parameters']['PHONE']; ?> 
                            <br>

                        </p> 
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <p>
                        <small><?php echo lang('COPYRIGHT') . $_SESSION['parameters']['COMPANY']; ?> </small>
                    </p>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>
<!-- /#wrapper -->
</div>


<!-- Modal -->
<div id="form-content" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="form-content" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
        </div>
    </div>
</div>

<?php
$url = base_url();
echo <<<EOF
            <script type="text/javascript">
                var baseUrl = "{$url}";
            </script>
EOF;
?>
<script type="text/javascript" data-main="<?php echo base_url(); ?>js/main.js?<?php echo time(); ?>" src="<?php echo base_url(); ?>assets/requirejs/require.js"></script>
</body>
</html>
