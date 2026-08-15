<?php
// axisdtf.com enquiry handler
$to      = "sales@uvprinterindia.com";
$from    = "noreply@axisdtf.com";
$log     = __DIR__ . "/enquiries.csv";

if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: index.html"); exit; }
if (!empty($_POST["company"])) { header("Location: thank-you.html"); exit; }

function f($k){ return isset($_POST[$k]) ? trim(strip_tags($_POST[$k])) : ""; }
$name = f("name"); $phone = f("phone"); $city = f("city");
$interest = f("interest"); $machine = f("machine"); $msg = f("message");

if ($name === "" || $phone === "") { header("Location: contact.html?error=1"); exit; }

$row = [date("Y-m-d H:i:s"), $name, $phone, $city, $interest, $machine, $msg,
        $_SERVER["REMOTE_ADDR"] ?? ""];
if ($fh = @fopen($log, "a")) { fputcsv($fh, $row); fclose($fh); }

$subject = "New axisdtf.com enquiry: $name, $city";
$body  = "Name: $name\n";
$body .= "Phone: $phone\n";
$body .= "City: $city\n";
$body .= "Interested in: $interest\n";
$body .= "Page / machine: $machine\n";
$body .= "Message: $msg\n";
$body .= "Time: " . date("d M Y, H:i") . "\n";

$headers  = "From: Axis DTF Website <$from>\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
@mail($to, $subject, $body, $headers);

header("Location: thank-you.html");
exit;
