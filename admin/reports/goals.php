<style>
    table td, table th {
        padding: 3px !important;
    }
</style>

<?php 
// Set default date range
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : date("Y-m-d", strtotime("-7 days"));
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : date("Y-m-d");
$user = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'")->fetch_assoc();
$user_id = $user['id'];

// Escape date inputs
$date_start_esc = $conn->real_escape_string($date_start); 
$date_end_esc = $conn->real_escape_string($date_end);
?>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="card-title">Goals Report</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <div class="row align-items-end">
                <div class="form-group col-md-3">
                    <label for="date_start">Date Start</label>
                    <input type="date" class="form-control form-control-sm" name="date_start" value="<?php echo htmlspecialchars($date_start) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="date_end">Date End</label>
                    <input type="date" class="form-control form-control-sm" name="date_end" value="<?php echo htmlspecialchars($date_end) ?>">
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
                </div>
                <div class="form-group col-md-1">
                    <button class="btn btn-flat btn-block btn-success btn-sm" type="button" id="printBTN"><i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </form>
        <hr>
        <div id="printable">
            <div>
                <h4 class="text-center m-0"><?php echo $_settings->info('name') ?></h4>
                <h3 class="text-center m-0"><b>Goals Report</b></h3>
                <hr style="width:15%">
                <p class="text-center m-0">Date Between <b><?php echo date("M d, Y", strtotime($date_start)) ?> and <?php echo date("M d, Y", strtotime($date_end)) ?></b></p>
                <hr>
            </div>
            <table class="table table-bordered">
                <colgroup>
                    <col width="5%">
                    <col width="25%">
                    <col width="25%">
                    <col width="15%">
                    <col width="20%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr class="bg-gray-light">
                        <th class="text-center">#</th>
                        <th>Goal Name</th>
                        <th>Description</th>
                        <th>Target Amount</th>
                        <th>Total Contributions</th>
                        <th>Expected Completion Date</th>
                        <th>Progress (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $total_contributed = 0;
                    $total_target = 0;

                    $qry = $conn->query("SELECT g.*, 
                        COALESCE(SUM(gc.amount), 0) AS total_contributed,
                        g.expected_completion_date,
                        g.starting_capital
                        FROM goals g
                        LEFT JOIN goal_contributions gc ON g.id = gc.goal_id
                        WHERE DATE(g.created_at) BETWEEN '{$date_start_esc}' AND '{$date_end_esc}'
                        AND g.user_id = '{$user_id}'
                        GROUP BY g.id
                        ORDER BY g.created_at ASC");

                    if (!$qry) {
                        echo "<tr><td colspan='7' class='text-danger text-center'>SQL Error: " . $conn->error . "</td></tr>";
                    } elseif ($qry->num_rows > 0) {
                        while($row = $qry->fetch_assoc()):
                            $starting_capital = $row['starting_capital'] ?? 0;
                            $additional_contributions = $row['total_contributed'] ?? 0;
                            $contributed = $starting_capital + $additional_contributions;
                            $target = $row['target_amount'] ?? 0;
                            $progress = $target > 0 ? ($contributed / $target) * 100 : 0;
                            $expected_completion_date = $row['expected_completion_date'] ?? 'N/A';

                            $total_contributed += $contributed;
                            $total_target += $target;
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td><?php echo htmlspecialchars($row['goal_name']) ?></td>
                        <td><?php echo htmlspecialchars($row['goal_description']) ?></td>
                        <td class="text-right"><?php echo number_format($target, 2) ?></td>
                        <td class="text-right"><?php echo number_format($contributed, 2) ?></td>
                        <td class="text-right"><?php echo htmlspecialchars($expected_completion_date) ?></td>
                        <td class="text-right"><?php echo round($progress, 2) . "%" ?></td>
                    </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo '<tr><td colspan="7" class="text-center">No Data...</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right px-3" colspan="3"><b>Total</b></td>
                        <td class="text-right"><b><?php echo number_format($total_target, 2) ?></b></td>
                        <td class="text-right"><b><?php echo number_format($total_contributed, 2) ?></b></td>
                        <td class="bg-gray"></td>
                        <td class="bg-gray"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#filter-form').submit(function(e){
            e.preventDefault();
            location.href = "./?page=reports/goals&date_start=" + $('[name="date_start"]').val() + "&date_end=" + $('[name="date_end"]').val();
        });

        $('#printBTN').click(function(){
            var rep = $('#printable').clone();
            var ns = $('head').clone();
            start_loader();
            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write('<html><head>' + ns.html() + '</head><body>' + rep.html() + '</body></html>');
            nw.document.close();
            setTimeout(function(){
                nw.print();
                setTimeout(function(){
                    nw.close();
                    end_loader();
                }, 500);
            }, 500);
        });
    });
</script>
