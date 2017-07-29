 </form>
        <?php
        $url = base_url();
        echo <<<EOF
            <script type="text/javascript">
                var baseUrl = "{$url}";
            </script>
EOF;
        ?>
    </body>
     <script>
        $(function () {
            $('#datetimepicker').datetimepicker({
                lang: 'fr',
                format: 'd/m/Y',
                timepicker: false
            });
        });
    </script>
    <script type="text/javascript" data-main="<?php echo base_url(); ?>js/main?<?php echo time(); ?>" src="<?php echo base_url(); ?>assets/requirejs/require.js"></script>
</html>
