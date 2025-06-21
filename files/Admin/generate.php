<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Neon Admin Panel" />
    <meta name="author" content="" />

    <link rel="icon" type="image/jpg" href="../../img/Brgy. Estefania Logo (Old).png">
    <title>Barangay Estefania Admin - Driver ID System</title>

    <link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
    <link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/neon-core.css">
    <link rel="stylesheet" href="assets/css/neon-theme.css">
    <link rel="stylesheet" href="assets/css/neon-forms.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <script src="assets/js/jquery-1.11.3.min.js"></script>

    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="page-body" data-url="http://neon.dev">
<div class="page-container">
    <?php include "sidebar.php" ?>

    <div class="main-content">
        <?php include "header.php" ?>
        <hr />

        <?php
        // Assuming you have already established a database connection
        include "../../connections.php";

        // Fetch data from the database where verification_stat is 'Registered' and renew_stat is 'Active'
        $query = "SELECT formatted_id, first_name, middle_name, last_name, driver_category, 
                  CONCAT(a.association_name, ' - ', a.association_area) AS association, 
                  verification_stat, renew_stat
                  FROM tbl_driver d
                  LEFT JOIN tbl_association a ON d.fk_association_id = a.association_id
                  WHERE verification_stat = 'Registered' AND renew_stat = 'Active'";
        $result = mysqli_query($connections, $query);

        // Check if query was successful
        if ($result) {
            ?>
            <h3>Generate Driver's ID</h3>
            <br />

            <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $table4 = jQuery("#table-4");
        var accountType = <?php echo json_encode($account_type); ?>;

        $table4.DataTable({
            dom: 'Bfrtip',
            buttons: accountType == 1 ? [ // Only show buttons if account_type is 1
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Actions)
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Actions)
                    }
                },
                {
                    extend: 'csvHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Actions)
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':not(:last-child)' // Exclude the last column (Actions)
                    }
                }
            ] : [] // No buttons if account_type is not 1
        });

        // Handle button click for ID generation modal
        $('#table-4').on('click', 'button[data-target="#generateIDModal"]', function() {
            var driverId = $(this).data('id');
            $('#modal-frame').attr('src', 'ID_generation.php?id=' + driverId);
        });
    });
</script>

            <table class="table table-bordered datatable" id="table-4">
                <thead>
                    <tr>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Vehicle Type</th>
                        <th>Association</th>
                        <th>Verification Status</th>
                        <th>Renew Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        $renew_stat_class = ($row['renew_stat'] == 'Active') ? 'active-renew' : '';
                        ?>
                        <tr>
                            <td><?php echo $row['formatted_id']; ?></td>
                            <td><?php echo $row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']; ?></td>
                            <td><?php echo $row['driver_category']; ?></td>
                            <td><?php echo $row['association']; ?></td>
                            <td class="center"><?php echo $row['verification_stat']; ?></td>
                            <td class="center <?php echo $renew_stat_class; ?>"><?php echo $row['renew_stat']; ?></td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm btn-icon icon-left" data-toggle="modal" data-target="#generateIDModal" data-id="<?php echo $row['formatted_id']; ?>">
                                    <i class="entypo-vcard"></i>
                                    Generate Driver ID
                                </button>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Driver ID</th>
                        <th>Driver Name</th>
                        <th>Vehicle Type</th>
                        <th>Association</th>
                        <th>Verification Status</th>
                        <th>Renew Status</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>

            <br />
        <?php
        } else {
            // Query failed
            echo "Error: " . mysqli_error($connections);
        }

        // Close the database connection
        mysqli_close($connections);
        ?>

        <br />

        <!-- Footer -->
        <?php include "footer.php" ?>

        <!-- Imported styles on this page -->
        <link rel="stylesheet" href="assets/js/datatables/datatables.css">
        <link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
        <link rel="stylesheet" href="assets/js/select2/select2.css">

        <!-- Modal HTML -->
        <div id="generateIDModal" class="modal fade" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generate Driver ID</h4>
              </div>
              <div class="modal-body">
                <iframe id="modal-frame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom scripts (common) -->
        <script src="assets/js/gsap/TweenMax.min.js"></script>
        <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
        <script src="assets/js/bootstrap.js"></script>
        <script src="assets/js/joinable.js"></script>
        <script src="assets/js/resizeable.js"></script>
        <script src="assets/js/neon-api.js"></script>

        <!-- Imported scripts on this page -->
        <script src="assets/js/datatables/datatables.js"></script>
        <script src="assets/js/select2/select2.min.js"></script>
        <script src="assets/js/neon-chat.js"></script>

        <!-- JavaScripts initializations and stuff -->
        <script src="assets/js/neon-custom.js"></script>

        <!-- Demo Settings -->
        <script src="assets/js/neon-demo.js"></script>

        <!-- Add this JavaScript to apply the class after the table is rendered -->
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.renew-status').each(function() {
                if ($(this).text().trim() === 'Active') {
                    $(this).addClass('active-renew');
                }
            });
        });
        </script>

        <!-- Add this to your existing CSS -->
        <style>
        .active-renew {
            color: green;
        }
        </style>

    </div>
</div>
</body>
</html>
