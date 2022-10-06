<?php
session_start();
$document_title = 'Administration • Statistics';
if (!isset($_SESSION['identifier']))
    header('Location: login.php');
if (!$_SESSION['is_admin'])
    header('Location: index.php');
require_once 'utils/queries.php';
require_once 'utils/components.php';

include_once 'include/header.php';
include_once 'include/sidebar.php' ?>
    <div class="root-container">
        <?php include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20"><?= $document_title ?></h2>
            <div class="margin-top-20">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
                        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
                        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <h3 class="margin-lr-20">Monthly Uploads</h3>
                <canvas id="uploadStats" width="400" height="400"></canvas>
                <h3 class="margin-lr-20 padding-top-40">Monthly Comments</h3>
                <canvas id="commentStats" width="400" height="400"></canvas>
                <script>
                    const monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"];

                    // monthly uploads
                    const uploadsData = JSON.parse('<?php
                        $stats = get_upload_stats_per_month(6);
                        for ($minusMonth = 0; $minusMonth < 6; $minusMonth++) {
                            $month = date('m', strtotime("-$minusMonth month"));
                            foreach ($stats as $s)  // fill the fields of all 6 months even if there's no data for them
                                $results[$month] = $s[0] == $month ? $s[1] : 0;
                        }
                        if (isset($results)) echo json_encode($results); ?>');
                    let months = [];    // map month numbers to names
                    Object.keys(uploadsData).reverse().forEach(monthNum => {
                        months.push(monthNames[parseInt(monthNum) - 1])
                    });
                    const ctxUploads = document.getElementById('uploadStats').getContext('2d');
                    const uploadStatsChart = new Chart(ctxUploads, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: '# of Uploaded Songs per Month in the Last 6 Months',
                                data: Object.values(uploadsData).reverse(),
                                backgroundColor: ['#4343EF'],
                                borderColor: ['#4343EF'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Uploads'
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // montly comments
                    const commentsData = JSON.parse('<?php
                        $stats = get_comment_stats_per_month(6);
                        $results = [];
                        for ($minusMonth = 0; $minusMonth < 6; $minusMonth++) {
                            $month = date('m', strtotime("-$minusMonth month"));
                            foreach ($stats as $s)  // fill the fields of all 6 months even if there's no data for them
                                $results[$month] = $s[0] == $month ? $s[1] : 0;
                        }
                        if (isset($results)) echo json_encode($results); ?>');
                    months = [];    // map month numbers to names
                    Object.keys(commentsData).reverse().forEach(monthNum => {
                        months.push(monthNames[parseInt(monthNum) - 1])
                    });
                    const ctxComments = document.getElementById('commentStats').getContext('2d');
                    const commentStatsChart = new Chart(ctxComments, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: '# of Comments per Month in the Last 6 Months',
                                data: Object.values(commentsData).reverse(),
                                backgroundColor: ['#4343EF'],
                                borderColor: ['#4343EF'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Comments'
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
        </main>

    </div>
<?php include_once 'include/footer.php' ?>