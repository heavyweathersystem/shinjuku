<?php include('header.php')?>
    <form  action="<?php echo SHINJUKU_URL; ?>/includes/api.php" method="get">
                <input type="hidden" name="do" value="report">
                <div class="form-group">
                    <label for="file">Filename</label>
                    <input id="file" type="text" name="file">
                </div>

                <input class="btn btn-default" type="submit" value="report">
            </form>
<?php include('footer.php');?>
