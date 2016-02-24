<!DOCTYPE html>
<html lang="en">
<!-- printheader -->
    <head>
    <meta charset="utf-8">
    <title>WHAM! - Web Host Account Manager!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="<?= base_url() ?>includes/bootstrap/css/bootstrap.css" rel="stylesheet" />
    <link href="<?= base_url() ?>includes/bootstrap/css/wham.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="<?= base_url() ?>includes/bootstrap/js/bootstrap.min.js"></script>
<?php if (isset($jquery) && $jquery == "ui") :?>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<?php endif; ?>
<!-- printheader ends -->     