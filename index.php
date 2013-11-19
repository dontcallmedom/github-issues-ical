<?php

$user = ""; // github username you want to get assigned issues of
$auth_token = ""; // get it as explained in https://help.github.com/articles/creating-an-access-token-for-command-line-use

require_once 'vendor/autoload.php';
$client = new Github\Client();
$client->authenticate($auth_token, null, Github\Client::AUTH_HTTP_TOKEN);
$issues = json_decode($client->getHttpClient()->get('issues')->getBody(true));

function toIcalDate($date) {
  return str_replace(array('-',':'), '', $date);
}

echo("BEGIN:VCALENDAR\r\n");
echo("VERSION:2.0\r\n");
echo("X-WR-CALNAME: Github issues assigned to " . $user . "\r\n");
echo("PRODID:-//dontcallmedom//Github-Issues//EN\r\n");
echo("CALSCALE:GREGORIAN\r\n");

foreach ($issues as $issue) {
  if ($issue->assignee->login == $user && $issue->state == "open") {
    $repoName = $issue->repository->name;
    $repoOwner = $issue->repository->owner->login;
    echo("BEGIN:VTODO\r\n");
    echo("DTSTART:" .  toIcalDate($issue->created_at) . "\r\n");
    if ($issue->milestone && $issue->milestone->due_on) {
      echo("DUE:" . toIcalDate($issue->milestone->due_on) . "\r\n");
    }
    echo("SUMMARY: " . preg_replace('/([\\\,;])/s', '\\\$1', preg_replace('/([\n])/s', '/\\\n', "[".$repoName."] ".$issue->title))."\r\n");
    echo("UID:".$issue->url."\r\n");
    echo("URL:".$issue->url."\r\n");
    echo("END:VTODO\r\n");
  }
}
echo("END:VCALENDAR\r\n");
