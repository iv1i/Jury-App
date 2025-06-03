@extends('layouts.noneslidebar')
@section('css')
    <style>
        .scoreboard-container {
            background-color: var(--app-bg-2);
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
            box-shadow: var(--filter-shadow);
        }

        .scoreboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--table-border);
        }

        .scoreboard-title {
            color: var(--app-content-main-color);
            font-size: 24px;
            font-weight: 600;
        }

        .scoreboard-controls {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--action-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--action-color-hover);
        }

        .btn-secondary {
            background-color: var(--filter-reset);
            color: var(--app-content-main-color);
            border: 1px solid var(--app-border-color);
        }

        .btn-secondary:hover {
            background-color: var(--sidebar-hover-link);
        }

        .scoreboard-table {
            width: 100%;
            border-collapse: collapse;
            color: var(--app-content-main-color);
        }

        .scoreboard-table th {
            background-color: var(--table-header);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }

        .scoreboard-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--table-border);
        }

        .scoreboard-table tr:last-child td {
            border-bottom: none;
        }

        .scoreboard-table tr:hover {
            background-color: var(--sidebar-hover-link);
        }

        .team-name {
            font-weight: 500;
        }

        .team-score {
            font-weight: 600;
            color: var(--txt-color);
        }

        .service-cell {
            text-align: center;
            font-family: monospace;
        }

        .service-up {
            color: #4caf50;
        }

        .service-down {
            color: #f44336;
        }

        .service-unknown {
            color: #ff9800;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: var(--app-bg-2);
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: var(--filter-shadow);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--table-border);
        }

        .modal-title {
            color: var(--app-content-main-color);
            font-size: 20px;
            font-weight: 600;
        }

        .close-btn {
            color: var(--app-content-main-color);
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-body {
            margin-bottom: 20px;
            color: var(--app-content-main-color);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid var(--app-border-color);
            background-color: var(--app-bg);
            color: var(--app-content-main-color);
        }

        .submit-btn {
            background-color: var(--action-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .submit-btn:hover {
            background-color: var(--action-color-hover);
        }

        .timer {
            font-size: 18px;
            font-weight: 600;
            color: var(--txt-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .instructions-list {
            padding-left: 20px;
        }

        .instructions-list li {
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('title', 'CTF Attack-Defense Scoreboard')
@section('appcontent')
    <div style="text-align: center; width: 100%; height: 2.2vh; color: var(--app-bg-inv);background-color: var(--app-bg-2); position: fixed; top: 0%; opacity: 0.5;font-family: cursive; font-size: 12px">
        <b>By SharLike</b>
    </div>
    <div class="scoreboard-container">
        <div class="scoreboard-header">
            <h1 class="scoreboard-title">CTF Attack-Defense Scoreboard</h1>
            <div class="scoreboard-controls">
                <button class="btn btn-primary" id="submitFlagBtn">Submit Flag</button>
                <button class="btn btn-secondary" id="instructionsBtn">Instructions</button>
            </div>
        </div>

        <div class="timer" id="contestTimer">Time remaining: 05:23:47</div>

        <div class="table-responsive">
            <table class="scoreboard-table">
                <thead>
                <tr>
                    <th>Rank</th>
                    <th>Team</th>
                    <th>Score</th>
                    <th>Service 1</th>
                    <th>Service 2</th>
                    <th>Service 3</th>
                    <th>Service 4</th>
                    <th>Attack</th>
                    <th>Defense</th>
                    <th>SLA</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td class="team-name">Red Team</td>
                    <td class="team-score">1250</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td class="service-cell service-up">UP</td>
                    <td>+350</td>
                    <td>-100</td>
                    <td>95%</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="team-name">Blue Team</td>
                    <td class="team-score">1100</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-unknown">?</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-up">UP</td>
                    <td>+250</td>
                    <td>-150</td>
                    <td>90%</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="team-name">Green Team</td>
                    <td class="team-score">980</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td>+180</td>
                    <td>-200</td>
                    <td>85%</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="team-name">Yellow Team</td>
                    <td class="team-score">750</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-up">UP</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td>+150</td>
                    <td>-350</td>
                    <td>75%</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td class="team-name">Purple Team</td>
                    <td class="team-score">500</td>
                    <td class="service-cell service-unknown">?</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td class="service-cell service-down">DOWN</td>
                    <td class="service-cell service-unknown">?</td>
                    <td>+50</td>
                    <td>-500</td>
                    <td>60%</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Flag Submission Modal -->
    <div id="flagModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Submit Flag</h2>
                <span class="close-btn">&times;</span>
            </div>
            <div class="modal-body">
                <form id="flagForm">
                    <div class="form-group">
                        <label for="flagInput" class="form-label">Flag:</label>
                        <input type="text" id="flagInput" class="form-input" placeholder="Enter your flag here" required>
                    </div>
                    <div class="form-group">
                        <label for="serviceSelect" class="form-label">Service:</label>
                        <select id="serviceSelect" class="form-input" required>
                            <option value="">Select service</option>
                            <option value="service1">Service 1</option>
                            <option value="service2">Service 2</option>
                            <option value="service3">Service 3</option>
                            <option value="service4">Service 4</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Instructions Modal -->
    <div id="instructionsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">CTF Attack-Defense Instructions</h2>
                <span class="close-btn">&times;</span>
            </div>
            <div class="modal-body">
                <h3>How to play:</h3>
                <ol class="instructions-list">
                    <li>Find vulnerabilities in the services to capture flags</li>
                    <li>Submit captured flags to earn attack points</li>
                    <li>Defend your services from other teams to prevent losing points</li>
                    <li>Maintain high SLA (Service Level Agreement) by keeping your services running</li>
                    <li>The team with the highest score at the end wins</li>
                </ol>

                <h3>Scoring:</h3>
                <ul class="instructions-list">
                    <li><strong>Attack:</strong> +100 points per valid flag submitted</li>
                    <li><strong>Defense:</strong> -50 points per flag stolen from your services</li>
                    <li><strong>SLA:</strong> Bonus points based on service uptime</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Modal functionality
        const flagModal = document.getElementById('flagModal');
        const instructionsModal = document.getElementById('instructionsModal');
        const submitFlagBtn = document.getElementById('submitFlagBtn');
        const instructionsBtn = document.getElementById('instructionsBtn');
        const closeBtns = document.getElementsByClassName('close-btn');

        submitFlagBtn.onclick = function() {
            flagModal.style.display = 'block';
        }

        instructionsBtn.onclick = function() {
            instructionsModal.style.display = 'block';
        }

        for (let i = 0; i < closeBtns.length; i++) {
            closeBtns[i].onclick = function() {
                flagModal.style.display = 'none';
                instructionsModal.style.display = 'none';
            }
        }

        window.onclick = function(event) {
            if (event.target == flagModal) {
                flagModal.style.display = 'none';
            }
            if (event.target == instructionsModal) {
                instructionsModal.style.display = 'none';
            }
        }

        // Form submission
        document.getElementById('flagForm').onsubmit = function(e) {
            e.preventDefault();
            const flag = document.getElementById('flagInput').value;
            const service = document.getElementById('serviceSelect').value;

            // Here you would typically send the data to the server
            console.log('Submitting flag:', flag, 'for service:', service);

            // Show success message (in a real app, you'd handle the response)
            alert('Flag submitted successfully!');

            // Reset form and close modal
            document.getElementById('flagForm').reset();
            flagModal.style.display = 'none';
        }

        // Timer simulation (in a real app, this would sync with server time)
        function updateTimer() {
            // This is just a simulation - in a real app, you'd get the time from the server
            const timerElement = document.getElementById('contestTimer');
            let seconds = 5 * 3600 + 23 * 60 + 47; // 5 hours, 23 minutes, 47 seconds

            setInterval(function() {
                seconds--;
                if (seconds < 0) seconds = 0;

                const hours = Math.floor(seconds / 3600);
                const minutes = Math.floor((seconds % 3600) / 60);
                const secs = seconds % 60;

                timerElement.textContent = `Time remaining: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            }, 1000);
        }

        updateTimer();
    </script>
@endsection
