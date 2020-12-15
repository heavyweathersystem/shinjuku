<?php include('header.php')?>
            <form action="<?php echo SHINJUKU_URL; ?>/includes/api.php" method="get">
                <input type="hidden" name="do" value="invite">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="level">Level:</label>
                    <input id="level" type="number" name="number" value="0">
                </div>
                <input class="btn btn-default" type="submit" value="invite">

            </form>
        </div>
<?php include('footer.php');?>
