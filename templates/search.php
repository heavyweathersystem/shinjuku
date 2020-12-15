<?php include('header.php')?>
    <form class="form-inline" action="<?php echo SHINJUKU_URL; ?>/includes/api.php" method="get">
                <input type="hidden" name="do" value="fetch">
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input id="date" type="date" name="date" data-date-format="YYYY-MM-DD" value="<?php if (empty($date)) {
                        echo date('Y-m-d');
} else {
    echo $date;
}?>">
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input id="amount" type="number" name="count" value="30">
                </div>

                <div class="form-group">
                    <label for="amount">Keyword:</label>
                    <input type="text" name="keyword">
                </div>
                <input class="btn btn-default" type="submit" value="fetch">
            </form>
            <br>
            <table id="result" class="table">
                <tr>
                    <th>ID</th>
                    <th>Orginal Name</th>
                    <th>Filename</th>
                    <th>Size (bytes)</th>
                    <th>Action</th>
                </tr>

