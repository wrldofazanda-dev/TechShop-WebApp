<?php

// destroy sessions and send to index
session_start();
session_destroy();

header('Location: ../index.php');
