<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessRestriction {

    private $timestamp_file = './access_timestamp.json';
    private $allowed_duration = 120;  // 1 hour in seconds
    private $restricted_duration = 600;  // 15 minutes in seconds

    public function restrictAccess() {
        $current_time = time();
        $data = $this->readTimestampFile();

        if ($data === null) {
            // Initialize file if missing or invalid
            $data = ['last_allowed_time' => $current_time];
            $this->writeTimestampFile($data);
        }

        $elapsed_since_last_allowed = $current_time - $data['last_allowed_time'];

        if ($elapsed_since_last_allowed < $this->allowed_duration) {
            // Within allowed 1 hour → Let user access
            return;
        } elseif ($elapsed_since_last_allowed < ($this->allowed_duration + $this->restricted_duration)) {
            // Within restriction period → Show restriction page with countdown
            $remaining_seconds = ($this->allowed_duration + $this->restricted_duration) - $elapsed_since_last_allowed;

            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Access Restricted</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; }
                    .timer { font-size: 48px; margin-top: 20px; }
                </style>
            </head>
            <body>
                <h1>Access Temporarily Restricted</h1>
                <p>The system is under scheduled maintenance.</p>
                <p>Time remaining until access is restored:</p>
                <div class='timer' id='countdown'></div>

                <script>
                    var remainingSeconds = {$remaining_seconds};

                    function updateTimer() {
                        var minutes = Math.floor(remainingSeconds / 60);
                        var seconds = remainingSeconds % 60;
                        document.getElementById('countdown').innerText = 
                            minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');

                        if (remainingSeconds <= 0) {
                            location.reload();
                        } else {
                            remainingSeconds--;
                        }
                    }

                    updateTimer();
                    setInterval(updateTimer, 1000);
                </script>
            </body>
            </html>";
            exit;
        } else {
            // After restriction window → reset timer
            $data['last_allowed_time'] = $current_time;
            $this->writeTimestampFile($data);
        }
    }

    private function readTimestampFile() {
        if (!file_exists($this->timestamp_file)) {
            return null;
        }

        $content = file_get_contents($this->timestamp_file);
        return json_decode($content, true);
    }

    private function writeTimestampFile($data) {
        file_put_contents($this->timestamp_file, json_encode($data));
    }
}
