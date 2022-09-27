***REMOVED***
***REMOVED***
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
        ***REMOVED*** include_once 'include/top-nav.php' ?>

        <main>
            <h2 class="accent padding-20"><?= $document_title ?></h2>
            <div class="margin-top-20">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
                        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
                        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                <canvas id="uploadStats" width="400" height="400"></canvas>
                <script>
                    const monthNames = ["January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"];
                    const data = JSON.parse('***REMOVED***
                        $stats = get_upload_stats_per_month(6);
                        for ($minusMonth = 0; $minusMonth < 6; $minusMonth++) {
                            $month = date('m', strtotime("-$minusMonth month"));
                            foreach ($stats as $s)  // fill the fields of all 6 months even if there's no data for them
                                $results[$month] = $s[0] == $month ? $s[1] : 0;
                    ***REMOVED***
                        if (isset($results)) echo json_encode($results); ?>');
                    let months = [];    // map month numbers to names
                    Object.keys(data).reverse().forEach(monthNum => {
                        months.push(monthNames[parseInt(monthNum) - 1])
                ***REMOVED***);
                    const ctx = document.getElementById('uploadStats').getContext('2d');
                    const uploadStatsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: '# of Uploaded Songs per Month in the Last 6 Months',
                                data: Object.values(data).reverse(),
                                backgroundColor: ['#4343EF'],
                                borderColor: ['#4343EF'],
                                borderWidth: 2
                        ***REMOVED***]
                    ***REMOVED***,
                        options: {
                            responsive: true,
                            title: {
                                display: true,
                                text: 'Uploads'
                        ***REMOVED***,
                            scales: {
                                y: {
                                    beginAtZero: true
                            ***REMOVED***
                        ***REMOVED***
                    ***REMOVED***
                ***REMOVED***);
                </script>
            </div>
        </main>

    </div>
***REMOVED*** include_once 'include/footer.php' ?>