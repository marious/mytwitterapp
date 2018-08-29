</div><!-- ./ row -->
</div><!-- ./container -->


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php if (isset($assets) && isset($assets['js']) && count($assets['js'])): ?>
    <?php foreach ($assets['js'] as $js): ?>
        <script src="<?php echo URL_ROOT . 'assets/js/' . $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<script>
    $(document).on('click', '.delete-img, .delete-btn', function(e) {
        e.preventDefault();
        if (confirm('هل انت متاكد من انك تريد حذف هذا العنصر؟')) {
            window.location.href = this.getAttribute('href');
        }
    });
    <?php if (isset($assets) && isset($assets['custom_script_date']) && ($assets['custom_script_date']) == true): ?>
    $(function () {
        $('.datetimepicker').datetimepicker({
            format: 'LT'
        });
    });
    <?php endif; ?>
    <?php if (isset($assets) && isset($assets['custom_script_date']) && ($assets['custom_script_date']) == true): ?>
    $(function () {
        $('.date-time').datetimepicker({
            format: 'DD-MM-YYYY h:mm a',
            sideBySide: true
        });
    });
    <?php endif; ?>
</script>
<script src="<?php echo URL_ROOT . 'assets/js/common.js'; ?>"></script>

<?php if (! isset($edit_script)): ?>
</body>
</html>
<?php endif; ?>